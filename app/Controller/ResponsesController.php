<?php
class ResponsesController extends AppController {
	public function index(){

	}

	public function reply(){ // responding to a respond!

		if($this->request->is("post")){
			$token = $this->request->data['User']['access_token'];

			$user_id = $this->_check($token);
			if(is_numeric($user_id));
			else
				return $user_id;

			$this->request->data['Reply']['user_id'] = $user_id;
			$response_id = $this->request->data['Reply']['response_id'];
			$output = array();
			$post = $this->Response->findByid($this->request->data['Reply']['response_id'] , array("fields" => "message_id"));

			if(!$post){
				$output['error'] = "invalid response_id";
			}
			else {
				$message_id = $post['Response']['message_id'];
				if($this->Response->Reply->save($this->request->data)){
					$reply = $this->Response->Reply->findByid($this->Response->Reply->id);
					$output['notice'] = "Your reply has been submitted!";
					$this->firebase->push("/messages/" . $message_id . "/responses/" . $response_id . "/replies/" , $reply);
				}
			}
			
			$temp = $this->generate_new_token($user_id);
			$output['return'] = $temp['return'];

			return $this->render_response($output);
		}

	}
}