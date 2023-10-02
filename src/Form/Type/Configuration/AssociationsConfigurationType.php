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

use Sylius\Bundle\ProductBundle\Form\Type\ProductAssociationTypeChoiceType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAutocompleteChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class AssociationConfigurationType.
 * @package Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class AssociationsConfigurationType extends AbstractType
{
    const _ASSOCIATION_TYPE_FIELD = 'type';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('type', ProductAssociationTypeChoiceType::class, [
            'label' => 'sylius.form.product_association.type',
        ])
        ->add('products', ProductAutocompleteChoiceType::class, [
            'label' => 'sylius.ui.products',
            'multiple' => true,
            'required' => false,
            'choice_name' => 'name',
            'choice_value' => 'code',
            'resource' => 'sylius.product',
            'constraints' => [
                    new NotBlank(['groups' => 'sylius']),
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'asdoria_bulk_edit_configuration_product_association';
    }
}
