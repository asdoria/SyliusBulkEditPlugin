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

namespace Asdoria\SyliusBulkEditPlugin\Templating\Helper;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Symfony\Component\Templating\Helper\Helper;

/**
 * Class ProductOptionByVariantHelper
 * @package Asdoria\SyliusBulkEditPlugin\Templating\Helper
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ProductOptionByVariantHelper extends Helper implements ProductOptionByVariantHelperInterface
{

    /**
     * @param ProductVariantInterface $subject
     *
     * @return Collection
     */
    public function getOptionsByVariant(ProductVariantInterface $subject): Collection
    {
        $options = new ArrayCollection();
        foreach ($subject->getOptionValues() as $optionValues) {
            if ($options->contains($optionValues->getOption())) continue;
            $options->add($optionValues->getOption());
        }

        return $options;
    }

    public function getName(): string
    {
        return 'asdoria_bulk_edit_product_option_by_variant';
    }
}
