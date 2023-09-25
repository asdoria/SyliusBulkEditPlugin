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

namespace Asdoria\SyliusBulkEditPlugin\Step;

use Asdoria\SyliusBulkEditPlugin\Traits\StepResolverTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FirstStepResolver.
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
class FirstStep implements StepInterface
{
    use StepTrait;

    /**
     * @param int    $step
     * @param string $context
     *
     * @return bool
     */
    public function supports(int $step, string $context): bool
    {
        return $context === $this->context && $step === $this->step;
    }
}
