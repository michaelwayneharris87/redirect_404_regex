<?php

namespace Drupal\redirect_404_regex\EventSubscriber;

use Drupal\config_pages\ConfigPagesLoaderServiceInterface;
use Drupal\Core\EventSubscriber\HttpExceptionSubscriberBase;
use Drupal\Core\Path\CurrentPathStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Redirect_404_regex Custom event subscriber.
 */
class Redirect404RegexSubscriber extends HttpExceptionSubscriberBase {

  /**
   * The current path stack service.
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Constructs event subscriber.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, CurrentPathStack $currentPath) {
    $this->entityTypeManager = $entityTypeManager;
    $this->currentPath = $currentPath;
  }

  /**
   * On a 404, check if a 440 redirect is registered. If so, use it.
   */
  public function on404(ExceptionEvent $event) {
    $storage = $this->entityTypeManager->getStorage('redirect_404_regex_rule');
    $query = $storage->getQuery()->sort('weight', 'ASC');
    $results = $query->execute();

    // The redirect rules.
    $rules = $storage->loadMultiple($results);

    // The path from the request.
    $path = $this->currentPath->getPath();

    if (count($rules) > 0) {
      foreach ($rules as $rule) {
        // Stop the foreeach loop as soon as a matching regex is found.
        $loopBreak = FALSE;
        $regex = $rule->get('regex_pattern');
        $redirectPath = $rule->get('redirect_path');
        if (preg_match($regex, $path)) {
          $event->setResponse(new RedirectResponse($event->getRequest()->getSchemeAndHttpHost() . $redirectPath));
          $loopBreak = TRUE;
        }
        if ($loopBreak) {
          break;
        }
      }
    }
  }

  /**
   *
   */
  protected function getHandledFormats() {
    return ['html'];
  }

}
