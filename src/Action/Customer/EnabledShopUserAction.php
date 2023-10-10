<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin\Action\Customer;

/*
 * This file is part of the Asdoria Package.
 *
 * (c) Asdoria .
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Asdoria\SyliusBulkEditPlugin\Action\EnabledAction;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

/**
 * Class EnabledShopUserAction.
 */
final class EnabledShopUserAction extends EnabledAction
{
    public const ENABLED_SHOP_USER = 'enabled_shop_user';

    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        Assert::isInstanceOf($resource, CustomerInterface::class);

        $shopUser = $resource->getUser();

        Assert::isInstanceOf($shopUser, ShopUserInterface::class);

        parent::handle($shopUser, $message);
    }
}
