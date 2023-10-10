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

namespace Asdoria\SyliusBulkEditPlugin\Message;

/**
 * Interface BulkEditNotificationInterface
 */
interface BulkEditNotificationInterface
{
    public function getConfiguration(): array;

    public function getType(): ?string;

    public function getEntityClass(): ?string;

    public function getTypeIdentifier(): ?string;

    public function getId(): ?int;

    public function getActionId(): ?string;

    public function getCurrent(): ?int;

    public function getTotalCount(): ?int;
}
