<?php
class UsersController extends AppController {
	// Basic Auth version
	// public function beforeFilter(){
	// 	parent::beforeFilter();
	// 	$this->Auth->allow("index");
	// }

	

	public function index(){
		
	}

	public function logout(){
		if($this->request->is("post")){
			$access_token = $this->request->data['User']['access_token'];
			$user_id = $this->_check($access_token);
			if(is_numeric($user_id));
			else
				return $user_id;
			$save['id'] = $user_id;
			$save['token'] = NULL;

			$return = array();

			if($this->User->save($save)){

				$this->firebase->delete('users/' . $user_id);
				$return = array("notice" => "successfully logged out!");
			}
			else
				$return = array("error" => "Error logging use out");

			return $this->render_response($return , 200);
		}
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

					$active_user = array();
					$active_user['user_id'] = $check['User']['id'];

					//$firebase = new Firebase(FIREBASE_URI);
					$this->firebase->update('users/' . $save['id'] . '/' , array(	
							'access_token' => ($save['token']),
							'username' => $check['User']['username']
					));
					return $this->render_response($return , 200);

				}
				else {
					$return['error'] = "Cannot obtain token, please try again";
					$return['return'] = false;
				}
			
			}
			else{
				$return['error'] = "Check your user credentials again";
				$return['return'] = false;
			}

			return $this->render_response($return , 404);
		}
	}

	public function signup(){
		if($this->request->is("post")){
			$output = array();

			$this->User->create();
			if(!$this->User->save($this->request->data)){
				$output['error'] = $this->User->validationErrors;
				return $this->render_response($output);
			}
			else
				return $this->login();
		}
	}

}