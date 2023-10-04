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

use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\AttributeConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\AttributeValueConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use sdoria\SyliusBulkEditPlugin\Form\Type\Configuration\TaxonConfigurationType;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * Class RemoveProductTaxonAction.
 * @package Asdoria\SyliusBulkEditPlugin\DependencyInjection
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class RemoveAttributeValueAction implements ResourceActionInterface
{
    const REMOVE_ATTRIBUTE_VALUE = 'remove_attribute_value';

    public function __construct(protected RepositoryInterface $productAttributeRepository)
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
        if (!$resource instanceof ProductInterface) return;

        $configuration = $message->getConfiguration();

        if (empty($configuration)) return;

        $attributeCode  = $configuration[AttributeConfigurationType::_ATTRIBUTE_FIELD] ?? null;

        if (empty($attributeCode)) return;

        $attribute = $this->productAttributeRepository->findOneByCode($attributeCode);

        if (!$attribute instanceof AttributeInterface) return;

        $values = $resource->getAttributes()
            ->matching(Criteria::create()->where(Criteria::expr()->eq('attribute', $attribute)));

        if (!$values instanceof Collection) return;
        if ($values->isEmpty()) return;

        /** @var AttributeValueInterface $value */
        foreach ($values as $value) {
            $resource->removeAttribute($value);
        }
    }

}
