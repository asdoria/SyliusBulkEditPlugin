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
 * Class TaxonConfigurationType.
 */
class TaxonConfigurationType extends AbstractType
{
    public const _TAXON_FIELD = 'taxon';

    public function __construct(
        protected DataTransformerInterface $taxonToCodeTransformer,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $taxonField = $builder
            ->create(self::_TAXON_FIELD, TaxonAutocompleteChoiceType::class, [
                'label' => 'asdoria_bulk_edit.ui.form.choice.taxonomy_configuration.taxon',
                'constraints' => [new NotBlank(['groups' => ['sylius']])],
            ])
            ->addModelTransformer($this->taxonToCodeTransformer);

        $builder->add($taxonField);
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix(): string
    {
        return 'asdoria_bulk_edit_configuration_taxon';
    }
}
