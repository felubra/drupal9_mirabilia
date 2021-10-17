<?php

namespace Drupal\mirabilia_utils\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Entity\EntityInterface;
use Exception;

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

  private function getArticlesCountForIssue(EntityInterface $entity) {
    $issue_type = $entity->bundle();
    $nid  = $entity->id();

    $articleType = '';
    switch ($issue_type) {
      case 'issue': {
        $articleType = 'article';
        break;
      }
    }

    if (!empty($articleType)) {
      try {
        $query =  \Drupal::entityQuery('node')
          ->condition('type', $articleType)
          ->condition('field_'.$issue_type.'.entity.nid', $nid);

        return ($query->count()->execute()) * 1;
      } catch(Exception $e) {
        return 0;
      }
    }
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode)
  {
    $element = [];
    $entity = $items->getParent()->getEntity();

    foreach ($items as $delta => $item) {
      try {
        $deadline = new \DateTime($item->value);
        $today = new \DateTime();

        $value = ($today->diff($deadline)->days <= 1) || ($this->getArticlesCountForIssue($entity) == 0)
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
