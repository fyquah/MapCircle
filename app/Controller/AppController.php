<?php
define("FIREBASE_URI" , "https://fyquah.firebaseio.com/" , false);
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'firebase'.DIRECTORY_SEPARATOR.'firebase.php';
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */

// Some conventions : Consistently return an array with a hash called 'return' consisting of 'username' , 'id' , 'access_token'
// Don't care whether the update query is successful or not
// iff token generation fails, _generate_new_token($token) will return false

class AppController extends Controller {

    public $firebase;
	public $helpers = array('Html', 'Form', 'Session');
    public $components = array("Session" , "RequestHandler" , 
        "Auth" => array(
            "loginRedirect" => false,
            "logoutRedirect" => false,
            "unauthorizedRedirect" => false
        )
    );

    public function beforeFilter(){

        $this->response->header('Access-Control-Allow-Origin' , '*');
        $this->response->header("Access-Control-Allow-Methods" , "*");
        $this->response->header('Access-Control-Allow-Origin','*');
        $this->response->header('Access-Control-Allow-Methods','*');
        $this->response->header('Access-Control-Allow-Headers','X-Requested-With');
        $this->response->header('Access-Control-Allow-Headers','Content-Type, x-xsrf-token');
        $this->response->header('Access-Control-Max-Age','172800'); 


        $this->firebase = new Firebase(FIREBASE_URI);
        $this->Auth->allow();
    }

    protected function _check($token = NULL){
        $this->loadModel("User");

        if($token == NULL)
            return new CakeResponse(array("type" => "JSON" , "body" => json_encode(array("error" => "no token in request" , "return" => false) , JSON_NUMERIC_CHECK)));

        $post = $this->User->findByToken($token);

        if(!$post)
            return new CakeResponse(array("type" => "JSON" , "body" => json_encode(array("error" => "invalid token in request" , "return" => false) , JSON_NUMERIC_CHECK)));

        $now = time();
        if($now - intval(strtotime($post['User']['last_login'])) >= 7200) //more than two house!
            return new CakeResponse(array("type" => "JSON" , "body" => json_encode(array("error" => "your token has expired, please login again" , "return" => false) , JSON_NUMERIC_CHECK)));

        return $post['User']['id']; //if success, returns user_id for query references
    }

    protected function generate_new_token($user_id){ // returns false if failed to generate new token
        $this->LoadModel("User");

        $check = $this->User->findByid($user_id);
        $save = array();
        $return = array();

        if($check){
            $save['id'] = $check['User']['id'];
            $save['token'] = $this->Auth->password($check['User']['username'] . date('Y-m-d H:i:s'));
            $save['last_login'] = date('Y-m-d H:i:s');

            if($this->User->save($save)) {
                $return['return']['token'] = $save['token'];
                $return['return']['id'] = $save['id'];
                $return['return']['username'] = $check['User']['username'];
                // once a new token generated, it is automagically updated in firebase
                $this->firebase->set("/users/" . $user_id . "/access_token/" , $return['return']['token']);
            }
            else
                return array("return" => false);

            return $return;
        }
        else
            return array("return" => false);
    }

    protected function render_response($output = NULL , $status_code = 200){
    	$response = new CakeResponse;
        $response->body(json_encode($output) , JSON_NUMERIC_CHECK);
        $response->type("json");
        $response->header('Access-Control-Allow-Origin','*');
        $response->header('Access-Control-Allow-Methods','*');
        $response->header('Access-Control-Allow-Headers','X-Requested-With');
        $response->header('Access-Control-Allow-Headers','Content-Type, x-xsrf-token');
        $response->header('Access-Control-Max-Age','172800');        
        $response->statusCode($status_code);
        return $response;
    }

    /*Using Basic Auth
    public $components = array('Session' , "RequestHandler" , 
    	"Auth" => array(
            "authenticate" => array("Basic"=>array("fields" => )),
    		"loginRedirect" => array(
    			"controller" => "messages",
    			"action" => "index"
    		),
    		"logoutRedirect" => array(
    			"controller" => "users",
    			"action" => "login"
    		),
    		"authError" => "You have to login to visit that page!",
            'userModel' => "User",
            "loginAction" => array("controller" => "users" , "action" => "index")
    	)
    );

    public function beforeFilter(){
		AuthComponent::$sessionKey = false;
        $this->Auth->allow(array("signup"));
	}
    */
}
