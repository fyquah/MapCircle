<?php

echo $this->Form->create("Message" , array("action" => "comment.json"));
echo $this->Form->input("Comment.content");
echo $this->Form->input("Comment.message_id" , array("type" => "text" , "label" => "message id to be commented"));
echo $this->Form->end("Submit Comment");