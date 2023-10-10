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
 */
class CustomerGroupConfigurationType extends AbstractType
{
    public const _CUSTOMER_GROUP_FIELD = 'customerGroup';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(self::_CUSTOMER_GROUP_FIELD, CustomerGroupCodeChoiceType::class, [
                'constraints' => [new NotBlank(['groups' => ['sylius']])],
                'attr' => ['class' => 'ui search dropdown'],
            ]);
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix(): string
    {
        return 'asdoria_bulk_edit_configuration_customer_group';
    }
}
