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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class PropertyConfigurationType
 */
class PropertyConfigurationType extends AbstractType
{
    public const _PROPERTY_FIELD = 'property';

    public const _VALUE_FIELD = 'value';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('property', ChoiceType::class, [
                'choices' => [
                    'sylius.form.variant.width' => 'setWidth',
                    'sylius.form.variant.height' => 'setHeight',
                    'sylius.form.variant.depth' => 'setDepth',
                    'sylius.form.variant.weight' => 'setWeight',
                ],
                'label' => 'asdoria_bulk_edit.ui.form.product_variant.property',
            ])
            ->add('value', NumberType::class, [
                'required' => false,
                'label' => 'sylius.ui.value',
                'constraints' => [new NotBlank(['groups' => ['sylius']])],
            ]);
    }
}
