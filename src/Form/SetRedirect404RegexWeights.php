<?php

namespace Drupal\redirect_404_regex\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Redirect 404 Regex form.
 */
class SetRedirect404RegexWeights extends FormBase {

  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('entity_type.manager'));
  }

  /**
   * Construct a form.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'redirect_404_regex_set_redirect404_regex_weights';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['info'] = [
      '#type' => 'item',
      '#markup' => $this->t('If a 404 path matches the pattern for multiple rules, the rule with the lowest weight will be applied. Rules with higher weights will be ignored.')
    ];

    $form['table-row'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('Rule'),
        $this->t('Regex'),
        $this->t('Path'),
        $this->t('Weight'),
      ],
      '#empty' => $this->t('There are no redirect 404 regex rules yet.'),
      '#tabledrag' => [
        [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'table-sort-weight',
        ],
      ],
    ];

    $regexRuleStorage = $this->entityTypeManager->getStorage('redirect_404_regex_rule');
    $query = $regexRuleStorage->getQuery()->sort('weight', 'ASC');
    $results = $query->execute();
    $rules = $regexRuleStorage->loadMultiple($results);
    foreach ($rules as $rule) {
      $form['table-row'][$rule->id()]['#attributes']['class'][] = 'draggable';
      $form['table-row'][$rule->id()]['#weight'] = $rule->get('weight');

      // Some table columns containing raw markup.
      $form['table-row'][$rule->id()]['name'] = [
        '#markup' => $rule->label(),
      ];

      $form['table-row'][$rule->id()]['regex'] = [
        '#markup' => $rule->get('regex_pattern'),
      ];

      $form['table-row'][$rule->id()]['path'] = [
        '#markup' => $rule->get('redirect_path'),
      ];

      $form['table-row'][$rule->id()]['weight'] = [
        '#type' => 'weight',
        '#title' => $this->t('Weight for @title', ['@title' => $rule->label()]),
        '#title_display' => 'invisible',
        '#default_value' => $rule->get('weight'),
        // Classify the weight element for #tabledrag.
        '#attributes' => ['class' => ['table-sort-weight']],
      ];
    }

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];
    $form['actions']['cancel'] = [
      '#type' => 'submit',
      '#value'  => 'Cancel',
      '#attributes' => [
        'title' => $this->t('Cancel'),
      ],
      '#submit' => ['::cancel'],
      '#limit_validation_errors' => [],
    ];

    return $form;
  }

  /**
   * Form submission handler for the 'Return to' action.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function cancel(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirect('entity.redirect_404_regex_rule.collection');
  }

  /**
   * Form submission handler for the simple form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Because the form elements were keyed with the item ids from the database,
    // we can simply iterate through the submitted values.
    $submission = $form_state->getValue('table-row');
    $storage = $this->entityTypeManager->getStorage('redirect_404_regex_rule');
    foreach ($submission as $id => $item) {
      $rule = $storage->load($id);
      $rule->set('weight', $item['weight']);
      $rule->save();
    }
    $form_state->setRedirect('entity.redirect_404_regex_rule.collection');
  }

}
