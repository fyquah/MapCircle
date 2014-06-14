<?php

echo $this->Form->create("Message" , array("action" => "submit"));
echo $this->Form->input("message");
echo $this->Form->input("lat");
echo $this->Form->input("lng");
echo $this->Form->input("radius");
echo $this->Form->input("access_token" , array("requried" => "true" , "type" => "text"));
echo $this->Form->end("Submit Message");
