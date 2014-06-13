<?php
class MessagesController extends AppController {

	public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');

	public function index(){
		if($this->request->is(array('post' , 'put'))){
			$longtitude = $this->request->data['Message']['lng'];
			$latitude = $this->request->data['Message']['lat'];

			// output queries which are at a radius of 25 miles to the coordinate 37 , -122 (lat , long)
			// To search by kilometers instead of miles, replace 3959 with 6371.
			// 3959 -> miles
			// 6371 -> kilometers
			// 5.459008, 100.298645
			// $latitude = 5.465032;
			// $longtitude =  100.276073;
			// $radius = 5;

			// $original from google website = "SELECT id, ( 6371 * acos( cos( radians('$latitude') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('$longtitude') ) + sin( radians('$latitude') ) * sin( radians( lat ) ) ) ) AS distance FROM markers HAVING distance < '$radius' ORDER BY distance LIMIT 0 , 20;";

			if($results = $this->Message->query("SELECT * FROM (SELECT id,radius, ( 6371 * acos( cos( radians('$latitude') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('$longtitude') ) + sin( radians('$latitude') ) * sin( radians( lat ) ) ) ) AS distance FROM messages ORDER BY distance) AS Message WHERE distance < radius;")){
				
				$output = array();
				$counter = 0;
				foreach($results as $result){
					array_push($output ,$this->Message->findById($result['Message']['id']));
					$output[$counter]['Message']['distance'] = $result['Message']['distance'];
					$counter++;
				}
				$this->set("var3" , $output);

			}
			else
				$this->set("var3" , "an error occured");

		}
		else{

		}
	}

	public function submit(){
		if($this->request->is(array("post" , "put"))){
			$this->request->data['Message']['user_id'] = 6;
			$this->Message->create();
			if($this->Message->save($this->request->data)) {
				$this->Session->setFlash("Your message has been received!");
			}
			else {
				$this->Session->setFlash("an error occured");
			}
			$this->redirect(array("controller" => "messages" , "action" => "index"));
		}
	}

}