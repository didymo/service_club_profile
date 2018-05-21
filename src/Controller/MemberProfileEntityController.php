<?php

namespace Drupal\service_club_profile\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\service_club_profile\Entity\MemberProfileEntityInterface;

/**
 * Class MemberProfileEntityController.
 *
 *  Returns responses for Member profile entity routes.
 */
class MemberProfileEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Member profile entity  revision.
   *
   * @param int $member_profile_entity_revision
   *   The Member profile entity  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($member_profile_entity_revision) {
    $member_profile_entity = $this->entityManager()->getStorage('member_profile_entity')->loadRevision($member_profile_entity_revision);
    $view_builder = $this->entityManager()->getViewBuilder('member_profile_entity');

    return $view_builder->view($member_profile_entity);
  }

  /**
   * Page title callback for a Member profile entity  revision.
   *
   * @param int $member_profile_entity_revision
   *   The Member profile entity  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($member_profile_entity_revision) {
    $member_profile_entity = $this->entityManager()->getStorage('member_profile_entity')->loadRevision($member_profile_entity_revision);
    return $this->t('Revision of %title from %date', ['%title' => $member_profile_entity->label(), '%date' => format_date($member_profile_entity->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Member profile entity .
   *
   * @param \Drupal\service_club_profile\Entity\MemberProfileEntityInterface $member_profile_entity
   *   A Member profile entity  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(MemberProfileEntityInterface $member_profile_entity) {
    $account = $this->currentUser();
    $langcode = $member_profile_entity->language()->getId();
    $langname = $member_profile_entity->language()->getName();
    $languages = $member_profile_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $member_profile_entity_storage = $this->entityManager()->getStorage('member_profile_entity');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $member_profile_entity->label()]) : $this->t('Revisions for %title', ['%title' => $member_profile_entity->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all member profile entity revisions") || $account->hasPermission('administer member profile entity entities')));
    $delete_permission = (($account->hasPermission("delete all member profile entity revisions") || $account->hasPermission('administer member profile entity entities')));

    $rows = [];

    $vids = $member_profile_entity_storage->revisionIds($member_profile_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\service_club_profile\MemberProfileEntityInterface $revision */
      $revision = $member_profile_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $member_profile_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.member_profile_entity.revision', ['member_profile_entity' => $member_profile_entity->id(), 'member_profile_entity_revision' => $vid]));
        }
        else {
          $link = $member_profile_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.member_profile_entity.translation_revert', ['member_profile_entity' => $member_profile_entity->id(), 'member_profile_entity_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.member_profile_entity.revision_revert', ['member_profile_entity' => $member_profile_entity->id(), 'member_profile_entity_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.member_profile_entity.revision_delete', ['member_profile_entity' => $member_profile_entity->id(), 'member_profile_entity_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['member_profile_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
