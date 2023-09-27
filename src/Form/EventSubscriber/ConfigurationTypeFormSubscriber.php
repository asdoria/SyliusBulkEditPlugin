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

namespace Asdoria\SyliusBulkEditPlugin\Form\EventSubscriber;

use Asdoria\SyliusBulkEditPlugin\Action\ResourceActionInterface;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ConfigurationTypeFormSubscriber
 * @package Asdoria\SyliusBulkEditPlugin\Form\EventSubscriber
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ConfigurationTypeFormSubscriber implements EventSubscriberInterface
{

    /**
     * @param FormTypeRegistryInterface $formTypeRegistry
     * @param ServiceRegistryInterface  $formConfigurationRegistry
     * @param string                    $context
     */
    public function __construct(
        protected FormTypeRegistryInterface $formTypeRegistry,
        protected ServiceRegistryInterface  $formConfigurationRegistry,
        protected TranslatorInterface $translator,
        protected ?string $context = null
    )
    {
    }

    /**
     * {@inheritdoc}
     * sylius.form_builder.default
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    /**
     * @param FormEvent $event
     *
     * @return void
     */
    public function preSetData(FormEvent $event): void {
        $data = $event->getData();

        if (empty($data) || !array_key_exists('type', $data) || empty($data['type'])) {
            return;
        }

        $this->addConfigurationField($event->getForm(), $data['type']);
    }


    /**
     * @param FormEvent $event
     *
     * @return void
     */
    public function preSubmit(FormEvent $event): void {
        $data = $event->getData();

        if (empty($data) || !array_key_exists('type', $data) || empty($data['type'])) {
            return;
        }

        $this->addConfigurationField($event->getForm(), $data['type']);
    }

    /**
     * @param FormInterface $form
     * @param string        $typeName
     *
     * @return void
     */
    private function addConfigurationField(FormInterface $form, string $typeName): void
    {
        if (!$this->formTypeRegistry->has($typeName, $this->context ?? 'default')) {
            throw new \InvalidArgumentException(sprintf('there is no form for type %s and this context %s',$typeName, $this->context ?? 'default'));
        }

        $formConfiguration = $this->formTypeRegistry->get($typeName, $this->context ?? 'default');

        $form->add(
            'configuration',
            $formConfiguration,
            ['constraints' => [new Valid()]]
        );

        $label = sprintf('<i class="icon pencil"></i>%s', $this->translator->trans('asdoria_bulk_edit.ui.save_action'));
        $form->add('submit', SubmitType::class, [
            'label' => $label,
            'label_html' => true,
            'attr' => ['class' => 'ui blue labeled icon button', 'data-bulk-edit-action-requires-confirmation' => '1']
        ]);
    }
}
