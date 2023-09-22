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
namespace Asdoria\SyliusBulkEditPlugin\MessageHandler;

use Asdoria\SyliusBulkEditPlugin\Action\ResourceActionInterface;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


/**
 * Class BulkEditMessageHandler.
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
class BulkEditMessageHandler implements MessageHandlerInterface
{
    /**
     * @param EntityManagerInterface   $entityManager
     * @param ServiceRegistryInterface $actionRegistry
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        protected EntityManagerInterface   $entityManager,
        protected ServiceRegistryInterface $actionRegistry,
        protected EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    /**
     * @param BulkEditNotificationInterface $message
     *
     * @return void
     */
    public function __invoke(BulkEditNotificationInterface $message): void
    {
        $action = $this->actionRegistry->get($message->getType());
        if (!$action instanceof ResourceActionInterface) {
            return;
        }
        $entity = $this->entityManager->find($message->getEntityClass(), $message->getId());
        if (!$entity instanceof ResourceInterface) {
            return;
        }

        $this->entityManager->transactional(function (EntityManagerInterface $manager) use ($entity, $message, $action) {
            $action->handle($entity, $message);
            $this->eventDispatcher->dispatch(new GenericEvent($message), 'bulk_edit.post_handle_message');
        });
    }
}
