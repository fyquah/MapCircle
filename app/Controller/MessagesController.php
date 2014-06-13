<?php
class MessagesController extends AppController {

	public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session' , "RequestHandler");

    public function index($id = NULL){
    	$this->redirect(array("controller" => "messages" , "action" => "retrieve"));
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