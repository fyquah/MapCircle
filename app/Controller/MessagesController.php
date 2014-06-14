<?php
class MessagesController extends AppController {

    public function index($id = NULL){
    	
    }

    public function view(){
    	if($this->request->is(array("post" , "put"))){

    		//validation of token
			$token = $this->request->data['Message']['access_token'];

			$user_id = $this->_check($token);
			if(is_numeric($user_id));
			else
				return $user_id; // a response object stating occurence of error
			//finish validating token

			$output = array();

    		$id = $this->request->data["Message"]["id"];
    		if(empty($id))
    			$output['error'] = "did not provide message id";

    		else {
    			$message = $this->Message->findById($id);
    			if(!$message)
    				$output['error'] = "The message with id " . $id . "cannot be found!";
    			else
    				$output['result']  = $message;
    		}

    		// generating new token, false if token not updated
			$temp = $this->generate_new_token($user_id);
			$output['return'] = $temp['return'];
			// whether or not it is false or generated, it is generated :)

			return $this->render_response($output);
    	}

    }

	public function retrieve(){
		if($this->request->is(array('post' , 'put'))) { //in json format
			
			//validation of token
			$token = $this->request->data['Message']['access_token'];

			$user_id = $this->_check($token);
			if(is_numeric($user_id));
			else
				return $user_id; // a response object stating occurence of error
			//finish validating token

			$longtitude = $this->request->data['Message']['lng'];
			$latitude = $this->request->data['Message']['lat'];

			$output = array();

			if($results = $this->Message->query("SELECT * FROM (SELECT id,radius, ( 6371 * acos( cos( radians($latitude) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($longtitude) ) + sin( radians($latitude) ) * sin( radians( lat ) ) ) ) AS distance FROM messages ORDER BY distance) AS Message WHERE distance < radius;")){
				
				$counter = 0;
				$output['result'] = array();
				foreach($results as $result){
					array_push($output['result'] ,$this->Message->findById($result['Message']['id']));
					$output['result'][$counter]['Message']['distance'] = $result['Message']['distance'];
					$counter++;
				}

			}
			else if($results == array()){
				$output['notice'] = ("no message has been for your distance!");
			}
			else
				$output['error'] = ("an error occured in retrieving message!");

			// generating new token, false if token not updated
			$temp = $this->generate_new_token($user_id);
			$output['return'] = $temp['return'];
			// whether or not it is false or generated, it is generated :)

			return $this->render_response($output);
		}
	}

	public function submit(){
		if($this->request->is(array("post" , "put"))){
			//validation of token
			$token = $this->request->data['Message']['access_token'];

			$user_id = $this->_check($token);
			if(is_numeric($user_id));
			else
				return $user_id; // a response object stating occurence of error
			//finish validating token

			$this->request->data['Message']['user_id'] = $user_id;
			$this->Message->create();

			$output = array();

			if($this->Message->save($this->request->data))
				$output['notice'] = ("Your message has been submitted!");
			else
				$output['error'] = ("An error occured while submitting the new post!");

			// generating new token, false if token not updated
			$temp = $this->generate_new_token($user_id);
			$output['return'] = $temp['return'];
			// whether or not it is false or generated, it is generated :)

			return $this->render_response($output);
		}
	}

	public function comment(){ 
		// deal with comment submitting or allowing ppl to submit comments
		if($this->request->is(array("put" , "post"))){ //user has submitted a comment

			$token = $this->request->data['Message']['access_token'];

			$user_id = $this->_check($token);
			if(is_numeric($user_id));
			else
				return $user_id; // a response object stating occurence of error
			//finish validating token

			$this->request->data['Comment']['user_id'] = $user_id;
			$id = $this->request->data["Comment"]["message_id"];
			$post = $this->Message->findById($id);
			$output = array();

			if(!$post)
				$output['error'] = "Post with the id " . $id . " cannot be found!";
			else {
				if($this->Message->Comment->save($this->request->data))
					$output['notice'] = "Your comment has been submitted!";
				else
					$output['error'] = "An error occured in saving comment!";
			}

			// generating new token, false if token not updated
			$temp = $this->generate_new_token($user_id);
			$output['return'] = $temp['return'];
			// whether or not it is false or generated, it is generated :)

			return $this->render_response($output);
		}

	}

	public function my_messages(){
		if($this->request->is(array("put" , "post"))){ //user has submitted a comment

			$token = $this->request->data['Message']['access_token'];

			$user_id = $this->_check($token);
			if(is_numeric($user_id));
			else
				return $user_id; // a response object stating occurence of error
			//finish validating token

			$output = array();
			$posts = $this->Message->find("all" , array(
				"conditions" => array(
					"user_id" => $user_id
				)
			));

			if($posts) {
				$output['result'] = $posts;
			}
			else if($posts == array()) {
				$output['notice'] = 'you have not posted any stuff';
			}
			else {
				$output['error'] = "error in retrieving posts!";
			}

			// generating new token, false if token not updated
			$temp = $this->generate_new_token($user_id);
			$output['return'] = $temp['return'];
			// whether or not it is false or generated, it is generated :)

			return $this->render_response($output);
		}
	}
}