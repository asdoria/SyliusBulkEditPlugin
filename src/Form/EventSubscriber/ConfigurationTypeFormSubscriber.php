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

use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ConfigurationTypeFormSubscriber
 */
class ConfigurationTypeFormSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected FormTypeRegistryInterface $formTypeRegistry,
        protected TranslatorInterface $translator,
        protected array $options,
    ) {
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

    public function preSetData(FormEvent $event): void
    {
        $data = $event->getData();

        if (empty($data) || !array_key_exists('type', $data) || empty($data['type'])) {
            return;
        }

        $this->addConfigurationField($event->getForm(), $data['type']);
    }

    public function preSubmit(FormEvent $event): void
    {
        $data = $event->getData();

        if (empty($data) || !array_key_exists('type', $data) || empty($data['type'])) {
            return;
        }

        $this->addConfigurationField($event->getForm(), $data['type']);
    }

    private function addConfigurationField(FormInterface $form, string $typeName): void
    {
        $context = $this->options['context'] ?? 'default';
        if (!$this->formTypeRegistry->has($typeName, $context)) {
            throw new \InvalidArgumentException(sprintf('there is no form for type %s and this context %s', $typeName, $context));
        }

        $formConfiguration = $this->formTypeRegistry->get($typeName, $context);

        $form->add(
            'configuration',
            $formConfiguration,
            [
                'constraints' => [new Valid([], ['sylius'])],
            ],
        );

        $form->add('submit', SubmitType::class, [
            'label' => sprintf('<i class="icon pencil"></i>%s', $this->translator->trans('asdoria_bulk_edit.ui.save_action')),
            'label_html' => true,
            'priority' => -1,
            'attr' => ['class' => 'ui blue labeled icon button', 'data-bulk-edit-action-requires-confirmation' => '1'],
        ]);
    }
}
