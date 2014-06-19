<?php
class Reply extends AppModel {
	public $belongsTo = array("User" => array(
		"fields" => array('username' , 'first_name' , 'last_name')
	));
	// each secondary respond belongs to one user and one primary response

	// public $validate = array(
	// 	"content" => array(
	// 		"rule1" => array(
	// 			'rule' => 'notEmpty',
	// 			"message" => "Response cannot be empty!"
	// 		)
	// 	),
	// 	"response_id" => array(
	// 		"rule1" => array(
	// 			"rule" => "numeric",
	// 			"message" => "response_id must be numeric!"
	// 		)
	// 	),
	// 	"user_id" => array(
	// 		"rule1" => array(
	// 			"rule" => "numeric" , 
	// 			"message" => "user_id must be numeric!"
	// 		)
	// 	)
	// );
}