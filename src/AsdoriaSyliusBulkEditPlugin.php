<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin;
/*
 * This file is part of the Asdoria Package.
 *
 * (c) Asdoria .
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Asdoria\SyliusBulkEditPlugin\DependencyInjection\Compiler\RegisterFormTypeResolversPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AsdoriaSyliusBulkEditPlugin
 * @package Asdoria\SyliusBulkEditPlugin
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
final class AsdoriaSyliusBulkEditPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterFormTypeResolversPass());
    }
}
