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

namespace Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration;

use Asdoria\SyliusBulkEditPlugin\Action\Product\RemoveProductAssociationAction;
use Asdoria\SyliusBulkEditPlugin\Form\DataTransformer\CollectionResourceToIdentifierTransformer;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAssociationTypeChoiceType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAutocompleteChoiceType;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Product\Repository\ProductAssociationTypeRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class AssociationConfigurationType.
 */
class AssociationsConfigurationType extends AbstractType
{
    public const _ASSOCIATION_TYPE_FIELD = 'type';

    public const _PRODUCTS_FIELD = 'products';

    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected ProductAssociationTypeRepositoryInterface $productAssociationTypeRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $typeField = $builder->create(self::_ASSOCIATION_TYPE_FIELD, ProductAssociationTypeChoiceType::class, [
            'label' => 'sylius.form.product_association.type',
            'attr' => ['class' => 'ui search dropdown'],
            'constraints' => [new NotBlank(['groups' => ['sylius']])],
        ])->addModelTransformer(
            new ReversedTransformer(new ResourceToIdentifierTransformer($this->productAssociationTypeRepository, 'code')),
        );

        $productsField = $builder->create(
            self::_PRODUCTS_FIELD,
            ProductAutocompleteChoiceType::class,
            [
                'label' => 'sylius.ui.products',
                'multiple' => true,
                'required' => true,
                'choice_name' => 'name',
                'choice_value' => 'code',
                'resource' => 'sylius.product',
                'constraints' => [new Callback(['groups' => ['sylius'], 'callback' => $this->validatorCallback()])],
            ],
        )->addModelTransformer(
            new ReversedTransformer(new CollectionResourceToIdentifierTransformer($this->productRepository)),
        );

        $builder
            ->add($typeField)
            ->add($productsField);
    }

    protected function validatorCallback(): \Closure
    {
        return static function (string $data, ExecutionContextInterface $context) {
            if (!empty($data)) {
                return;
            }
            $type = $context->getObject()->getRoot()->get('type')->getData();
            if ($type === RemoveProductAssociationAction::REMOVE_PRODUCT_ASSOCIATION) {
                return;
            }
            $context
                ->buildViolation((new NotBlank())->message)
                ->addViolation();
        };
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix(): string
    {
        return 'asdoria_bulk_edit_configuration_product_association';
    }
}
