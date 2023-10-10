<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin\Traits;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class RequestStackTrait.
 */
trait RequestStackTrait
{
    protected ?RequestStack $requestStack = null;

    public function getRequestStack(): ?RequestStack
    {
        return $this->requestStack;
    }

    public function setRequestStack(?RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
    }
}
