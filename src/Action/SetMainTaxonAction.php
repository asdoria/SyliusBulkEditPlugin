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

use Asdoria\Bundle\BulkEditBundle\Form\Type\Configuration\TaxonConfigurationType;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * Class SetMainTaxonAction.
 * @package Asdoria\SyliusBulkEditPlugin\Action
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class SetMainTaxonAction implements ResourceActionInterface
{
    use EntityManagerTrait;

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

        $taxonCode = $configuration[TaxonConfigurationType::_TAXON_FIELD] ?? null;
        $taxon      = $this
            ->getEntityManager()
            ->getRepository(TaxonInterface::class)
            ->findOneByCode($taxonCode);

        $this->getEntityManager()
            ->wrapInTransaction($this->callbackTransactional($taxon, $resource));

    }
    /**
     * @param TaxonInterface   $taxon
     * @param ProductInterface $product
     *
     * @return \Closure
     */
    protected function callbackTransactional(TaxonInterface $taxon, ProductInterface $product): \Closure
    {
        return function (EntityManagerInterface $entityManager) use ($taxon, $product): void {
            $product->setMainTaxon($taxon);
        };
    }
}
