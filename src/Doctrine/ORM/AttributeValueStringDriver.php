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

namespace Asdoria\SyliusBulkEditPlugin\Doctrine\ORM;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\DriverInterface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Exception\UndefinedGridException;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\Provider\GridProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class AttributeValueStringDriver.
 */
class AttributeValueStringDriver implements DriverInterface
{
    public const QUERY_BUILDER_ATTR = 'asdoria_bulk_edit_search_attribute_value[queryBuilder]';

    public function __construct(
        protected DriverInterface $inner,
        protected GridProviderInterface $gridProvider,
        protected RequestStack $requestStack,
    ) {
    }

    /**
     * @throws \ReflectionException
     */
    public function getDataSource(array $configuration, Parameters $parameters): DataSourceInterface
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (null === $currentRequest) {
            return $this->inner->getDataSource($configuration, $parameters);
        }

        $grid = $currentRequest->attributes->get('_sylius', [])['grid'] ?? null;

        if (empty($grid)) {
            return $this->inner->getDataSource($configuration, $parameters);
        }

        try {
            $gridDefinition = $this->gridProvider->get($grid);
        } catch (UndefinedGridException $e) {
            return $this->inner->getDataSource($configuration, $parameters);
        }

        if (!$gridDefinition instanceof Grid || !$gridDefinition->hasFilter('asdoria_bulk_edit_search_attribute_value')) {
            return $this->inner->getDataSource($configuration, $parameters);
        }

        $dataSource = $this->inner->getDataSource($configuration, $parameters);

        $reflectionProperty = new \ReflectionProperty(get_class($dataSource), 'queryBuilder');
        $reflectionProperty->setAccessible(true);
        $queryBuilder = $reflectionProperty->getValue($dataSource);
        $currentRequest->attributes->set(self::QUERY_BUILDER_ATTR, $queryBuilder);

        return $dataSource;
    }
}
