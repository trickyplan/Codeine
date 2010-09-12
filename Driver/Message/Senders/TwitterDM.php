<?php

    function F_TwitterDM_Mount ($Args)
    {
        include Root.'Package/twitteroauth/twitteroauth.php';

        $connection = new TwitterOAuth(
                Core::$Conf['Keys']['Twitter']['CONSUMER_KEY'],
                Core::$Conf['Keys']['Twitter']['CONSUMER_SECRET'],
                Core::$Conf['Keys']['Twitter']['OAUTH_TOKEN'],
                Core::$Conf['Keys']['Twitter']['OAUTH_SECRET']);
        
        $content = $connection->get('account/verify_credentials');
    }
    
    function F_TwitterDM_Send($Args)
    {
        return $connection->post('statuses/update', array('status' => $Args['Message']));
    }