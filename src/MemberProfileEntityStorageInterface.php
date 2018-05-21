<?php

namespace Drupal\service_club_profile;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface MemberProfileEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Member profile entity revision IDs for a specific Member profile entity.
   *
   * @param \Drupal\service_club_profile\Entity\MemberProfileEntityInterface $entity
   *   The Member profile entity entity.
   *
   * @return int[]
   *   Member profile entity revision IDs (in ascending order).
   */
  public function revisionIds(MemberProfileEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Member profile entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Member profile entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\service_club_profile\Entity\MemberProfileEntityInterface $entity
   *   The Member profile entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(MemberProfileEntityInterface $entity);

  /**
   * Unsets the language for all Member profile entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
