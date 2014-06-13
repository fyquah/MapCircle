<h1>Tell me about your current location</h1>
<?php
if(isset($var3))
	print_r($var3);
echo $this->Form->create("Message" , array("action" => "retrieve.json"));
echo $this->Form->input("Message.lat");
echo $this->Form->input("Message.lng");
echo $this->Form->end("Retrieve Messages");
?>
<br />
<?php echo $this->Html->link("Click here to broadcast a message" , array("controller"=> "messages" , "action" => "submit")) ?>