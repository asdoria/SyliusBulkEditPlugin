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

namespace Asdoria\SyliusBulkEditPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RegisterFormTypeResolversPass
 */
class RegisterFormTypeResolversPass implements CompilerPassInterface
{
    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('asdoria_bulk_edit.configuration_form_type_registry')) {
            return;
        }

        $configurationFormTypeRegistry = $container->getDefinition('asdoria_bulk_edit.configuration_form_type_registry');

        $formConfigurationResolverTypeToLabelMap = [];
        foreach ($container->findTaggedServiceIds('asdoria_bulk_edit.action') as $id => $attributes) {
            if (!isset($attributes[0]['type'], $attributes[0]['label'], $attributes[0]['form_type'], $attributes[0]['type_identifier'])) {
                throw new \InvalidArgumentException('Tagged form configuration resolver `' . $id . '` needs to have `type`, `form_type`, `type_identifier`, `label` and `label_group` attributes.');
            }

            $labelGroup = $attributes[0]['label_group'] ?? 'default';
            $typeIdentifier = $attributes[0]['type_identifier'] ?? 'default';
            $formConfigurationResolverTypeToLabelMap[$typeIdentifier][$labelGroup][$attributes[0]['label']] = $attributes[0]['type'];

            $configurationFormTypeRegistry->addMethodCall('add', [$attributes[0]['type'], $typeIdentifier, $attributes[0]['form_type']]);
        }

        $container->setParameter('asdoria_bulk_edit.form_configurations', $formConfigurationResolverTypeToLabelMap);
    }
}
