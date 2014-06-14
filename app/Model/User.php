<?php 

App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
	public $hasMany = array("Message");

	public $validate = array(
		"username" => array(
			"rule1" => array(
				"rule" => "notEmpty",
				"message" => "Username cannot be empty"
			),
			"rule2" => array(
				"rule" => "alphanumeric",
				"message" => "Username must only consist of alpha numeric characters"
			),
			'rule3' => array(
				"rule" => array("between" , 5 , 50),
				"message" => "Username must be between 5 and 50 characters"
			),
			'rule4' => array(
				'rule' => 'isUnique',
				'message' => "username is already taken"
			)
		),

		'password' => array(
			'rule1' => array (
				"rule" => "notEmpty",
				"message" => "Password cannot be empty!"
			),
			"rule2" => array(
				"rule" => array("between" , 8 , 50),
				"message" => "password must be betwwen 8 and 50 inclusive characters long"
			)
		),

		'first_name' => array(
			"rule" => "notEmpty",
			"message" => "First name cannot be empty!"
		),

		'last_name' => array(
			"rule" => "notEmpty",
			"message" => "Last name cannot be empty!"
		),

		'email' => array(
			'rule1' => array(
				"rule" => "notEmpty",
				"message" => "Last name cannot be empty!"
			),
			'rule2' => array(
				'rule' => 'email',
				"message" => 'please enter a valid email!'
			),
			'rule3' => array(
				'rule' => "isUnique",
				"message" => "email is already taken!"			
			)
		)

	);

	public function beforeSave($options = array()) {
	    if (isset($this->data[$this->alias]['password'])) {
	        $passwordHasher = new SimplePasswordHasher();
	        $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
	    }
   		return true;
	}
}