<?php

echo $this->Form->create("Message" , array("action" => "submit.json"));
echo $this->Form->input("message");
echo $this->Form->input("lat");
echo $this->Form->input("lng");
echo $this->Form->input("radius");
echo $this->Form->input("user_id" , array("type" => "text"));
echo $this->Form->end("Submit Message");
