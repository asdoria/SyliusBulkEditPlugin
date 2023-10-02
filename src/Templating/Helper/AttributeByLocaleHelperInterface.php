<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin\Templating\Helper;

use Sylius\Component\Attribute\Model\AttributeSubjectInterface;
use Sylius\Component\Core\Model\ChannelInterface;

/**
 * Class AttributeByLocaleHelperInterface.
 * @package Asdoria\SyliusBulkEditPlugin\Templating\Helper
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface AttributeByLocaleHelperInterface
{
    /**
     * @param AttributeSubjectInterface $subject
     * @param ChannelInterface          $channel
     *
     * @return array
     */
    public function getAttributesByLocale(AttributeSubjectInterface $subject, ChannelInterface $channel): array;
}
