<?php

namespace Drupal\mirabilia_utils\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'Utils' formatter.
 *
 * @FieldFormatter(
 *   id = "mirabilia_utils_deadline_is_call_for_pappers",
 *   label = @Translation("Is call for pappers formatter"),
 *   field_types = {
 *     "datetime"
 *   }
 * )
 */
class DeadlineIsCallForPappersFormatter extends FormatterBase
{

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode)
  {
    $element = [];

    foreach ($items as $delta => $item) {

      try {
        $deadline = new \DateTime($item->value);
        $today = new \DateTime();

        $value = $today->diff($deadline)->days <= 1
          ? t('Issues accepting papers')
          : t('Past issues');
      } catch (\Exception $e) {
        $value = t('Past issues');
      }

      $element[$delta] = [
        '#markup' => $value,
      ];
    }

    return $element;
  }
}
