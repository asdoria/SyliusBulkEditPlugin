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

use Asdoria\SyliusBulkEditPlugin\Templating\Helper\AttributeByLocaleHelperInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;

/**
 * Class LocalesExtension.
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
class AttributeByLocaleExtension extends AbstractExtension implements ExtensionInterface
{
    /**
     * LocaleExtension constructor.
     *
     * @param EntityRepository $localeRepository
     */
    public function __construct(protected AttributeByLocaleHelperInterface $helper)
    {
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('attributes_by_locale', [$this->helper, 'getAttributesByLocale']),
        ];
    }
}

