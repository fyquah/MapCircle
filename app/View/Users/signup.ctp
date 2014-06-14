<?php

echo $this->Form->create("User" , array("action" => "signup"));
echo $this->Form->input("username");
echo $this->Form->input("password");
echo $this->Form->input("email");
echo $this->Form->input("first_name" , array("type" => "text"));
echo $this->Form->input("last_name" , array("type" => "text"));
echo $this->Form->end("Sign up to awesomeness!");
