<?php

echo $this->Form->create("Message" , array("action" => "respond"));
echo $this->Form->input("Response.content");
echo $this->Form->input("Response.message_id" , array("type" => "text" , "label" => "message id to be commented"));
echo $this->Form->input("User.access_token" , array("type" => "text" , "required" => true));
echo $this->Form->end("Submit response");