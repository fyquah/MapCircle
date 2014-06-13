<?php
class MessagesController extends AppController {

	public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');

    public function index($id = NULL){
    	if($id == NULL)
    		return $this->redirect(array("controller" => "messages" , "action" => "retrieve"));
    	$post = $this->Message->findById($id);
    	if(!$post)
    		throw new NotFoundException("The post with the id " . $id . " cannot be found!");
    	$this->set("message" , $post);
    }

	public function retrieve(){
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

			if($results = $this->Message->query("SELECT * FROM (SELECT id,radius, ( 6371 * acos( cos( radians($latitude) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($longtitude) ) + sin( radians($latitude) ) * sin( radians( lat ) ) ) ) AS distance FROM messages ORDER BY distance) AS Message WHERE distance < radius;")){
				
				$output = array();
				$counter = 0;
				foreach($results as $result){
					array_push($output ,$this->Message->findById($result['Message']['id']));
					$output[$counter]['Message']['distance'] = $result['Message']['distance'];
					$counter++;
				}
				$this->set("var3" , $output);

			}
			else if($results == array()){
				$this->set("var3" , "no message has been retrieved :(");
			}
			else
				$this->set("var3" , "an error occured");

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

	public function comment($id = NULL){ 
		// deal with comment submitting or allowing ppl to submit comments
		if($id == NULL)
			return $this->redirect(array("controller" => "messages" , "action" => "retrieve"));
		$post = $this->Message->findById($id);
		if(!$post)
			throw new NotFoundException("the post with the id " . $id . " could not be found!");

		if($this->request->is(array("put" , "post"))){
			$this->request->data['Comment']['message_id'] = $id;

			if($this->Message->Comment->save($this->request->data))
				$this->Session->setFlash("Your comment has been submitted!");
			else
				$this->Session->setFlash("Failed updating your comment :(");
			return $this->redirect(array("controller"=> "messages" , "action" => "index" , $id));
		}
		else {
			$this->set("var3" , $post);
		}
	}

}