<h1>Tell me about your current location</h1>
<?php
print_r($message);
?>
<h2>Message</h2>
<p><?php echo "from " . $message['Message']
<p><?php echo $message['Message']["message"] ;?></p>
<h2>Comments</h2>
<?php foreach($message['Comment'] as $comment)
	echo "<p>" . $comment['content'] . "</p>";
?>