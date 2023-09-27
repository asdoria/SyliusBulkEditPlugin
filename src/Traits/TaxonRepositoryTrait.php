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

namespace Asdoria\SyliusBulkEditPlugin\Traits;

use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

/**
 * Class TaxonRepositoryTrait.
 * @package Asdoria\SyliusBulkEditPlugin\Traits
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
trait TaxonRepositoryTrait
{
    protected ?TaxonRepositoryInterface $taxonRepository = null;

    /**
     * @return TaxonRepositoryInterface|null
     */
    public function getTaxonRepository(): ?TaxonRepositoryInterface
    {
        return $this->taxonRepository;
    }

    /**
     * @param TaxonRepositoryInterface|null $taxonRepository
     */
    public function setTaxonRepository(?TaxonRepositoryInterface $taxonRepository): void
    {
        $this->taxonRepository = $taxonRepository;
    }
}
