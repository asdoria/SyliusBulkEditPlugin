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
namespace Asdoria\SyliusBulkEditPlugin\Grid\Filter;

use Asdoria\SyliusBulkEditPlugin\Doctrine\ORM\AttributeValueStringDriver;
use Asdoria\SyliusBulkEditPlugin\Traits\LocaleContextTrait;
use Asdoria\SyliusBulkEditPlugin\Traits\ProductAttributeRepositoryTrait;
use Asdoria\SyliusBulkEditPlugin\Traits\QueryBuilderTrait;
use Asdoria\SyliusBulkEditPlugin\Traits\RequestStackTrait;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;
use Sylius\Component\Product\Model\ProductAttributeInterface;

/**
 * Class AttributeValueStringFilter.
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
class AttributeValueStringFilter implements FilterInterface
{
    use RequestStackTrait;
    use ProductAttributeRepositoryTrait;
    use LocaleContextTrait;

    public const NAME = 'string';

    public const TYPE_EQUAL = 'equal';

    public const TYPE_NOT_EQUAL = 'not_equal';

    public const TYPE_EMPTY = 'empty';

    public const TYPE_NOT_EMPTY = 'not_empty';

    public const TYPE_CONTAINS = 'contains';

    public const TYPE_NOT_CONTAINS = 'not_contains';

    public const TYPE_STARTS_WITH = 'starts_with';

    public const TYPE_ENDS_WITH = 'ends_with';

    public const TYPE_IN = 'in';

    public const TYPE_NOT_IN = 'not_in';

    public function apply(DataSourceInterface $dataSource, string $name, $data, array $options): void
    {
        $expressionBuilder = $dataSource->getExpressionBuilder();
        $queryBuilder      = $this->requestStack
            ->getCurrentRequest()->attributes->get(AttributeValueStringDriver::QUERY_BUILDER_ATTR);

        $value         = is_array($data) ? $data['value'] ?? null : $data;
        $type          = $data['type'] ?? ($options['type'] ?? self::TYPE_CONTAINS);
        $attributeCode = $data['attribute'] ?? null;
        $localeCode = $data['localeCode'] ?? $this->getLocaleContext()->getLocaleCode();


        if (!in_array($type, [self::TYPE_NOT_EMPTY, self::TYPE_EMPTY], true) && empty($value)) {
            return;
        }

        $attribute = $this->getProductAttributeRepository()->findOneByCode($attributeCode);

        if (!$attribute instanceof ProductAttributeInterface)  return;

        $alias      = sprintf('%s_%s_%s', 'code', $attribute->getCode(), $attribute->getId());
        $attrParam  = 'attr_' . uniqid($attribute->getCode());
        $condition  = $alias . '.attribute = :' . $attrParam. ' AND '.$alias . '.localeCode = :localeCode';
        $fieldType  = $attribute->getStorageType();
        $queryBuilder
            ->innerJoin('o.attributes', $alias, 'WITH', $condition)
            ->setParameter($attrParam, $attribute)
            ->setParameter('localeCode', $localeCode);

        if (!is_array($value)) {
            $dataSource->restrict($this->getExpression($expressionBuilder, $type, $alias . '.' . $fieldType, $value));
            return;
        }
        
        $ands = [];
        foreach ($value as $v) $ands[] = $expressionBuilder->like($alias . '.' . $fieldType, '%' . $v. '%');
        $dataSource->restrict($expressionBuilder->andX(...$ands));
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    private function getExpression(
        ExpressionBuilderInterface $expressionBuilder,
        string $type,
        string $field,
        $value
    ) {
        switch ($type) {
            case self::TYPE_EQUAL:
                return $expressionBuilder->equals($field, $value);
            case self::TYPE_NOT_EQUAL:
                return $expressionBuilder->notEquals($field, $value);
            case self::TYPE_EMPTY:
                return $expressionBuilder->isNull($field);
            case self::TYPE_NOT_EMPTY:
                return $expressionBuilder->isNotNull($field);
            case self::TYPE_CONTAINS:
                return $expressionBuilder->like($field, '%' . $value . '%');
            case self::TYPE_NOT_CONTAINS:
                return $expressionBuilder->notLike($field, '%' . $value . '%');
            case self::TYPE_STARTS_WITH:
                return $expressionBuilder->like($field, $value . '%');
            case self::TYPE_ENDS_WITH:
                return $expressionBuilder->like($field, '%' . $value);
            case self::TYPE_IN:
                return $expressionBuilder->in($field, array_map('trim', explode(',', $value)));
            case self::TYPE_NOT_IN:
                return $expressionBuilder->notIn($field, array_map('trim', explode(',', $value)));
            default:
                throw new \InvalidArgumentException(sprintf('Could not get an expression for type "%s"!', $type));
        }
    }
}
