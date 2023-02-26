<?php

namespace Drupal\redirect_404_regex\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\redirect_404_regex\Redirect404RegexRuleInterface;

/**
 * Defines the redirect 404 regex rule entity type.
 *
 * @ConfigEntityType(
 *   id = "redirect_404_regex_rule",
 *   label = @Translation("Redirect 404 Regex Rule"),
 *   label_collection = @Translation("Redirect 404 Regex Rules"),
 *   label_singular = @Translation("redirect 404 regex rule"),
 *   label_plural = @Translation("redirect 404 regex rules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count redirect 404 regex rule",
 *     plural = "@count redirect 404 regex rules",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\redirect_404_regex\Redirect404RegexRuleListBuilder",
 *     "form" = {
 *       "add" = "Drupal\redirect_404_regex\Form\Redirect404RegexRuleForm",
 *       "edit" = "Drupal\redirect_404_regex\Form\Redirect404RegexRuleForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "redirect_404_regex_rule",
 *   admin_permission = "administer redirect_404_regex_rule",
 *   links = {
 *     "collection" = "/admin/config/search/redirect-404-regex-rule",
 *     "add-form" = "/admin/config/search/redirect-404-regex-rule/add",
 *     "edit-form" = "/admin/config/search/redirect-404-regex-rule/{redirect_404_regex_rule}",
 *     "delete-form" = "/admin/config/search/redirect-404-regex-rule/{redirect_404_regex_rule}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "regex_pattern",
 *     "redirect_path",
 *     "weight",
 *   }
 * )
 */
class Redirect404RegexRule extends ConfigEntityBase implements Redirect404RegexRuleInterface {

  /**
   * The redirect 404 regex rule ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The redirect 404 regex rule label.
   *
   * @var string
   */
  protected $label;

  /**
   * The redirect_404_regex_rule regex match patterh.
   *
   * @var string
   */
  protected $regex_pattern;

  /**
   * The redirect_404_regex_rule path to redirect 404s to.
   *
   * @var string
   */
  protected $redirect_path;

  /**
   * The redirect_404_regex_rule weight.
   *
   * @var integer
   */
  protected $weight;

}
