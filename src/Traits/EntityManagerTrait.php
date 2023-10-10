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

use Doctrine\ORM\EntityManagerInterface;

trait EntityManagerTrait
{
    protected ?EntityManagerInterface $entityManager = null;

    public function getEntityManager(): ?EntityManagerInterface
    {
        return $this->entityManager;
    }

    public function setEntityManager(?EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }
}
