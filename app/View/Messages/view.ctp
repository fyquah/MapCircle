<h1>Tell me about your current location</h1>

<?php 
	echo $this->Form->create("Message" , array("action" => "view.json"));
	echo $this->Form->input("Message.id" , array("type" => "text" , "required" => true));
	echo $this->Form->end("retrieve message json");
?>