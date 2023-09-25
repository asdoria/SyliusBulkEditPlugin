<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin\Step;

use Symfony\Component\Form\FormTypeInterface;

/**
 * Class StepInterface.
 * @package Asdoria\SyliusBulkEditPlugin\Step
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface StepInterface
{
    /**
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void;

    /**
     * @return string|null
     */
    public function getContext(): ?string;

    /**
     * @param string|null $context
     */
    public function setContext(?string $context): void;

    /**
     * @return int|null
     */
    public function getStep(): ?int;

    /**
     * @param int|null $step
     */
    public function setStep(?int $step): void;

    /**
     * @return FormTypeInterface|null
     */
    public function getForm(): ?FormTypeInterface;

    /**
     * @param FormTypeInterface|null $form
     */
    public function setForm(?FormTypeInterface $form): void;
}
