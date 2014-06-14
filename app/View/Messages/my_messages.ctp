<?php
echo $this->Form->create("Message");
echo $this->Form->input("access_token" , array("requried" => "true"));
echo $this->Form->end("Get my messages!");
