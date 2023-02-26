<?php

namespace Drupal\redirect_404_regex\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Redirect 404 Regex Rule form.
 *
 * @property \Drupal\redirect_404_regex\Redirect404RegexRuleInterface $entity
 */
class Redirect404RegexRuleForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {

    $form = parent::form($form, $form_state);

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#description' => $this->t('Label for the redirect 404 regex rule.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\redirect_404_regex\Entity\Redirect404RegexRule::load',
      ],
      '#disabled' => !$this->entity->isNew(),
    ];

    $form['regex_pattern'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Regex Pattern'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->get('regex_pattern'),
      '#description' => $this->t('If the 404 requested path matches this regex, the rule fires.'),
      '#required' => TRUE,
    ];

    $form['redirect_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Redirect Path'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->get('redirect_path'),
      '#description' => $this->t('The path this rule will redirect the 404 to (if the regex matches).'),
      '#required' => TRUE,
    ];

    $form['weight'] = [
      '#type' => 'number',
      '#title' => $this->t('Weight'),
      '#default_value' => $this->entity->get('weight') ? $this->entity->get('weight') : 0,
      '#min' => -50,
      '#max' => 50,
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);
    $message_args = ['%label' => $this->entity->label()];
    $message = $result == SAVED_NEW
      ? $this->t('Created new redirect 404 regex rule %label.', $message_args)
      : $this->t('Updated redirect 404 regex rule %label.', $message_args);
    $this->messenger()->addStatus($message);
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $result;
  }

}
