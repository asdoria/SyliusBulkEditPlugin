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
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Attribute\Model\AttributeSubjectInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * Class SetAttributeValueAction
 * @package Asdoria\SyliusBulkEditPlugin\Action\Product
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class SetAttributeValueAction implements ResourceActionInterface
{
    use EntityManagerTrait;

    const SET_ATTRIBUTE_VALUE = 'set_attribute_value';

    /**
     * @param FactoryInterface       $attributeValueFactory
     * @param RepositoryInterface    $productAttributeRepository
     */
    public function __construct(
        protected FactoryInterface       $attributeValueFactory,
        protected RepositoryInterface    $productAttributeRepository,
    )
    {
    }

    /**
     * @param ResourceInterface             $resource
     * @param BulkEditNotificationInterface $message
     *
     * @return void
     */
    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        if (!$resource instanceof AttributeSubjectInterface) return;

        $configuration = $message->getConfiguration();

        if (empty($configuration)) return;

        $value      = $configuration[AttributeValueConfigurationType::_ATTRIBUTE_VALUE_FIELD] ?? null;
        $attributeCode  = $configuration[AttributeValueConfigurationType::_ATTRIBUTE_FIELD] ?? null;
        $localeCode = $configuration[AttributeValueConfigurationType::_LOCALE_CODE_FIELD] ?? null;

        if (empty($value) || empty($attributeCode)) return;

        $this->process($attributeCode, $value, $resource, $localeCode);
    }

    /**
     * @param AttributeValueInterface   $configAttributeValue
     * @param AttributeSubjectInterface $resource
     *
     * @return \Closure
     */
    protected function process(string $attributeCode, $value, AttributeSubjectInterface $resource, ?string $localeCode = null): void {
        $attribute = $this->productAttributeRepository->findOneBy(['code' => $attributeCode]);

        if (!$attribute instanceof AttributeInterface) return;

        $isTranslatable = $attribute->isTranslatable() && $attribute->getStorageType() === AttributeValueInterface::STORAGE_TEXT;

        $attributeValue = $isTranslatable ? $resource->getAttributeByCodeAndLocale($attributeCode, $localeCode) :
            $resource->getAttributeByCodeAndLocale($attributeCode);

        if (!$attributeValue instanceof AttributeValueInterface) {
            /** @var AttributeValueInterface $attributeValue */
            $attributeValue = $this->attributeValueFactory->createNew();
            if ($isTranslatable) $attributeValue->setLocaleCode($localeCode);
            $attributeValue->setAttribute($attribute);
            $resource->addAttribute($attributeValue);
            $this->getEntityManager()->persist($attributeValue);
        }

        $attributeValue->setValue($value);
    }
}
