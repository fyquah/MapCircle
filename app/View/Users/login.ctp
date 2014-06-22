<h1>Use this page to generate user access tokens</h1>
<?php
echo $this->Form->create('User');
echo $this->Form->input("username");
echo $this->Form->input("password");
echo $this->Form->end("Log in!");