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
namespace Asdoria\SyliusBulkEditPlugin\DependencyInjection;

use Asdoria\SyliusBulkEditPlugin\Resolver\FormConfigurationStepResolver;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class ConfigurationStepController.
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfigurationStepController
{
    public function __construct(
        protected Environment                   $twig,
        protected FormConfigurationStepResolver $formConfigurationStepResolver,
        protected EventDispatcherInterface      $eventDispatcher,
    )
    {
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->formConfigurationStepResolver->resolve($request);
        $form->handleRequest($request);

        $form->getErrors(true);

        if ($form->isSubmitted() && $form->isValid() && $this->isSubmitted($request)) {
            $this->eventDispatcher->dispatch(
                new GenericEvent(array_merge_recursive(
                    $form->getData(),
                    $form->getExtraData(),
                    ['context' => $request->attributes->all()['context'] ?? null],
                )),
                'bulk_edit.handle'
            );

            return new JsonResponse('OK', 204);
        }

        $steps = array_map(
            fn(FormView $step) => $this->twig->render('@AsdoriaBulkEditPlugin/_partial/show_field.html.twig', ['form' => $step]),
            $form->createView()->children['steps']->children,
        );

        return new JsonResponse(['steps' => $steps]);
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function isSubmitted(Request $request): bool
    {
        $steps = $request->request->all('form') ?? [];
        $steps = $steps['steps'] ?? null;

        if (empty($steps)) return false;

        ksort($steps);

        $lastStep = end($steps);

        return isset($lastStep['submit']);
    }
}
