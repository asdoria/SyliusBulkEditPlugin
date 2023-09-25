<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin\StepResolver;

use Symfony\Component\Form\FormTypeInterface;

/**
 * Class StepFormResolverInterface.
 * @package Asdoria\SyliusBulkEditPlugin\StepResolver
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface StepFormResolverInterface
{
    /**
     * @param int    $step
     * @param string $context
     *
     * @return FormTypeInterface|null
     */
    public function resolveFormForStepAndContext(int $step, string $context): ?FormTypeInterface;
}
