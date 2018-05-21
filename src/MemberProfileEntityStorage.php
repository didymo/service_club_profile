<?php

namespace Drupal\service_club_profile;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\service_club_profile\Entity\MemberProfileEntityInterface;

/**
 * Defines the storage handler class for Member profile entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Member profile entity entities.
 *
 * @ingroup service_club_profile
 */
class MemberProfileEntityStorage extends SqlContentEntityStorage implements MemberProfileEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(MemberProfileEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {member_profile_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {member_profile_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(MemberProfileEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {member_profile_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('member_profile_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
