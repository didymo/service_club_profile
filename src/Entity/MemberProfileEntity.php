<?php

namespace Drupal\service_club_profile\Entity;

use Drupal\Component\Utility\Number;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Member profile entity entity.
 *
 * @ingroup service_club_profile
 *
 * @ContentEntityType(
 *   id = "member_profile_entity",
 *   label = @Translation("Member profile entity"),
 *   handlers = {
 *     "storage" = "Drupal\service_club_profile\MemberProfileEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\service_club_profile\MemberProfileEntityListBuilder",
 *     "views_data" = "Drupal\service_club_profile\Entity\MemberProfileEntityViewsData",
 *     "translation" = "Drupal\service_club_profile\MemberProfileEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\service_club_profile\Form\MemberProfileEntityForm",
 *       "add" = "Drupal\service_club_profile\Form\MemberProfileEntityForm",
 *       "edit" = "Drupal\service_club_profile\Form\MemberProfileEntityForm",
 *       "delete" = "Drupal\service_club_profile\Form\MemberProfileEntityDeleteForm",
 *     },
 *     "access" = "Drupal\service_club_profile\MemberProfileEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\service_club_profile\MemberProfileEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "member_profile_entity",
 *   data_table = "member_profile_entity_field_data",
 *   revision_table = "member_profile_entity_revision",
 *   revision_data_table = "member_profile_entity_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer member profile entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/member_profile_entity/{member_profile_entity}",
 *     "add-form" = "/admin/structure/member_profile_entity/add",
 *     "edit-form" = "/admin/structure/member_profile_entity/{member_profile_entity}/edit",
 *     "delete-form" = "/admin/structure/member_profile_entity/{member_profile_entity}/delete",
 *     "version-history" = "/admin/structure/member_profile_entity/{member_profile_entity}/revisions",
 *     "revision" = "/admin/structure/member_profile_entity/{member_profile_entity}/revisions/{member_profile_entity_revision}/view",
 *     "revision_revert" = "/admin/structure/member_profile_entity/{member_profile_entity}/revisions/{member_profile_entity_revision}/revert",
 *     "revision_delete" = "/admin/structure/member_profile_entity/{member_profile_entity}/revisions/{member_profile_entity_revision}/delete",
 *     "translation_revert" = "/admin/structure/member_profile_entity/{member_profile_entity}/revisions/{member_profile_entity_revision}/revert/{langcode}",
 *     "collection" = "/admin/structure/member_profile_entity",
 *   },
 *   field_ui_base_route = "member_profile_entity.settings"
 * )
 */
class MemberProfileEntity extends RevisionableContentEntityBase implements MemberProfileEntityInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the
    // member_profile_entity owner the revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getUserName() {
    return $this->get('user_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setUserName($user_name) {
    $this->set('user_name', $user_name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmail() {
    return $this->get('email')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEmail($email) {
    $this->set('email', $email);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getMemberNumber() {
    return $this->get('member_number')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setMemberNumber($member_number) {
    $this->set('member_number', $member_number);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDateOfBirth() {
    return $this->get('birth_date')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDateOfBirth($birth_date) {
    $this->set('birth_date', $birth_date);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getProfilePicture() {
    return $this->get('profile_picture')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setProfilePicture($profile_picture) {
    $this->set('profile_picture', $profile_picture);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getNameBool() {
    return $this->get('name_box')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getUserNameBool() {
    return $this->get('user_name_box')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmailBool() {
    return (int) $this->get('email_box')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getMemberNumberBool() {
    return $this->get('member_number_box')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getBirthDateBool() {
    return $this->get('birth_date_box')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Member profile entity entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 7,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Member Profile Entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['name_box'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('NamePublic'))
      ->setDescription(t('Toggle Public Display'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 1,
      ]);

    $fields['user_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('User Name'))
      ->setDescription(t('The User Name of the Member Profile Entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['user_name_box'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('UserPublic'))
      ->setDescription(t('Toggle Public Display'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 2,
      ]);

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email Address'))
      ->setDescription(t('The email of the Member Profile Entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['email_box'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('EmailPublic'))
      ->setDescription(t('Toggle Public Display'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 3,
      ]);

    $fields['member_number'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Member Number'))
      ->setDescription(t('The member number of the Member Profile Entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 8,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['member_number_box'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('MemPublic'))
      ->setDescription(t('Toggle Public Display'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 4,
      ]);

    $fields['birth_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Date of Birth'))
      ->setDescription(t('The birthdate of the Member Profile Entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'datetime_type' => 'date',
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'datetime_default',
        'settings' => [
          'format_type' => 'html_date',
        ],
        'weight' => 4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'datetime_default',
        'weight' => 4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['birth_date_box'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('BirthPublic'))
      ->setDescription(t('Toggle Public Display'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 5,
      ]);

    $fields['profile_picture'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Profile Picture'))
      ->setDescription(t('The profile picture of the Member Profile Entity.'))
      ->setSettings([
        'file_directory' => 'IMAGE_FOLDER',
        'alt_field_required' => FALSE,
        'file_extensions' => 'png jpg jpeg',
      ])
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'default',
        'weight' => 5,
      ))
      ->setDisplayOptions('form', array(
        'label' => 'above',
        'type' => 'image_image',
        'weight' => 5,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Member profile entity is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 6,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

}
