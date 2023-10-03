<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin\Traits;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class RequestStackTrait.
 * @package Asdoria\SyliusBulkEditPlugin\Traits
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
trait RequestStackTrait
{
    protected ?RequestStack $requestStack = null;

    /**
     * @return RequestStack|null
     */
    public function getRequestStack(): ?RequestStack
    {
        return $this->requestStack;
    }

    /**
     * @param RequestStack|null $requestStack
     */
    public function setRequestStack(?RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
    }
}
