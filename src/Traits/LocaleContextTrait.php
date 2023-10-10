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

namespace Asdoria\SyliusBulkEditPlugin\Traits;

use Sylius\Component\Locale\Context\LocaleContextInterface;

/**
 * Class LocaleContextTrait.
 */
trait LocaleContextTrait
{
    protected ?LocaleContextInterface $localeContext = null;

    public function getLocaleContext(): ?LocaleContextInterface
    {
        return $this->localeContext;
    }

    public function setLocaleContext(?LocaleContextInterface $localeContext): void
    {
        $this->localeContext = $localeContext;
    }
}
