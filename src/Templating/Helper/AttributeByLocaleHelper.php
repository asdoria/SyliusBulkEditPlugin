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
use Sylius\Component\Product\Model\ProductAttributeValueInterface;
use Symfony\Component\Templating\Helper\Helper;

/**
 * Class AttributeByLocaleHelper.
 */
class AttributeByLocaleHelper extends Helper implements AttributeByLocaleHelperInterface
{
    public function getAttributesByLocale(AttributeSubjectInterface $subject, ChannelInterface $channel): array
    {
        $attributeValues = [];
        $nonTranslatableAttributeValues = $subject->getAttributes()
            ->filter(fn(ProductAttributeValueInterface $value) => !$value->getAttribute()->isTranslatable());
        $translatableAttributeValues = $subject->getAttributes()
            ->filter(fn(ProductAttributeValueInterface $value) => $value->getAttribute()->isTranslatable());

        $initialValues = array_reduce(
            $nonTranslatableAttributeValues->toArray(),
            function (array $acc, $item) {
                $carry[$item->getAttribute()->getCode()] = $item;
                return $carry;
            },
            []
        );

        foreach ($translatableAttributeValues as $attributeValue) {
            if (!isset($attributeValues[$attributeValue->getLocaleCode()])) {
                $attributeValues[$attributeValue->getLocaleCode()] = $initialValues;
            }

            $attributeValues[$attributeValue->getLocaleCode()][$attributeValue->getAttribute()->getCode()] = $attributeValue;
        }

        return $attributeValues;
    }

    public function getName(): string
    {
        return 'asdoria_bulk_edit_attribute_by_locale';
    }
}
