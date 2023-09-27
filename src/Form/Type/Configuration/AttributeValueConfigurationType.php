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

use Sylius\Bundle\ProductBundle\Form\Type\ProductAttributeValueType;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
/**
 * Class AttributeValueConfigurationType.
 * @package Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class AttributeValueConfigurationType extends AbstractType
{
    const _ATTRIBUTE_FIELD = 'attribute';
    const _LOCALE_FIELD = 'localeCode';
    const _VALUE_FIELD = 'value';

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('value',
            ProductAttributeValueType::class,
            [
                'label' => false,
                'required' => true,
                'constraints' => [new Assert\Callback([
                    'groups' => 'bulk_edit',
                    // Ici $value prend la valeur du champs que l'on est en train de valider,
                    // ainsi, pour un champs de type TextType, elle sera de type string.
                    'callback' => static function (AttributeValueInterface $value, ExecutionContextInterface $context) {
                        if (!$value) {
                            return;
                        }

                        if (!\preg_match('~^\p{Lu}~u', $value)) {
                            $context
                                ->buildViolation('La question doit commencer par une majuscule.')
                                ->atPath('[question]')
                                ->addViolation()
                            ;
                        }

                        if (\substr($value, \strlen($value) - 1, 1) !== '?') {
                            $context
                                ->buildViolation("La question doit finir par un point d'interrogation.")
                                ->atPath('[question]')
                                ->addViolation()
                            ;
                        }
                    },
                ]),]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'asdoria_bulk_edit_attribute_configuration';
    }
}
