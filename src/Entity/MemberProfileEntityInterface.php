<?php

namespace Drupal\service_club_profile\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Member profile entity entities.
 *
 * @ingroup service_club_profile
 */
interface MemberProfileEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Member profile entity name.
   *
   * @return string
   *   Name of the Member profile entity.
   */
  public function getName();

  /**
   * Sets the Member profile entity name.
   *
   * @param string $name
   *   The Member profile entity name.
   *
   * @return \Drupal\service_club_profile\Entity\MemberProfileEntityInterface
   *   The called Member profile entity entity.
   */
  public function setName($name);

  /**
   * Gets the Member profile entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Member profile entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Member profile entity creation timestamp.
   *
   * @param int $timestamp
   *   The Member profile entity creation timestamp.
   *
   * @return \Drupal\service_club_profile\Entity\MemberProfileEntityInterface
   *   The called Member profile entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Member profile entity published status indicator.
   *
   * Unpublished Member profile entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Member profile entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Member profile entity.
   *
   * @param bool $published
   *   TRUE to set this Member profile entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\service_club_profile\Entity\MemberProfileEntityInterface
   *   The called Member profile entity entity.
   */
  public function setPublished($published);

  /**
   * Gets the Member profile entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Member profile entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\service_club_profile\Entity\MemberProfileEntityInterface
   *   The called Member profile entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Member profile entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Member profile entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\service_club_profile\Entity\MemberProfileEntityInterface
   *   The called Member profile entity entity.
   */
  public function setRevisionUserId($uid);

}
