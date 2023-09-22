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
use Psr\Log\LoggerInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Registry;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\SecurityBundle\Security;

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
            $metadata    = $this->getResourceMetadata($subject['context']);
            $actionId    = uniqid();

            $config = array_merge(...(array_filter($subject['steps'] ?? [], 'is_array')));

            foreach ($resourceIds as $i => $resourceId) {
                $subject = [
                    'id'              => intval($resourceId),
                    'configuration'   => $config,
                    'type'            => $config['type'],
                    'type_identifier' => $subject['context'] ?? null,
                    'entity_class'    => $metadata?->getClass('model'),
                    'action_id'       => $actionId,
                    'current'         => $i + 1,
                    'total_count'     => sizeof($resourceIds),
                ];

                $message = new BulkEditNotification($subject);

                $this->bus->dispatch($message);
            }
        } catch (\Throwable $ex) {

//            $this->logger->
//            dump($ex->getMessage());exit;
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
