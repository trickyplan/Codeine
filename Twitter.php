<?php

include 'Package/twitteroauth/twitteroauth.php';

define("CONSUMER_KEY", 'dNZY37c7SMFTXzTJy16RtQ');
define("CONSUMER_SECRET", 'Ed3HSH24foI5j288se9LY1j9t5EqQTtDkHVK8HbYk');
define("OAUTH_TOKEN", '14557176-DCIvB8Fbvpj7Ku6bjrQdxjEUCvd6zTyl7DD3zji8');
define("OAUTH_SECRET", 'L5EjFgqxjiDq7OkyZBzDDmscmzUlAyCE67J6Rnoc');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);
$content = $connection->get('account/verify_credentials');

$connection->post('direct_messages/new', array('screen_name' => 'Zapix','text' => 'TestPHP'));