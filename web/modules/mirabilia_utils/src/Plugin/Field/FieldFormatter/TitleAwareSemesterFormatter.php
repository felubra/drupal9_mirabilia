<?php

namespace Drupal\mirabilia_utils\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldDefinitionInterface;
use Symfony\Component\String\UnicodeString;


/**
 * Returns empty string for the semester field if it equals to the title of its node.
 *
 * @FieldFormatter(
 *   id = "mirabilia_utils_title_aware_semester",
 *   label = @Translation("Title-aware semester"),
 *   field_types = {
 *     "string"
 *   }
 * )
 */
class TitleAwareSemesterFormatter extends FormatterBase
{
  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode)
  {
    $element = [];
    $entity = $items->getParent()->getEntity();
    $title = (new UnicodeString($entity->getTitle()))->trim();

    foreach ($items as $delta => $item) {
      try {
        $value = $title->equalsTo($item->value)
          ? ""
          : $item->value;
      } catch (\Exception $e) {
        $value = $item->value;
      }

      $element[$delta] = [
        '#markup' => $value,
      ];
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition)
  {
    // Only applies for the `field_semester` field
    return $field_definition->getName() === 'field_semester';
  }
}
