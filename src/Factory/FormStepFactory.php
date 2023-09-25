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

namespace Asdoria\SyliusBulkEditPlugin\Factory;

use Sylius\Component\Registry\ServiceRegistry;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;


/**
 * Class FormStepFactory
 * @package Asdoria\SyliusBulkEditPlugin\Factory
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class FormStepFactory implements FormStepFactoryInterface
{
    /**
     * @param FormFactoryInterface $formFactory
     * @param RequestStack         $requestStack
     */
    public function __construct(
        protected FormFactoryInterface $formFactory,
        protected RequestStack $requestStack
    ) {
    }

    /**
     * @param Request $request
     *
     * @return FormInterface|null
     */
    public function create(): ?FormInterface
    {
        $request = $this->requestStack->getCurrentRequest();
        $form    = $this->formFactory->create(FormType::class, null, [
            'csrf_protection'    => false,
            'validation_groups'  => 'sylius',
            'allow_extra_fields' => true,
        ]);

        $formSteps = $this->formFactory->createNamed(
            'steps',
            FormType::class,
            null,
            ['auto_initialize' => false]
        );

        $currentStep = $this->getCurrentStep($request);

        for ($i = $currentStep; $i > 0; $i--) {
            $stepForm = $this->resolveFormForStep($request, $i);
            $stepName = strval($i);
            if (empty($stepForm)) {
                if ($i === $currentStep) {
                    $formSteps->add($this->createSubmitStep($stepName));
                }
                continue;
            }

            try {
                $formSteps->add($stepName, get_class($stepForm), [
                    'auto_initialize' => false,
                    'context'         => $request->get('context') ?? null,
                ]);
            } catch (UndefinedOptionsException $e) {
                $formSteps->add($stepName, get_class($stepForm), [
                    'auto_initialize' => false,
                    'mapped'          => $currentStep !== $i,
                ]);
            }
        }

        $form->add($steps);

        return $form;
    }

    /**
     * @param Request $request
     *
     * @return int|null
     */
    protected function getCurrentStep(Request $request): ?int {
        $steps = $request->request->all('form') ?? [];
        $steps = $steps['steps'] ?? [];

        if (empty($steps)) return null;

        $isSubmitted = isset($steps[sizeof($steps)]['submit']);

        return $isSubmitted ? sizeof($steps) : sizeof($steps) + 1;
    }

    /**
     * @param string $step
     *
     * @return FormInterface
     */
    protected function createSubmitStep(string $step): FormInterface
    {
        return $this->formFactory
            ->createNamed($step, FormType::class, null, [
                'auto_initialize' => false,
                'empty_data'      => null,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class'                                       => 'ui primary button',
                    'data-bulk-edit-action-requires-confirmation' => true
                ],
            ]);
    }
}
