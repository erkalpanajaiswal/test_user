<?php

namespace Drupal\user_data\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\user_data\Service\UserDataService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller class to return user data in json format.
 */
class UserDataController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The user data service.
   *
   * @var \Drupal\user_data\Service\UserDataService
   */
  protected $userData;

  /**
   * Constructs a UserController object.
   */
  public function __construct(UserDataService $user_data) {
    $this->userData = $user_data;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('user_data.get_user_data'));
  }

  /**
   * Get user data from service and return in cacheable json format.
   */
  public function getUserData() {
    $build = [];

    // Addig context and tags for proper cache invalidation.
    $current_user = $this->userData->currentUserData();
    $build['#cache'] = [
      'contexts' =>['user'],
      'tags' =>['user:' . $current_user['uid']],
    ];

    // Return data in json format.
    $response = new CacheableJsonResponse(['user' => $current_user], 200);
    $cache_data = new CacheableMetadata();
    return $response->addCacheableDependency($cache_data->createFromRenderArray($build));
  }

  /**
   * Check access for odd user uid.
   */
  public function access(AccountInterface $account) {
    return (($account->id() % 2 != 0) ? AccessResult::allowed() : AccessResult::forbidden());
  }
  
}
