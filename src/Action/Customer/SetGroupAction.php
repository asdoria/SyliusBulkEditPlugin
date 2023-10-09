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

namespace Asdoria\SyliusBulkEditPlugin\Action\Customer;

use Asdoria\SyliusBulkEditPlugin\Action\ResourceActionInterface;
use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\AttributeValueConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\CustomerGroupConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Asdoria\SyliusBulkEditPlugin\Traits\EntityManagerTrait;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Attribute\Model\AttributeSubjectInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Customer\Model\CustomerGroupInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * Class SetGroupAction
 * @package Asdoria\SyliusBulkEditPlugin\Action\Customer
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
final class SetGroupAction implements ResourceActionInterface
{
    const SET_CUSTOMER_GROUP = 'set_customer_group';

    /**
     * @param RepositoryInterface $customerGroupRepository
     */
    public function __construct(
        protected RepositoryInterface $customerGroupRepository,
    )
    {
    }

    /**
     * @param ResourceInterface             $resource
     * @param BulkEditNotificationInterface $message
     *
     * @return void
     */
    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        if (!$resource instanceof CustomerInterface) return;

        $configuration = $message->getConfiguration();

        if (empty($configuration)) return;

        $customerGroupCode = $configuration[CustomerGroupConfigurationType::_CUSTOMER_GROUP_FIELD] ?? null;

        if (empty($customerGroupCode)) return;

        $customerGroup = $this->customerGroupRepository->findOneByCode($customerGroupCode);

        if (!$customerGroup instanceof CustomerGroupInterface) return;

        $resource->setGroup($customerGroup);
    }

}
