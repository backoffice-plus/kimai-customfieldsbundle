<?php

namespace KimaiPlugin\CustomFieldsBundle\EventSubscriber;

use App\Entity\ActivityMeta;
use App\Entity\EntityWithMetaFields;
use App\Entity\MetaTableTypeInterface;
use App\Event\ActivityMetaDefinitionEvent;
use App\Event\ActivityMetaDisplayEvent;
use KevinPapst\TablerBundle\Helper\ContextHelper;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;

class MetaFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {

        return [
            ActivityMetaDefinitionEvent::class => ['loadActivityMeta', 200],
            ActivityMetaDisplayEvent::class => ['loadActivityField', 200],
        ];
    }

    public function loadActivityMeta(ActivityMetaDefinitionEvent $event): void
    {
        $this->prepareEntity($event->getEntity(), new ActivityMeta());
    }

    public function loadActivityField(ActivityMetaDisplayEvent $event)
    {
        $event->addField($this->prepareField(new ActivityMeta()));
    }

    private function prepareEntity(EntityWithMetaFields $entity, MetaTableTypeInterface $definition): void
    {
        $definition
            ->setLabel('Icon')
            ->setOptions(['help' => 'Enter a icon name from icones.js.org, Format: "mdi:play-circle"'])
            ->setName('icon')
            ->setType(TextType::class)
            ->addConstraint(new Length(['max' => 255]))
            ->setIsVisible(true);

        $entity->setMetaField($definition);
    }

    private function prepareField(MetaTableTypeInterface $definition): MetaTableTypeInterface
    {
        $definition
            ->setLabel('Icon')
            ->setName('icon')
            ->setType(TextType::class);

        return $definition;
    }
}
