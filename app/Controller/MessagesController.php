<?php
function binary_search($haystack , $needle){
	$lo = 0;
	$hi = count($haystack) - 1;
	$mid;
	while($lo != $hi){
		$mid = intval(($lo+$hi)/2);
		if($haystack[$mid] == $needle) return true;
		else if($haystack[$mid] > $needle) $hi = $mid;
		else if($haystack[$mid] < $needle) $lo = $mid + 1;
	}
	if($haystack[$lo] == $needle) return true;
	else return false;
}

function expired($created_date , $period){
	return TIME() > (strtotime(intval($created_date)) + intval($period));
}

class MessagesController extends AppController {

    public function index($id = NULL){
    	
    }

   //  public function view(){
   //  	if($this->request->is(array("post" , "put"))){

   //  		//validation of token
			// $token = $this->request->data['User']['access_token'];

			// $user_id = $this->_check($token);
			// if(is_numeric($user_id));
			// else
			// 	return $user_id; // a response object stating occurence of error
			// //finish validating token

			// $output = array();

   //  		$id = $this->request->data["Message"]["id"];
   //  		if(empty($id))
   //  			$output['error'] = "did not provide message id";

   //  		else {
   //  			$message = $this->Message->findById($id);
   //  			if(!$message)
   //  				$output['error'] = "The message with id " . $id . "cannot be found!";
   //  			else
   //  				$output['result']  = $message;
   //  		}

   //  		// generating new token, false if token not updated
			// $temp = $this->generate_new_token($user_id);
			// $output['return'] = $temp['return'];
			// // whether or not it is false or generated, it is generated :)

			// return $this->render_response($output);
   //  	}

   //  }

	public function update_location(){
		if($this->request->is(array('post' , 'put'))) { //in json format
			
			//validation of token
			$token = $this->request->data['User']['access_token'];

			$user_id = $this->_check($token);
			if(is_numeric($user_id));
			else
				return $user_id; 
			// a response object stating occurence of error
			// finish validating token

			$longtitude = $this->request->data['Message']['lng'];
			$latitude = $this->request->data['Message']['lat'];

			$output = array();

			if($results = $this->Message->query("SELECT * FROM (SELECT id , created , radius ,user_id ,period, ( 6371 * acos( cos( radians($latitude) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($longtitude) ) + sin( radians($latitude) ) * sin( radians( lat ) ) ) ) AS distance FROM messages ORDER BY distance) AS Message WHERE distance < radius ;")){

				$firebase = new Firebase(FIREBASE_URI); // connect to the firebase server

				$arr = array();					// array of message id to be pushed into server
				$already_have = array();		// array of message id that is already present in inbox
				$flag = false;

				foreach($results as $result)
					if($result['Message']['user_id'] == $user_id)
						array_push($arr , (intval($result['Message']['id'])));

				$temp = $firebase->get('/users/' . $user_id . '/inbox/');

				if($temp == NULL || strtoupper($temp) == 'NULL'){
					for($i = 0 ; $i < count($arr) ; $i++)
						$firebase->push("/users/" . $user_id . "/inbox/" , $arr[$i]);
				}
				else {
					$temp = json_decode($temp);
					foreach($temp as $key => $value)
						array_push($already_have, intval($value));

					sort($already_have);

					for($i = 0 ; $i < count($arr) ; $i++)
						if(!expired($results[$i]['Message']['created'] , $results[$i]['Message']['period'])  && !binary_search($already_have , $arr[$i])){
							$firebase->push('/users/' . $user_id . '/inbox/' , $arr[$i]);
							$flag = true;
						}
				}

				$output['notice'] = $flag?"success : added new message to firebase":"success : no new messages to update";
			}
			else if($results == array()){
				$output['notice'] = "success : no new messages to update";
			}
			else
				$output['error'] = ("an error occured in updating location");

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
			$token = $this->request->data['User']['access_token'];

			$user_id = $this->_check($token);
			if(is_numeric($user_id));
			else
				return $user_id; // a response object stating occurence of error
			//finish validating token

			$latitude = $this->request->data["Message"]["lat"];
			$longtitude = $this->request->data['Message']['lng'];
			$radius = $this->request->data['Message']['radius'];
			$this->request->data['Message']['user_id'] = $user_id;

			$output = array();

			if($this->Message->save($this->request->data)){
				$output['notice'] = ("Your message has been submitted!");
				
				$message = $this->Message->findByid($this->Message->id);
				//push the message into firebase
				$firebase = new Firebase(FIREBASE_URI);
				$firebase->set("/messages/" . $this->Message->id . "/", $message);
				$output['notice'] += " and is added into firebase database";
				//automatically add the message into my_messages
				$firebase->push("/users/" . $user_id . "/outbox/" , intval($this->Message->id));
				$output['notice'] += " and is added into user's firebase outbox";
			}

			else
				$output['error'] = ("An error occured while submitting the new post!");

			// generating new token, false if token not updated
			$temp = $this->generate_new_token($user_id);
			$output['return'] = $temp['return'];
			// whether or not it is false or generated, it is generated :)

			return $this->render_response($output);
		}
	}

	public function respond(){ 
		// allow ppl to submit response
		if($this->request->is(array("put" , "post"))){ //user has submitted a comment

			$token = $this->request->data['User']['access_token'];

			$user_id = $this->_check($token);
			if(is_numeric($user_id));
			else
				return $user_id; 
			// a response object stating occurence of error
			//finish validating token

			$this->request->data['Response']['user_id'] = $user_id;
			$message_id = $this->request->data["Response"]["message_id"];
			$post = $this->Message->findById($message_id);
			$output = array();

			if(!$post)
				$output['error'] = "Post with the id " . $message_id . " cannot be found!";
			else {
				if($this->Message->Response->save($this->request->data)){

					$response = $this->Message->Response->findByid($this->Message->Response->id);

					$output['notice'] = "Your comment has been submitted!";
					$firebase = new Firebase(FIREBASE_URI);
					$firebase->push("/messages/" . $message_id . "/Response/" , $response['Response']);
				}

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

	// public function my_messages(){
	// 	if($this->request->is(array("put" , "post"))){ //user has submitted a comment

	// 		$token = $this->request->data['User']['access_token'];

	// 		$user_id = $this->_check($token);
	// 		if(is_numeric($user_id));
	// 		else
	// 			return $user_id; // a response object stating occurence of error
	// 		//finish validating token

	// 		$output = array();
	// 		$posts = $this->Message->find("all" , array(
	// 			"conditions" => array(
	// 				"user_id" => $user_id
	// 			)
	// 		));

	// 		if($posts) {
	// 			$output['result'] = $posts;
	// 		}
	// 		else if($posts == array()) {
	// 			$output['notice'] = 'you have not posted any stuff';
	// 		}
	// 		else {
	// 			$output['error'] = "error in retrieving posts!";
	// 		}

	// 		// generating new token, false if token not updated
	// 		$temp = $this->generate_new_token($user_id);
	// 		$output['return'] = $temp['return'];
	// 		// whether or not it is false or generated, it is generated :)

	// 		return $this->render_response($output);
	// 	}
	// }
}