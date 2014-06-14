<?php
class UsersController extends AppController {
	// Basic Auth version
	// public function beforeFilter(){
	// 	parent::beforeFilter();
	// 	$this->Auth->allow("index");
	// }

	public function index(){
		
	}

	public function login(){
		if($this->request->is("post")){

			$post["username"] = $this->request->data['User']['username'];
			$post['password'] = $this->request->data['User']['password'];
			$data['hash'] = $this->Auth->password($post['password']); // Uses simple hasher

			$check = $this->User->find('first' , array(
				"conditions" => array(
					"username" => $post['username'],
					'password' => $data['hash']
				)
			));

			$save = array();
			$return = array();	

			if($check){
				$save['id'] = $check['User']['id'];
				$save['token'] = $this->Auth->password($post['username'] . date('Y-m-d H:i:s'));
				$save['last_login'] = date('Y-m-d H:i:s');

				if($this->User->save($save)) {
					$return['return']['token'] = $save['token'];
					$return['return']['id'] = $check['User']['id'];
					$return['return']['username'] = $check['User']['username'];
				}
				else {
					$return['error'] = "Error obtaining token, please try again";
					$return['return'] = false;
				}
			
			}
			else{
				$return['error'] = "Check your user credentials again";
				$return['return'] = false;
			}

			return new CakeResponse(array("type" => "JSON" , "body" => json_encode($return , JSON_NUMERIC_CHECK)));
		}
	}

	public function signup(){
		if($this->request->is("post")){
			$output = array();

			$this->User->create();
			if(!$this->User->save($this->request->data)){
				$output['error'] = $this->User->validationErrors;
				return new CakeResponse(array("type" => "JSON" , "body" => json_encode($output , JSON_NUMERIC_CHECK)));
			}
			else
				return $this->login();
		}
	}

}