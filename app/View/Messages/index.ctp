<h1>Welcome to the home page</h1>
<?php

echo $this->html->link("Retrieve messages for the area" , array("controller" => "messages", "action" => "retrieve"));
echo "<br />";
echo $this->html->link("Broadcast a message" , array("controller" => "messages", "action" => "submit"));
echo "<br />";
echo $this->html->link("View a post!" , array("controller" => "messages", "action" => "view"));
echo "<br />";
echo $this->html->link("Comment on a post!" , array("controller" => "messages", "action" => "comment"));
echo "<br />";
echo $this->html->link("Click here to view JSON of messages by currently logged in user" , array("controller" => "users", "action" => "mymessages.json"));

echo "<pre>";
print_r($var3);
echo "</pre>";