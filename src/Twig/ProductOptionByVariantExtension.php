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
namespace Asdoria\SyliusBulkEditPlugin\Twig;

use Asdoria\SyliusBulkEditPlugin\Templating\Helper\ProductOptionByVariantHelperInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;

/**
 * Class ProductOptionByVariantExtension
 * @package Asdoria\SyliusBulkEditPlugin\Twig
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
final class ProductOptionByVariantExtension extends AbstractExtension implements ExtensionInterface
{
    /**
     * @param ProductOptionByVariantHelperInterface $helper
     */
    public function __construct(protected ProductOptionByVariantHelperInterface $helper)
    {
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('options_by_variant', [$this->helper, 'getOptionsByVariant']),
        ];
    }
}

