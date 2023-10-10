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
use Asdoria\SyliusBulkEditPlugin\Registry\ResourceActionServiceRegistryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

/**
 * Class BulkEditMessageHandler.
 */
class BulkEditMessageHandler implements MessageHandlerInterface
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ResourceActionServiceRegistryInterface $actionRegistry,
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(BulkEditNotificationInterface $message): void
    {
        $action = $this->actionRegistry->get($message->getType());

        Assert::isInstanceOf($action, ResourceActionInterface::class);

        $entity = $this->entityManager->find($message->getEntityClass(), $message->getId());

        Assert::isInstanceOf($entity, ResourceInterface::class);

        $action->handle($entity, $message);

        $this->eventDispatcher->dispatch(new GenericEvent($message), 'bulk_edit.post_handle_message');
    }
}
