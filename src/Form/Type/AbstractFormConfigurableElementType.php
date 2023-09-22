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

use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class AbstractFormConfigurableElementType
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
class AbstractFormConfigurableElementType extends AbstractType
{
    const _CONFIGURATION_FIELD = 'configuration';

    /**
     * {@inheritdoc}
     */
    public function __construct(
        protected FormTypeRegistryInterface $formTypeRegistry,
        protected ServiceRegistryInterface  $formConfigurationRegistry,
    )
    {
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $prototypes = [];
        foreach ($this->formConfigurationRegistry->all() as $type => $formConfiguration) {
            if (!$this->formTypeRegistry->has($type, $options['context'])) {
                continue;
            }

            $form = $builder->create(self::_CONFIGURATION_FIELD, $this->formTypeRegistry->get($type, $options['context']));

            $prototypes['types'][$type] = $form->getForm();
        }

        $builder->setAttribute('prototypes', $prototypes);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'configuration_type' => null,
                'context'            => 'product',
            ])
            ->setAllowedTypes('configuration_type', ['string', 'null'])
            ->setAllowedTypes('context', ['string']);
    }

    /**
     * @psalm-suppress MissingPropertyType
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['prototypes'] = [];
        foreach ($form->getConfig()->getAttribute('prototypes') as $group => $prototypes) {
            foreach ($prototypes as $type => $prototype) {
                $view->vars['prototypes'][$group . '_' . $type] = $prototype->createView($view);
            }
        }
    }
}
