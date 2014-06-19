<?php
class Response extends AppModel {
	public $belongsTo = array("User" => array(
		"fields" => array('username' , 'first_name' , 'last_name')
	));

	public $hasMany = array("Reply");
}