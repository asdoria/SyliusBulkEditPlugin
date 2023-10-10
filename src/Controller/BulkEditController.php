<?php

declare(strict_types=1);

/*
 * This file is part of the Asdoria Package.
 *
 * (c) Asdoria .
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asdoria\SyliusBulkEditPlugin\Controller;

use Asdoria\SyliusBulkEditPlugin\Form\Type\BulkEditType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class BulkEditController
 */
class BulkEditController
{
    public function __construct(
        protected Environment $twig,
        protected FormFactoryInterface $formFactory,
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(BulkEditType::class, null, [
            'context' => $request->attributes->get('context'),
        ]);

        $form->handleRequest($request);

        $isClicked = $form->has('submit') && $form->get('submit')->isClicked();

        if ($form->isSubmitted() && $form->isValid() && $isClicked) {
            $this->eventDispatcher->dispatch(
                new GenericEvent(array_merge_recursive(
                    $form->getData(),
                    $request->attributes->all(),
                )),
                'asdoria.bulk_edit.handle',
            );

            return new JsonResponse('OK', 204);
        }

        $steps = array_map(
            fn (FormView $step) => $this->twig->render('@AsdoriaSyliusBulkEditPlugin/Admin/Form/field.html.twig', ['form' => $step]),
            $form->createView()->children,
        );

        return new JsonResponse(
            [
                'steps' => $steps,
            ],
        );
    }
}
