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
use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\AttributeConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * Class RemoveAttributeValueAction
 */
final class RemoveAttributeValueAction implements ResourceActionInterface
{
    public const REMOVE_ATTRIBUTE_VALUE = 'remove_attribute_value';

    public function __construct(private RepositoryInterface $productAttributeRepository)
    {
    }

    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        Assert::isInstanceOf($resource, ProductInterface::class);

        $configuration = $message->getConfiguration();

        if (empty($configuration)) {
            return;
        }

        $attributeCode = $configuration[AttributeConfigurationType::_ATTRIBUTE_FIELD] ?? null;

        if (empty($attributeCode)) {
            return;
        }

        $attribute = $this->productAttributeRepository->findOneByCode($attributeCode);

        Assert::isInstanceOf($attribute, AttributeInterface::class);

        $values = $resource->getAttributes()
            ->matching(Criteria::create()->where(Criteria::expr()->eq('attribute', $attribute)));

        Assert::isInstanceOf($values, Collection::class);

        if ($values->isEmpty()) {
            return;
        }

        /** @var AttributeValueInterface $value */
        foreach ($values as $value) {
            $resource->removeAttribute($value);
        }
    }
}
