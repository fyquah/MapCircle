<?php
class Message extends AppModel{
	public $hasMany = array("Comment");
}