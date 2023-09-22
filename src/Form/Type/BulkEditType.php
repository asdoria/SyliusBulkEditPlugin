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

namespace Asdoria\SyliusBulkEditPlugin\Form\Type;

use Asdoria\SyliusBulkEditPlugin\Form\Type\AbstractFormConfigurableElementType;
use Asdoria\SyliusBulkEditPlugin\Form\Type\BulkEditConfigurationChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class BulkEditType
 * @package Asdoria\SyliusBulkEditPlugin\Form\Type
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class BulkEditType extends AbstractFormConfigurableElementType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('type', BulkEditConfigurationChoiceType::class, [
                'label' => 'asdoria_bulk_edit.form.type.header',
                'placeholder' => 'asdoria.ui.please_selected_item',
                'attr' => [
                    'data-form-collection' => 'update',
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'asdoria_bulk_edit_form';
    }
}
