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


/**
 * Class EnabledShopUserAction.
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
final class EnabledShopUserAction extends EnabledAction
{
    const ENABLED_SHOP_USER = 'enabled_shop_user';

    /**
     * @param ResourceInterface             $resource
     * @param BulkEditNotificationInterface $message
     */
    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        if (!$resource instanceof CustomerInterface) return;

        $shopUser = $resource->getUser();

        if (!$shopUser instanceof ShopUserInterface) return;

        parent::handle($shopUser, $message);
    }
}
