<?php
class SecondaryResponse extends AppModel {
	public $belongsTo = array("User" => array(
		"className" => "User" ,
		"fields" => array("id" , "first_name" , "last_name")
		) , "Response"
	);
	// each secondary respond belongs to one user and one primary response

	public $validate = array(
		"content" => array(
			"rule1" => array(
				'rule' => 'notEmpty',
				"message" => "Response cannot be empty!"
			)
		),
		"response_id" => array(
			"rule1" => array(
				"rule" => "numeric",
				"message" => "response_id must be numeric!"
			)
		),
		"user_id" => array(
			"rule1" => array(
				"rule" => "numeric" , 
				"message" => "user_id must be numeric!"
			)
		)
	);
}