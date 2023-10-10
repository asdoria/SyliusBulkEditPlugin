<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin\Templating\Helper;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Product\Model\ProductVariantInterface;

/**
 * Class ProductOptionByVariantHelperInterface.
 */
interface ProductOptionByVariantHelperInterface
{
    public function getOptionsByVariant(ProductVariantInterface $subject): Collection;
}
