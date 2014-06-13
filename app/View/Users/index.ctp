<?php

echo $this->html->link("Click here to login!" , array("controller" => "messages" , "action" => "index"));
echo "<br />";
echo $this->html->link("Click here to sign up!" , array("controller" => "users" , "action" => "signup"));
