<?php
print_r($var3);
echo "<h1>The message is : </h1>";

echo $this->Form->create("Message");
echo $this->Form->input("Comment.content");
echo $this->Form->end("Submit Comment");