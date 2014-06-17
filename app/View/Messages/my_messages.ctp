<?php
echo $this->Form->create("Message");
echo $this->Form->input("User.access_token" , array("requried" => "true"));
echo $this->Form->end("Get my messages!");
