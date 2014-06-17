<h1>Use this page to log users out</h1>
<?php
echo $this->Form->create('User');
echo $this->Form->input("access_token");
echo $this->Form->end("Log out!");
