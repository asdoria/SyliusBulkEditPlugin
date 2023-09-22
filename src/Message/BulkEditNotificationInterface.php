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
 * @package Asdoria\SyliusBulkEditPlugin\Message
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface BulkEditNotificationInterface
{
    /**
     * @return array
     */
    public function getConfiguration(): array;

    /**
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * @return string|null
     */
    public function getEntityClass(): ?string;

    /**
     * @return string|null
     */
    public function getTypeIdentifier(): ?string;

    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string|null
     */
    public function getActionId(): ?string;

    /**
     * @return int|null
     */
    public function getCurrent(): ?int;

    /**
     * @return int|null
     */
    public function getTotalCount(): ?int;
}
