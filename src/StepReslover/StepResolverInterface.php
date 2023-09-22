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

use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class StepResolverInterface.
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
interface StepResolverInterface
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

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request): bool;
}
