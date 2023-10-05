<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin\Templating\Helper;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;

/**
 * Class ProductOptionByVariantHelperInterface.
 * @package Asdoria\SyliusBulkEditPlugin\Templating\Helper
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ProductOptionByVariantHelperInterface
{
    /**
     * @param ProductVariantInterface $subject
     *
     * @return Collection
     */
    public function getOptionsByVariant(ProductVariantInterface $subject): Collection;
}
