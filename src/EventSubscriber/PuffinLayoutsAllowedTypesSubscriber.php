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

    $types = $event->getTypes();

    //Paragraph types can have [int]col or [int]vw at the end of the name.
    //Regions can also have [int]col or [int]vw at the end of the name.
    //If a region uses this naming convention, paragraph types that also use the
    //convention MUST MATCH suffixes, otherwise they are unset.
    foreach($types as $key => $type) {

      preg_match("/\d+col\b/", $event->getRegion(), $cols);
      if (count($cols) > 0) {
        if (strpos($key, 'col') && !strpos($key, $cols[0])) unset($types[$key]);
        if (strpos($key, 'vw')) unset($types[$key]);
      }
      preg_match("/\d+vw\b/", $event->getRegion(), $vw);
      if (count($vw) > 0) {
        if (strpos($key, 'vw') && !strpos($key, $vw[0])) unset($types[$key]);
        if (strpos($key, 'col')) unset($types[$key]);
      }
    }

    $event->setTypes($types);
  }

}
