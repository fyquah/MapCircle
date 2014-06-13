<?php
class MessagesController extends AppController {

    public function index($id = NULL){
    	
    }

    public function view(){
    	if($this->request->is(array("post" , "put"))){
    		$id = $this->request->data["Message"]["id"];
    		if(empty($id))
    			$this->set("var3" , "error : did not provide id in POST / PUT request");

    		else {
    			$message = $this->Message->findById($id);
    			if(!$message)
    				$this->set("var3" , "error : message with id " . $id . " does not exist");
    			else {
    				$this->set("var3" , $message);
    				$this->set('_serialize', 'var3');
    			}
    		}
    	}
    }

	public function retrieve(){
		if($this->request->is(array('post' , 'put'))){ //in json format
			$longtitude = $this->request->data['Message']['lng'];
			$latitude = $this->request->data['Message']['lat'];

			if($results = $this->Message->query("SELECT * FROM (SELECT id,radius, ( 6371 * acos( cos( radians($latitude) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($longtitude) ) + sin( radians($latitude) ) * sin( radians( lat ) ) ) ) AS distance FROM messages ORDER BY distance) AS Message WHERE distance < radius;")){
				
				$output = array();
				$counter = 0;
				foreach($results as $result){
					array_push($output ,$this->Message->findById($result['Message']['id']));
					$output[$counter]['Message']['distance'] = $result['Message']['distance'];
					$counter++;
				}
				$this->set("var3" , $output);
				$this->set('_serialize', 'var3');
			}
			else if($results == array()){
				$this->set("var3" , "notice : no message has been for your distance!");
			}
			else
				$this->set("var3" , "error : an error occured in retrieving message!");
		}
	}

	public function submit(){
		if($this->request->is(array("post" , "put"))){
			$this->request->data['Message']['user_id'] = AuthComponent::user('id');
			$this->Message->create();
			if($this->Message->save($this->request->data)) {
				$this->Session->setFlash("Your message has been received!");
				$this->set("var3" , "notice : successfully broadcasted message!");
			}
			else {
				$this->Session->setFlash("an error occured");
				$this->set("var3" , "error : cannot submit post!");
			}
		}
	}

	public function comment(){ 
		// deal with comment submitting or allowing ppl to submit comments
		
		// $post = $this->Message->findById($id);
		// if(!$post)
		// 	throw new NotFoundException("the post with the id " . $id . " could not be found!");

		if($this->request->is(array("put" , "post"))){ //user has submitted a comment
			$this->request->data['Comment']['user_id'] = AuthComponent::user('id');
			$id = $this->request->data["Comment"]["message_id"];
			$post = $this->Message->findById($id);

			if(!$post) {
				$this->set("var3" , "error : post with id " . $id . " could not be found in server!");
				$this->Session->setFlash("Post not found!");
				return;
			}

			if($this->Message->Comment->save($this->request->data)) {
				$this->Session->setFlash("Your comment has been submitted!");
				$this->set("var3" , "notice : successfully submitted comment!");
			}
			else {
				$this->Session->setFlash("Failed updating your comment :(");
				$this->set("var3" , "error : failed uploading comment!");
			}
		}
		else { //GUI to submit comment
			
		}
	}

}