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

use Sylius\Bundle\AttributeBundle\Form\Type\AttributeValueType;
use Sylius\Bundle\AttributeBundle\Validator\Constraints\ValidAttributeValue;
use Sylius\Bundle\CoreBundle\Validator\Constraints\LocalesAwareValidAttributeValueValidator;
use Sylius\Bundle\LocaleBundle\Form\Type\LocaleChoiceType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAttributeValueType;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
class AttributeValueConfigurationType extends AttributeValueType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('attribute', $this->attributeChoiceType, [
                'constraints' => [new NotBlank(['groups' => ['sylius']])],
                'attr'        => [
                    'data-form-collection' => 'update',
                ],
            ])
            ->add('localeCode',
                LocaleChoiceType::class,
                [
                    'constraints' => [new NotBlank(['groups' => ['sylius']])],
                    'label' => 'asdoria_bulk_edit.form.configuration.locale',
                ]
            )
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $attributeValue = $event->getData();

                if (!$attributeValue instanceof AttributeValueInterface) {
                    return;
                }

                $attribute = $attributeValue->getAttribute();
                if (null === $attribute) {
                    return;
                }

                $localeCode = $attributeValue->getLocaleCode();

                $this->addValueField($event->getForm(), $attribute, $localeCode);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $attributeValue = $event->getData();

                if (!isset($attributeValue['attribute'])) {
                    return;
                }

                $attribute = $this->attributeRepository->findOneBy(['code' => $attributeValue['attribute']]);
                if (!$attribute instanceof AttributeInterface) {
                    return;
                }

                $this->addValueField($event->getForm(), $attribute);
            })
        ;

        $builder->get('localeCode')->addModelTransformer(
            new ReversedTransformer(new ResourceToIdentifierTransformer($this->localeRepository, 'code')),
        );
    }

    protected function addValueField(
        FormInterface $form,
        AttributeInterface $attribute,
        ?string $localeCode = null,
    ): void {
        $form->add('value', $this->formTypeRegistry->get($attribute->getType(), 'default'), [
            'auto_initialize' => false,
            'configuration' => $attribute->getConfiguration(),
            'label' => $attribute->getName(),
            'locale_code' => $localeCode,
            'constraints' => [new NotBlank(['groups' => ['sylius']])]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'asdoria_bulk_edit_configuration_attribute_value';
    }
}
