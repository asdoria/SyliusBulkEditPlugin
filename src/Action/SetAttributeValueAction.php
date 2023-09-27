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

namespace Asdoria\SyliusBulkEditPlugin\Action;

use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\AttributeValueConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Asdoria\SyliusBulkEditPlugin\Traits\EntityManagerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Attribute\Model\AttributeSubjectInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * Class SetAttributeValueAction.
 * @package Asdoria\SyliusBulkEditPlugin\Action
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

        $configAttributeValue = $configuration[AttributeValueConfigurationType::_VALUE_FIELD] ?? null;

        if (!$configAttributeValue instanceof AttributeValueInterface) return;

        $this->process($configAttributeValue, $resource);
    }

    /**
     * @param AttributeValueInterface   $configAttributeValue
     * @param AttributeSubjectInterface $resource
     *
     * @return \Closure
     */
    protected function process(AttributeValueInterface $configAttributeValue, AttributeSubjectInterface $resource): void {
        $attributeCode = $configAttributeValue->getAttribute()->getCode();
        $value         = $configAttributeValue->getValue();
        $locale        = $configAttributeValue->getLocaleCode();

        $attribute = $this->productAttributeRepository->findOneBy(['code' => $attributeCode]);

        if (!$attribute instanceof AttributeInterface) return;

        $isTranslatable = $attribute->isTranslatable() && $attribute->getStorageType() === AttributeValueInterface::STORAGE_TEXT;

        $attributeValue = $isTranslatable ? $resource->getAttributeByCodeAndLocale($attributeCode, $locale) :
            $resource->getAttributeByCodeAndLocale($attributeCode);

        if (!$attributeValue instanceof AttributeValueInterface) {
            /** @var AttributeValueInterface $attributeValue */
            $attributeValue = $this->attributeValueFactory->createNew();
            if ($isTranslatable) {
                $attributeValue->setLocaleCode($locale);
            }
            $attributeValue->setAttribute($attribute);
            $resource->addAttribute($attributeValue);
        }

        $attributeValue->setValue($value);
        $this->getEntityManager()->persist($attributeValue);
    }
}
