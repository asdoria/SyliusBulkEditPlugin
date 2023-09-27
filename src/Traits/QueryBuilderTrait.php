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
