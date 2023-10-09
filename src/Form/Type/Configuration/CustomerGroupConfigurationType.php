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

use Sylius\Bundle\CustomerBundle\Form\Type\CustomerGroupCodeChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class CustomerGroupConfigurationType.
 * @package Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class CustomerGroupConfigurationType extends AbstractType
{
    const _CUSTOMER_GROUP_FIELD = 'customerGroup';

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(self::_CUSTOMER_GROUP_FIELD, CustomerGroupCodeChoiceType::class, [
                'constraints' => [new NotBlank(['groups' => ['sylius']])],
                'attr'        => ['class' => 'ui search dropdown'],
            ]);
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'asdoria_bulk_edit_configuration_customer_group';
    }
}
