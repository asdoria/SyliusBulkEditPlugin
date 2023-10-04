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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Webmozart\Assert\Assert;

/**
 * Class CollectionResourceToIdentifierTransformer
 * @package Asdoria\SyliusBulkEditPlugin\Form\DataTransformer
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
final class CollectionResourceToIdentifierTransformer implements DataTransformerInterface
{

    public function __construct(
        protected RepositoryInterface $repository,
        protected string              $identifier = 'id',
        protected string              $delimiter = ','
    )
    {
    }

    /**
     * @psalm-suppress MissingParamType
     *
     * @param object|null $value
     */
    public function transform($value)
    {
        if (!($value instanceof Collection)) {
            throw new TransformationFailedException(
                sprintf(
                    'Expected "%s", but got "%s"',
                    Collection::class,
                    is_object($value) ? get_class($value) : gettype($value),
                ),
            );
        }

        if ($value->isEmpty()) {
            return '';
        }

        $result = [];
        foreach ($value as $resource) {
            /** @psalm-suppress ArgumentTypeCoercion */
            Assert::isInstanceOf($resource, $this->repository->getClassName());
            $result[] = PropertyAccess::createPropertyAccessor()->getValue($resource, $this->identifier);
        }

        return implode($this->delimiter, $result);
    }

    /** @param string|null $value */
    public function reverseTransform($value): Collection
    {
        if(empty($value)) return new ArrayCollection();
        
        if (!is_string($value)) {
            throw new TransformationFailedException(
                sprintf(
                    'Expected string, but got "%s"',
                    is_object($value) ? get_class($value) : gettype($value),
                ),
            );
        }

        if ('' === $value) {
            return new ArrayCollection();
        }

        /** @var ResourceInterface|null $resource */
        $resource = $this->repository->findOneBy([$this->identifier => $value]);
        if (null === $resource) {
            throw new TransformationFailedException(sprintf(
                'Object "%s" with identifier "%s"="%s" does not exist.',
                $this->repository->getClassName(),
                $this->identifier,
                $value,
            ));
        }

        $result = new ArrayCollection();
        $values = explode($this->delimiter, $value);

        foreach ($values as $identifier) {
            /** @var ResourceInterface|null $resource */
            $resource = $this->repository->findOneBy([$this->identifier => $identifier]);
            if (null === $resource) {
                throw new TransformationFailedException(sprintf(
                    'Object "%s" with identifier "%s"="%s" does not exist.',
                    $this->repository->getClassName(),
                    $this->identifier,
                    $value,
                ));
            }
            $result->add($resource);
        }

        return $result;
    }
}
