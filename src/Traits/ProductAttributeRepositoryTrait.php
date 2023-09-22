<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin\Traits;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * Class ProductAttributeRepositoryTrait.
 * @package Asdoria\SyliusBulkEditPlugin\Traits
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
trait ProductAttributeRepositoryTrait
{
    protected EntityRepository $productAttributeRepository;

    /**
     * @return EntityRepository
     */
    public function getProductAttributeRepository(): EntityRepository
    {
        return $this->productAttributeRepository;
    }

    /**
     * @param EntityRepository $productAttributeRepository
     */
    public function setProductAttributeRepository(EntityRepository $productAttributeRepository): void
    {
        $this->productAttributeRepository = $productAttributeRepository;
    }
}
