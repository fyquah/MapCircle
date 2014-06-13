<?php

echo $this->Form->create("Message");
echo $this->Form->input("Message.message");
echo $this->Form->input("Message.lat");
echo $this->Form->input("Message.lng");
echo $this->Form->input("Message.radius");
echo $this->Form->input("Message.user_id");
echo $this->Form->end("Submit Message");
