<?php

namespace Drupal\service_club_profile\Entity;

use Drupal\views\EntityViewsData;
use Drupal\service_club_profile\Entity\MemberProfileEntity;

/**
 * Provides Views data for Member profile entity entities.
 */
class MemberProfileEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
