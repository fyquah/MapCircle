<?php
echo $this->Form->create("Response" , array("action" => "reply"));
echo $this->Form->input("Reply.content");
echo $this->Form->input("Reply.response_id" , array("type" => "text" , "label" => "response_id" , "required" => true));
echo $this->Form->input("User.access_token" , array("type" => "text" , "label" => "access_token" , "required" => true));
echo $this->Form->end("Submit Reply!");