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

use Sylius\Bundle\ProductBundle\Form\Type\ProductAttributeChoiceType;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class AttributeConfigurationType
 */
class AttributeConfigurationType extends AbstractType
{
    public const _ATTRIBUTE_FIELD = 'attribute';

    public function __construct(protected RepositoryInterface $attributeRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $attributeField = $builder
            ->create(self::_ATTRIBUTE_FIELD, ProductAttributeChoiceType::class, [
                'constraints' => [new NotBlank(['groups' => ['sylius']])],
                'attr' => ['class' => 'ui search dropdown'],
            ])
            ->addModelTransformer(new ReversedTransformer(new ResourceToIdentifierTransformer($this->attributeRepository, 'code')));

        $builder->add($attributeField);
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix(): string
    {
        return 'asdoria_bulk_edit_configuration_attribute';
    }
}
