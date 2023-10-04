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

namespace Asdoria\SyliusBulkEditPlugin\EventListener;

use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotification;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareTrait;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Registry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class BulkEditActionListener.
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
class BulkEditActionListener
{
    use LoggerAwareTrait;
    public function __construct(
        protected MessageBusInterface    $bus,
        protected EntityManagerInterface $entityManager,
        protected Security               $securityHelper,
        protected Registry               $resourceMetadataRegistry,
    )
    {
    }

    /**
     * @param GenericEvent $event
     *
     * @return void
     */
    public function handleEvent(GenericEvent $event): void
    {
        try {
            $subject     = $event->getSubject();
            $resources   = $subject['resources'] ?? '';
            $resourceIds = explode(',', $resources);
            $context     = $subject['context'] ?? null;
            $config      = $subject['configuration'];
            $metadata    = $this->getResourceMetadata($context);
            $actionId    = uniqid();

            foreach ($resourceIds as $i => $resourceId) {
                $subject = [
                    'id'              => intval($resourceId),
                    'configuration'   => $config,
                    'type'            => $subject['type'] ?? null,
                    'type_identifier' => $context,
                    'entity_class'    => $metadata?->getClass('model'),
                    'action_id'       => $actionId,
                ];

                $message = new BulkEditNotification($subject);

                $this->bus->dispatch($message);
            }
        } catch (\Throwable $ex) {
            //TODO Logger
        }
    }

    /**
     * @param string|null $context
     *
     * @return MetadataInterface|null
     */
    protected function getResourceMetadata(?string $context): ?MetadataInterface
    {
        /** @var MetadataInterface $resourceMetadata */
        foreach ($this->resourceMetadataRegistry->getAll() as $resourceMetadata) {
            if ($resourceMetadata->getName() === $context) return $resourceMetadata;
        }

        return null;
    }
}
