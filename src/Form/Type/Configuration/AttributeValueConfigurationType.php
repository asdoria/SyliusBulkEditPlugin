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

use Sylius\Bundle\LocaleBundle\Form\Type\LocaleChoiceType;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class AttributeValueConfigurationType.
 */
class AttributeValueConfigurationType extends AbstractType
{
    public const _ATTRIBUTE_FIELD = 'attribute';

    public const _LOCALE_CODE_FIELD = 'localeCode';

    public const _ATTRIBUTE_VALUE_FIELD = 'value';

    public function __construct(
        protected string $attributeChoiceType,
        protected RepositoryInterface $attributeRepository,
        protected RepositoryInterface $localeRepository,
        protected FormTypeRegistryInterface $formTypeRegistry,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(self::_ATTRIBUTE_FIELD, $this->attributeChoiceType, [
                'constraints' => [new NotBlank(['groups' => ['sylius']])],
                'required' => false,
                'placeholder' => 'asdoria_bulk_edit.form.attribute.select',
                'attr' => [
                    'data-form-collection' => 'update',
                    'class' => 'ui search dropdown',
                ],
            ])
            ->add(
                self::_LOCALE_CODE_FIELD,
                LocaleChoiceType::class,
                [
                    'constraints' => [new Callback(['groups' => ['sylius'], 'callback' => $this->localCodeValidatorCallback()])],
                    'label' => 'asdoria_bulk_edit.form.configuration.locale',
                ],
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
            });

        $builder->get(self::_LOCALE_CODE_FIELD)->addModelTransformer(
            new ReversedTransformer(new ResourceToIdentifierTransformer($this->localeRepository, 'code')),
        );
        $builder->get(self::_ATTRIBUTE_FIELD)->addModelTransformer(
            new ReversedTransformer(new ResourceToIdentifierTransformer($this->attributeRepository, 'code')),
        );
    }

    protected function localCodeValidatorCallback(): \Closure
    {
        return function (?string $data, ExecutionContextInterface $context) {
            $attributeCode = $context->getObject()->getParent()->get(self::_ATTRIBUTE_FIELD)->getData();
            if (empty($attributeCode)) {
                return;
            }

            $attribute = $this->attributeRepository->findOneByCode($attributeCode);
            if (!$attribute instanceof AttributeInterface || !$attribute->isTranslatable() || !empty($data)) {
                return;
            }
            $context
                ->buildViolation((new NotBlank())->message)
                ->addViolation();
        };
    }

    protected function addValueField(
        FormInterface $form,
        AttributeInterface $attribute,
        ?string $localeCode = null,
    ): void {
        $form->add(self::_ATTRIBUTE_VALUE_FIELD, $this->formTypeRegistry->get($attribute->getType(), 'default'), [
            'auto_initialize' => false,
            'configuration' => $attribute->getConfiguration(),
            'label' => $attribute->getName(),
            'locale_code' => $localeCode,
            'constraints' => [new NotBlank(['groups' => ['sylius']])],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix(): string
    {
        return 'asdoria_bulk_edit_configuration_attribute_value';
    }
}
