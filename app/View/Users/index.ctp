<?php

echo $this->html->link("Obtain user access token" , array("controller" => "users" , "action" => "login"));
echo "<br />";
echo $this->html->link("Retrieve messages for an area" , array("controller" => "messages" , "action" => "retrieve"));
echo "<br />";
echo $this->html->link("Retrieve current user messages" , array("controller" => "messages" , "action" => "my_messages"));
echo "<br />";
echo $this->html->link("Broadcast messages" , array("controller" => "messages" , "action" => "submit"));
echo "<br />";
echo $this->html->link("Comment on messages" , array("controller" => "messages" , "action" => "comment"));
echo "<br />";
echo $this->html->link("View messages" , array("controller" => "messages" , "action" => "view"));
echo "<br />";
echo $this->html->link("Create user" , array("controller" => "users" , "action" => "signup"));
echo "<br />";