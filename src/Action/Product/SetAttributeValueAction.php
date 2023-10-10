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

namespace Asdoria\SyliusBulkEditPlugin\Action\Product;

use Asdoria\SyliusBulkEditPlugin\Action\ResourceActionInterface;
use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\AttributeValueConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Asdoria\SyliusBulkEditPlugin\Traits\EntityManagerTrait;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Attribute\Model\AttributeSubjectInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * Class SetAttributeValueAction
 */
final class SetAttributeValueAction implements ResourceActionInterface
{
    use EntityManagerTrait;

    public const SET_ATTRIBUTE_VALUE = 'set_attribute_value';

    public function __construct(
        private FactoryInterface $attributeValueFactory,
        private RepositoryInterface $productAttributeRepository,
    ) {
    }

    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        Assert::isInstanceOf($resource, AttributeSubjectInterface::class);

        $configuration = $message->getConfiguration();

        if (empty($configuration)) {
            return;
        }

        $value = $configuration[AttributeValueConfigurationType::_ATTRIBUTE_VALUE_FIELD] ?? null;
        $attributeCode = $configuration[AttributeValueConfigurationType::_ATTRIBUTE_FIELD] ?? null;
        $localeCode = $configuration[AttributeValueConfigurationType::_LOCALE_CODE_FIELD] ?? null;

        if (empty($value) || empty($attributeCode)) {
            return;
        }

        $this->process($attributeCode, $value, $resource, $localeCode);
    }

    protected function process(string $attributeCode, $value, AttributeSubjectInterface $resource, ?string $localeCode = null): void
    {
        $attribute = $this->productAttributeRepository->findOneBy(['code' => $attributeCode]);

        Assert::isInstanceOf($attribute, AttributeInterface::class);

        $isTranslatable = $attribute->isTranslatable();

        $attributeValue = $isTranslatable ? $resource->getAttributeByCodeAndLocale($attributeCode, $localeCode) :
            $resource->getAttributeByCodeAndLocale($attributeCode);

        if (!$attributeValue instanceof AttributeValueInterface) {
            /** @var AttributeValueInterface $attributeValue */
            $attributeValue = $this->attributeValueFactory->createNew();
            if ($isTranslatable) {
                $attributeValue->setLocaleCode($localeCode);
            }
            $attributeValue->setAttribute($attribute);
            $resource->addAttribute($attributeValue);
            $this->getEntityManager()->persist($attributeValue);
        }

        $attributeValue->setValue($value);
    }
}
