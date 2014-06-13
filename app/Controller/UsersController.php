<?php
class UsersController extends AppController {

	public function beforeFilter(){
		parent::beforeFilter();
	}

	public function index(){

	}

	public function login(){
		
	}

	public function signup(){
		if($this->request->is("post")){
			$this->User->create();
			if($this->User->save($this->request->data))
				$this->Session->setFlash("User sign up successful!");
			else
				$this->Session->setFlash("Sign up failed!");
			$this->set("var3" , $this->request->data);
		}
	}

}