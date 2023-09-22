<?php

declare(strict_types=1);

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
