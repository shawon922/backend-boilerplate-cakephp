<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller\Auth;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Security;
use Cake\Validation\Validator;
use Cake\Routing\Router;
use Firebase\JWT\JWT;

/**
 * Login Controller handles all login
 *
 * @package    API
 * @subpackage Auth
 */
class AuthController extends AppController
{
  /**
   * Initialize
   *
   * @author Md Mahfuzur Rahman
   * @method
   * @param
   * @header
   * @return
   * @redirect
   * @throws
   * @access
   * @static
   * @since 11/26/2018
   */
  public function initialize()
  {
    parent::initialize();
    $this->loadComponent('Auth');
    $this->Auth->allow([
      'login', 
      'token',
    ]);
  }

  /**
   *
   *
   * @author Md Mahfuzur Rahman
   * @method
   * @param
   * @header
   * @return
   * @redirect
   * @throws
   * @access
   * @static
   * @since 11/26/2018
   */
  public function beforeRender(Event $event)
  {
    parent::beforeRender($event);
  }

  /**
   * login
   *
   * @author 
   * @method POST
   * @header
   * @return
   * @redirect
   * @throws
   * @access
   * @static
   * @since 06/26/2019
   */
  public function login()
  {
    $response = [];

    if (!$this->request->is('post')) throw new MethodNotAllowedException('Method not allowed');
    
    $user = $this->Auth->identify();

    if (!$user) {
      throw new UnauthorizedException("Invalid login details");
    } else {
        $key = Security::salt();
        $response = [
            'msg' => 'Login successfully',
            'success' => true,
            'user' => $user,
            'data' => [
                'token' => JWT::encode([
                    'alg' => 'HS256',
                    'id' => $user['id'],
                    'sub' => $user['id'],
                    'iat' => time(),
                    'exp' =>  time() + (60 * 60 * 3), 
                ],
                $key),
                'refresh_token' => JWT::encode([
                    'alg' => 'HS256',
                    'id' => $user['id'],
                    'sub' => $user['id'],
                    'iat' => time(),
                    'exp' =>  time() + (60 * 60 * 24 * 3),
                ],
                $key)
            ],
            '_serialize' => ['success', 'data', 'user', 'key']
        ];
    }

    $this->set($response);
  }

  public function checkLogin()
  {
    echo 'You are logged in.';
    die;
  }
}