<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin\Traits;

use Doctrine\ORM\QueryBuilder;

/**
 * Class QueryBuilderTrait.
 * @package Asdoria\SyliusBulkEditPlugin\Traits
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
trait QueryBuilderTrait
{
    /**
     * @var QueryBuilder|null
     */
    protected ?QueryBuilder $queryBuilder = null;

    /**
     * @return QueryBuilder|null
     */
    public function getQueryBuilder(): ?QueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * @param QueryBuilder|null $queryBuilder
     */
    public function setQueryBuilder(?QueryBuilder $queryBuilder): void
    {
        $this->queryBuilder = $queryBuilder;
    }
}
