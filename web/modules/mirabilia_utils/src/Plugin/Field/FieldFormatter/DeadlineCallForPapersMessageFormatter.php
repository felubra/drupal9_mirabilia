<?php

namespace Drupal\mirabilia_utils\Plugin\Field\FieldFormatter;

use Drupal\Core\Datetime\Element\Datetime;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;
use Exception;

/**
 * Plugin implementation of the 'Utils' formatter.
 *
 * @FieldFormatter(
 *   id = "mirabilia_utils_deadline_call_for_papers_message",
 *   label = @Translation("Call for papers message"),
 *   field_types = {
 *     "datetime"
 *   }
 * )
 */
class DeadlineCallForPapersMessageFormatter extends FormatterBase
{
  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode)
  {
    $element = [];
    $entity = $items->getParent()->getEntity();

    $issueType = $entity->bundle();

    $matches = [];
    $magazineName = preg_match("/^(.*)_.*$/U", $issueType, $matches) === 1
      ? "$matches[1]"
      : '';

    foreach ($items as $delta => $item) {
      try {
        $deadline = new \DateTime($item->value);
        $today = new \DateTime();
        $sendArticleLink = $magazineName
          ? "/$magazineName/send-article"
          : "/send-article";

        $deadlineFormatted = date_format($deadline, "F j, Y");
        $deadlineExpired = $today->diff($deadline)->days > 1;
        //TODO: add formatter settings to toggle the e-mail and date format
        $value = $deadlineExpired
          ? $deadlineFormatted
          : "Accepting papers until $deadlineFormatted <br/>
              Send your article to
              <a href='mailto:subm@revistamirabilia.com'>
              subm@revistamirabilia.com</a> to
              publish in this issue. For more information,
              see our <a href='$sendArticleLink'>submission guidelines</a>.";
      } catch (\Exception $e) {
        $value = "";
      }

      $element[$delta] = [
        '#markup' => $value,
      ];
    }

    return $element;
  }
}
