<?php

echo $this->Form->create("Message" , array("action" => "submit"));
echo $this->Form->input("Message.message");
echo $this->Form->input("Message.lat");
echo $this->Form->input("Message.lng");
echo $this->Form->input("Message.radius");
echo $this->Form->input("Message.period" , array("label" => "Period of broadcasting message in minutes"));
echo $this->Form->input("User.access_token" , array("requried" => "true" , "type" => "text"));
echo $this->Form->end("Submit Message");
print_r($var3);