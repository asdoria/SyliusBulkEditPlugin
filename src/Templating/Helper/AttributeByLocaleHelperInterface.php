<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin\Templating\Helper;

use Sylius\Component\Attribute\Model\AttributeSubjectInterface;
use Sylius\Component\Core\Model\ChannelInterface;

/**
 * Class AttributeByLocaleHelperInterface.
 */
interface AttributeByLocaleHelperInterface
{
    public function getAttributesByLocale(AttributeSubjectInterface $subject, ChannelInterface $channel): array;
}
