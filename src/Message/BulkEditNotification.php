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
 * Class BulkEditNotification
 * @package Asdoria\SyliusBulkEditPlugin\Message
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class BulkEditNotification implements BulkEditNotificationInterface
{
    protected array $configuration = [];
    protected ?string $type = null;
    protected ?string $entityClass = null;
    protected ?string $typeIdentifier = null;
    protected ?int $id = null;
    protected ?string $actionId = null;
    protected ?int $current = null;
    protected ?int $totalCount = null;

    /**
     * @param array $subject
     */
    public function __construct(array $subject)
    {
        $this->configuration  = $subject['configuration'] ?? [];
        $this->type           = $subject['type'] ?? null;
        $this->entityClass    = $subject['entity_class'] ?? null;
        $this->typeIdentifier = $subject['type_identifier'] ?? null;
        $this->id             = $subject['id'] ?? null;
        $this->actionId       = $subject['action_id'] ?? null;
        $this->current        = $subject['current'] ?? null;
        $this->totalCount     = $subject['total_count'] ?? null;
    }

    /**
     * @param string|null $key
     *
     * @return array
     */
    public function getConfiguration(?string $key = null): array
    {
        if (!empty($key)) return $this->configuration[$key] ?? [];

        return $this->configuration;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getEntityClass(): ?string
    {
        return $this->entityClass;
    }

    /**
     * @return string|null
     */
    public function getTypeIdentifier(): ?string
    {
        return $this->typeIdentifier;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getActionId(): ?string
    {
        return $this->actionId;
    }

    /**
     * @return int|null
     */
    public function getCurrent(): ?int
    {
        return $this->current;
    }

    /**
     * @return int|null
     */
    public function getTotalCount(): ?int
    {
        return $this->totalCount;
    }
}
