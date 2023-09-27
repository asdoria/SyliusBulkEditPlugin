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
 * @package Asdoria\SyliusBulkEditPlugin\Doctrine\ORM
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class AttributeValueStringDriver implements DriverInterface
{
    /**
     * @param DriverInterface       $inner
     * @param GridProviderInterface $gridProvider
     * @param array                 $services
     */
    public function __construct(
        protected DriverInterface       $inner,
        protected GridProviderInterface $gridProvider,
        protected RequestStack          $requestStack,
        protected array                 $services
    )
    {
    }

    /**
     * @param array      $configuration
     * @param Parameters $parameters
     *
     * @return DataSourceInterface
     * @throws \ReflectionException
     */
    public function getDataSource(array $configuration, Parameters $parameters): DataSourceInterface
    {
        if (empty($this->requestStack->getMainRequest())) {
            return $this->inner->getDataSource($configuration, $parameters);
        }

        $grid = $this->requestStack->getMainRequest()->attributes->get('_sylius', [])['grid'] ?? null;

        if (empty($grid)) return $this->inner->getDataSource($configuration, $parameters);

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

        foreach ($this->services as $service) {
            if (method_exists($service, 'setQueryBuilder')) $service->setQueryBuilder($queryBuilder);
        }

        return $dataSource;
    }
}
