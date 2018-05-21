<?php

namespace Drupal\service_club_profile;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Member profile entity entities.
 *
 * @ingroup service_club_profile
 */
class MemberProfileEntityListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Member profile entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\service_club_profile\Entity\MemberProfileEntity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.member_profile_entity.edit_form',
      ['member_profile_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
