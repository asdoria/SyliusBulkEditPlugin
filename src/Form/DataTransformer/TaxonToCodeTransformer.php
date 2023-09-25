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
namespace Asdoria\SyliusBulkEditPlugin\Form\DataTransformer;

use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Webmozart\Assert\Assert;


/**
 * Class TaxonToCodeTransformer.
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
class TaxonToCodeTransformer implements DataTransformerInterface
{
    /**
     * @param TaxonRepositoryInterface $taxonRepository
     */
    public function __construct(
        protected TaxonRepositoryInterface $taxonRepository,
    )
    {
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function transform($value): ?TaxonInterface
    {
        Assert::nullOrString($value);

        if (empty($value)) {
            return null;
        }

        return $this->taxonRepository->findOneBy(['code' => $value]);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function reverseTransform($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        Assert::methodExists($value, 'getCode');

        return $value->getCode();
    }
}

