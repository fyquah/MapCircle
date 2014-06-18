<?php
class ResponsesController extends AppController {
	public function index(){

	}

	public function respond(){ // responding to a respond!

		if($this->request->is("post")){
			$token = $this->request->data['User']['access_token'];

			$user_id = $this->_check($token);
			if(is_numeric($user_id));
			else
				return $user_id;

			$output = array();
			$save = array();

			$primary_response = $this->Response->findByid(intval($this->request->data['SecondaryResponse']['response_id']));

			if($primary_response){

				$save['content'] = $this->request->data['SecondaryResponse']['content'];
				$save['response_id'] = $this->request->data['SecondaryResponse']['response_id'];
				$save['user_id'] = $user_id;

				$this->Response->SecondaryResponse->create();

				if($this->Response->SecondaryResponse->save($save)){
					$output['notice'] = "success in adding response to response!";

					$query = $this->Response->SecondaryResponse->findByid(intval($this->Response->SecondaryResponse->id) , array("SecondaryResponse.id" , "SecondaryResponse.content" , 'SecondaryResponse.response_id' , 'SecondaryResponse.created' , 'SecondaryResponse.modified' , 'SecondaryResponse.user_id' , "User.id" , "User.first_name" , "User.last_name"));
					$output['lol'] = $query;
					$this->firebase->push("/messages/" . $primary_response['Response']['message_id'] . "/Response/" . $primary_response['Response']['firebase_id'] . "/SecondaryResponse/" ,$query);
				}
				else {
					$output['error'] = "an error occured!";
				}
			}
			else {
				$output['error'] = "Response is not found";
			}

			$temp = $this->generate_new_token($user_id);
			$output['return'] = $temp['return'];

			return $this->render_response($query);
		}

	}
}