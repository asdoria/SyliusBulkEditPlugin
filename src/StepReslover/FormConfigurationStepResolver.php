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

namespace Asdoria\SyliusBulkEditPlugin\StepResolver;


use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

/**
 * Class FormConfigurationStepResolver.
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
class FormConfigurationStepResolver
{
    /** @var StepResolverInterface[] */
    protected array $stepResolvers = [];

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        protected FormFactoryInterface $formFactory,
    )
    {
    }

    /**
     * @param StepResolverInterface $stepResolver
     *
     * @return void
     */
    public function addStepResolver(StepResolverInterface $stepResolver): void
    {
        $this->stepResolvers[] = $stepResolver;
    }

    /**
     * @param Request $request
     *
     * @return FormInterface|null
     */
    public function resolve(Request $request): ?FormInterface
    {
        $steps = $request->request->all('form') ?? [];
        $steps = $steps['steps'] ?? [];

        $isSubmitted = isset($steps[sizeof($steps)]['submit']);
        $step        = $isSubmitted ? sizeof($steps) : sizeof($steps) + 1;

        $form = $this->formFactory->create(FormType::class, null, [
            'csrf_protection'    => false,
            'validation_groups'  => 'sylius',
            'allow_extra_fields' => true,
        ]);

        $steps = $this->formFactory->createNamed(
            'steps',
            FormType::class,
            null,
            ['auto_initialize' => false]
        );

        for ($i = $step; $i > 0; $i--) {
            $stepForm = $this->resolveFormForStep($request, $i);

            if (empty($stepForm)) {
                if ($i !== $step) continue;

                $steps->add($this->createSubmitStep($this->formFactory, $i));
                continue;
            }

            try {
                $steps->add(strval($i), get_class($stepForm), [
                    'auto_initialize' => false,
                    'context'         => $request->get('context') ?? null,
                ]);
            } catch (UndefinedOptionsException $e) {
                $steps->add(strval($i), get_class($stepForm), [
                    'auto_initialize' => false,
                    'mapped'          => $step !== $i || isset($steps[$step]),
                ]);
            }
        }

        $form->add($steps);

        return $form;
    }

    /**
     * @param Request $request
     * @param int     $step
     *
     * @return FormTypeInterface|null
     */
    protected function resolveFormForStep(Request $request, int $step): ?FormTypeInterface
    {
        $request->request->set('step', $step);

        foreach ($this->stepResolvers as $stepResolver) {
            if ($stepResolver->supports($request)) {
                return $stepResolver->getForm();
            }
        }

        return null;
    }

    /**
     * @param FormFactoryInterface $formFactory
     * @param int                  $step
     *
     * @return FormInterface
     */
    protected function createSubmitStep(FormFactoryInterface $formFactory, int $step): FormInterface
    {
        $submitForm = $formFactory->createNamed(strval($step), FormType::class, null, [
            'auto_initialize' => false,
            'empty_data'      => null,
        ]);
        $submitForm->add('submit', SubmitType::class, [
            'attr' => [
                'class' => 'ui primary button',
                'data-bulk-edit-action-requires-confirmation' => true
            ],
        ]);

        return $submitForm;
    }
}
