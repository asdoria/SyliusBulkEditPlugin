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
use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\CustomerGroupConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Customer\Model\CustomerGroupInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * Class SetGroupAction
 */
final class SetGroupAction implements ResourceActionInterface
{
    public const SET_CUSTOMER_GROUP = 'set_customer_group';

    public function __construct(
        private RepositoryInterface $customerGroupRepository,
    ) {
    }

    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        Assert::isInstanceOf($resource, CustomerInterface::class);

        $configuration = $message->getConfiguration();

        if (empty($configuration)) {
            return;
        }

        $customerGroupCode = $configuration[CustomerGroupConfigurationType::_CUSTOMER_GROUP_FIELD] ?? null;

        if (empty($customerGroupCode)) {
            return;
        }

        $customerGroup = $this->customerGroupRepository->findOneByCode($customerGroupCode);

        Assert::isInstanceOf($customerGroup, CustomerGroupInterface::class);

        $resource->setGroup($customerGroup);
    }
}
