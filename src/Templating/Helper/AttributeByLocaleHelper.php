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

use Sylius\Component\Attribute\Model\AttributeSubjectInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Templating\Helper\Helper;

/**
 * Class AttributeByLocaleHelper.
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
class AttributeByLocaleHelper extends Helper implements AttributeByLocaleHelperInterface
{
    /**
     * @param AttributeSubjectInterface $subject
     * @param ChannelInterface          $channel
     *
     * @return array
     */
    public function getAttributesByLocale(AttributeSubjectInterface $subject, ChannelInterface $channel): array
    {
        $attributes = [];
        foreach ($subject->getAttributes() as $attribute) {
            if (!isset($attributes[$attribute->getLocaleCode()])) $attributes[$attribute->getLocaleCode()] = [];
            $attributes[$attribute->getLocaleCode()][$attribute->getAttribute()->getCode()] = $attribute;
        }
        return $attributes;
    }

    public function getName(): string
    {
        return 'asdoria_bulk_edit_attribute_by_locale';
    }
}
