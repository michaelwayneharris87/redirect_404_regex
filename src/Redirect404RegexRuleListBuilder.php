<?php

namespace Drupal\redirect_404_regex;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of redirect 404 regex rules.
 */
class Redirect404RegexRuleListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Label');
    $header['id'] = $this->t('Machine name');
    $header['regex_pattern'] = $this->t('Regex Pattern');
    $header['redirect_path'] = $this->t('Redirect Path');
    $header['weight'] = $this->t('Weight');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\redirect_404_regex\Redirect404RegexRuleInterface $entity */
    $row['label'] = $entity->label();
    $row['id'] = $entity->id();
    $row['regex_pattern'] = $entity->get('regex_pattern');
    $row['redirect_path'] = $entity->get('redirect_path');
    $row['weight'] = $entity->get('weight');
    return $row + parent::buildRow($entity);
  }

  public function render() {
    $info = [
      'info' => [
        '#type' => 'item',
        '#markup' => $this->t('If a 404 path matches the pattern for multiple rules, the rule with the lowest weight will be applied. Rules with higher weights will be ignored.')
      ]
    ];

    $parentBuild = parent::render();

    $build = array_merge($info, $parentBuild);

    return $build;
  }

}
