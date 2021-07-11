<?php

namespace Drupal\user_data\Service;

use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Current user date service class.
*/
class UserDataService {

  /**
   * The current account.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

	
  /**
   * Constructs a new UserdataService.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user.
   */
  public function __construct(AccountInterface $account) {
    $this->account = $account;
  }

  /**
  * {@inheriteddoc}
  */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user')
    );
  }

  /**
   * Get current user details.
   */
  public function currentUserData() {
  	$account = $this->account;
  	$uid = $account->id();
  	$email = $account->getEmail();
  	$display_name = $account->getDisplayName();
  	$account_name = $account->getAccountName();
  	$roles = $account->getRoles();

  	return [
  		'uid' => $uid,
  		'email'=> $email,
  		'display_name' => $display_name,
  		'account_name' => $account_name,
  		'user_roles' => $roles,
  	];
    
  }

}
