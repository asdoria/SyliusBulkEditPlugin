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


use Sylius\Component\Registry\ServiceRegistry;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

/**
 * Class FormConfigurationStepResolver
 * @package Asdoria\SyliusBulkEditPlugin\StepResolver
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class StepFormResolver extends ServiceRegistry
{
    public function __construct(
        protected ServiceRegistry $stepRegistry
    )
    {
    }

    /**
     * @param int    $step
     * @param string $context
     *
     * @return FormTypeInterface|null
     */
    public function resolveFormForStepAndContext(int $step, string $context): ?FormTypeInterface
    {
        /** @var StepResolverInterface $stepResolver */
        foreach ($this->stepRegistry->all() as $stepResolver) {
            if ($stepResolver->supports($step, $context)) {
                return $stepResolver->getForm();
            }
        }

        return null;
    }
}
