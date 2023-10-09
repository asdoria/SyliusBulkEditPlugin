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
namespace Asdoria\SyliusBulkEditPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;


/**
 * Class AdminMenuListener.
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
class AdminMenuListener
{
    /**
     * @param MenuBuilderEvent $event
     *
     * @return void
     */
    public function addMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $configuration = $menu->addChild('asdoria_bulk_edit')
            ->setLabel('asdoria_bulk_edit.menu.admin.asdoria_bulk_edit.header');

        $configuration->addChild('asdoria_bulk_edit_product' , ['route' => 'asdoria_bulk_edit_admin_product_index'])
            ->setLabel('asdoria_bulk_edit.menu.admin.asdoria_bulk_edit.products')
            ->setLabelAttribute('icon', 'list alternate outline');

        $configuration->addChild('asdoria_bulk_edit_product_variant' , ['route' => 'asdoria_bulk_edit_admin_product_variant_index'])
            ->setLabel('asdoria_bulk_edit.menu.admin.asdoria_bulk_edit.product_variants')
            ->setLabelAttribute('icon', 'list alternate');

        $configuration->addChild('asdoria_bulk_edit_taxon' , ['route' => 'asdoria_bulk_edit_admin_taxon_index'])
            ->setLabel('asdoria_bulk_edit.menu.admin.asdoria_bulk_edit.taxons')
            ->setLabelAttribute('icon', 'outdent');

        $configuration->addChild('asdoria_bulk_edit_customer' , ['route' => 'asdoria_bulk_edit_admin_customer_index'])
            ->setLabel('asdoria_bulk_edit.menu.admin.asdoria_bulk_edit.customers')
            ->setLabelAttribute('icon', 'users');
    }
}
