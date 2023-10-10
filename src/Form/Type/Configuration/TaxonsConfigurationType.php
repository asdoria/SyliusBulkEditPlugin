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

use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonAutocompleteChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class TaxonsConfigurationType.
 */
class TaxonsConfigurationType extends AbstractType
{
    public const _TAXONS_FIELD = 'taxons';

    public function __construct(
        protected DataTransformerInterface $taxonsToCodesTransformer,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $taxonsField = $builder
            ->create(self::_TAXONS_FIELD, TaxonAutocompleteChoiceType::class, [
                'label' => 'asdoria_bulk_edit.ui.form.choice.taxonomy_configuration.taxons',
                'multiple' => true,
                'constraints' => [new NotBlank(['groups' => ['sylius']])],
            ])
            ->addModelTransformer($this->taxonsToCodesTransformer);

        $builder->add($taxonsField);
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix(): string
    {
        return 'asdoria_bulk_edit_configuration_taxons';
    }
}
