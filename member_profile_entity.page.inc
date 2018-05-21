<?php

/**
 * @file
 * Contains member_profile_entity.page.inc.
 *
 * Page callback for Member profile entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Member profile entity templates.
 *
 * Default template: member_profile_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_member_profile_entity(array &$variables) {
  // Fetch MemberProfileEntity Entity Object.
  $member_profile_entity = $variables['elements']['#member_profile_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
