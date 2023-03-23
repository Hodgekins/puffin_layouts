<?php

namespace Drupal\puffin_layouts\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\layout_paragraphs\Event\LayoutParagraphsAllowedTypesEvent;

/**
 * Puffin Layouts event subscriber.
 */
class PuffinLayoutsAllowedTypesSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritDoc}
   */
  public static function getSubscribedEvents()
  {
    return [
      LayoutParagraphsAllowedTypesEvent::EVENT_NAME => 'typeRestrictions',
    ];
  }

  /**
   * Restricts available types based on settings in layout.
   *
   * @param \Drupal\layout_paragraphs\Event\LayoutParagraphsAllowedTypesEvent $event
   *   The allowed types event.
   */
  public function typeRestrictions(LayoutParagraphsAllowedTypesEvent $event)
  {

    if (!$event->getRegion()) return;

    //Some paragraph types
    $types = $event->getTypes();

    preg_match("/\dcol\b/", $event->getRegion(), $matches);
    if (count($matches) == 0) return;
    $column_count = $matches[0];

    foreach($types as $key => $type) {
      if (strpos($key, 'col') && !strpos($key, $column_count)) unset($types[$key]);
    }
    $event->setTypes($types);
  }

}
