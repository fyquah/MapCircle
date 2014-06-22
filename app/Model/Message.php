<?php
class Message extends AppModel{
	public $hasMany = array("Response");

	public $belongsTo = array("User" => array(
		"className" => "User" ,
		"fields" => array("id" , "first_name" , "last_name" , "username")
	));

	public $validate = array(
		"message" => array(
			"rule2" => array(
				'rule' => 'notEmpty',
				"message" => "message to be broadcasted cannot be empty!"
			)
		),

		"lat" => array(
			"rule2" => array(
				'rule' => 'notEmpty',
				"message" => "current latitude of the broadcasted message cannot be empty!"
			),
			"rule3" => array(
				"rule" => "numeric",
				"message" => "latitude must be a valid number!"
			),
			"rule4" => array(
				"rule" => array("range" , -180 , 180),
				"latitude must be between -90 degrees and 90 degrees!"
			)
		),

		"lng" => array(
			"rule2" => array(
				'rule' => 'notEmpty',
				"message" => "current longitude of the broadcasted message cannot be empty!"
			),
			"rule3" => array(
				"rule" => "numeric",
				"message" => "longitude must be a valid number!"
			),
			"rule4" => array(
				"rule" => array("range" , -180 , 180),
				"message" => "latitude must be between -180 degrees and 180 degrees!"
			)
		),

		"radius" => array(
			"rule2" => array(
				'rule' => 'notEmpty',
				"message" => "radius of the broadcasted message cannot be empty!"
			),
			"rule3" => array(
				"rule" => "numeric",
				"message" => "radius must be a valid number!"
			)
		),

		"period" => array(
			"rule2" => array(
				'rule' => 'notEmpty',
				"message" => "period of the broadcasting message must be filled!"
			),
			"rule3" => array(
				"rule" => "numeric",
				"message" => "period must be a valid number!"
			)
		),

		"user_id" => array(
			"rule1" => array(
				"rule" => "notEmpty",
				"message" => "user_id of the broadcasted message cannot be empty!"
			),
			"rule3" => array(
				"rule" => "numeric",
				"message" => "user_id must be a valid number!"
			)
		)

	);

	public function beforeSave($options = array()) {
	    if (isset($this->data[$this->alias]['period'])) {
	        $this->data[$this->alias]['period'] = $this->data[$this->alias]['period'] * 60; 
	    }
   		return true;
	}
}