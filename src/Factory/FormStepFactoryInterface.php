<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin\Factory;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FormStepFactoryInterface.
 * @package Asdoria\SyliusBulkEditPlugin\Factory
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface FormStepFactoryInterface
{
    /**
     * @param Request $request
     *
     * @return FormInterface|null
     */
    public function create(Request $request): ?FormInterface;
}
