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

namespace Asdoria\SyliusBulkEditPlugin\Action;

use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\EnableConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Webmozart\Assert\Assert;

/**
 * Class EnableAction.
 */
abstract class EnableAction implements ResourceActionInterface
{
    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        Assert::isInstanceOf($resource, ToggleableInterface::class);

        $configuration = $message->getConfiguration();

        if (empty($configuration)) {
            return;
        }

        $enabled = $configuration[EnableConfigurationType::_ENABLED_FIELD] ?? null;

        $resource->setEnabled(filter_var($enabled, \FILTER_VALIDATE_BOOLEAN));
    }
}
