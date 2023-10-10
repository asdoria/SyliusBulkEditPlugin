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

use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\Bundle\TaxationBundle\Form\Type\TaxCategoryChoiceType;
use Sylius\Component\Taxation\Repository\TaxCategoryRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class TaxCategoryConfigurationType
 */
class TaxCategoryConfigurationType extends AbstractType
{
    public const _TAX_CATEGORY_FIELD = 'tax_category';

    public function __construct(protected TaxCategoryRepositoryInterface $taxCategoryRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $taxCategoryField = $builder
            ->create(self::_TAX_CATEGORY_FIELD, TaxCategoryChoiceType::class, [
                'constraints' => [new NotBlank(['groups' => ['sylius']])],
                'attr' => ['class' => 'ui search dropdown'],
            ])
            ->addModelTransformer(new ReversedTransformer(new ResourceToIdentifierTransformer($this->taxCategoryRepository, 'code')));

        $builder->add($taxCategoryField);
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix(): string
    {
        return 'asdoria_bulk_edit_configuration_tax_category';
    }
}
