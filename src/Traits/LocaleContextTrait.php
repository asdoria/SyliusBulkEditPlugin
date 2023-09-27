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
 * @package Asdoria\SyliusBulkEditPlugin\Traits
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
trait LocaleContextTrait
{
    protected ?LocaleContextInterface $localeContext = null;

    /**
     * @return LocaleContextInterface|null
     */
    public function getLocaleContext(): ?LocaleContextInterface
    {
        return $this->localeContext;
    }

    /**
     * @param LocaleContextInterface|null $localeContext
     */
    public function setLocaleContext(?LocaleContextInterface $localeContext): void
    {
        $this->localeContext = $localeContext;
    }
}
