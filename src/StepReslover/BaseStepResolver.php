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

namespace Asdoria\SyliusBulkEditPlugin\Resolver\StepResolver;

use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BaseStepResolver.
 * @package Asdoria\Bundle\BulkEditBundle\Resolver\StepResolver
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
abstract class BaseStepResolver implements StepResolverInterface
{
    protected ?string $type = null;
    protected ?string $context = null;
    protected ?int $step = null;
    protected ?FormTypeInterface $form = null;

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getContext(): ?string
    {
        return $this->context;
    }

    /**
     * @param string|null $context
     */
    public function setContext(?string $context): void
    {
        $this->context = $context;
    }

    /**
     * @return int|null
     */
    public function getStep(): ?int
    {
        return $this->step;
    }

    /**
     * @param int|null $step
     */
    public function setStep(?int $step): void
    {
        $this->step = $step;
    }

    /**
     * @return FormTypeInterface|null
     */
    public function getForm(): ?FormTypeInterface
    {
        return $this->form;
    }

    /**
     * @param FormTypeInterface|null $form
     */
    public function setForm(?FormTypeInterface $form): void
    {
        $this->form = $form;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request): bool
    {
        $formData = $request->request->all('form');
        $type     = $formData['steps'][1]['type'] ?? $request->request->get('type');

        return
            $type === $this->type
            && $request->get('context') === $this->context
            && intval($request->request->get('step')) === $this->step
            ;
    }
}
