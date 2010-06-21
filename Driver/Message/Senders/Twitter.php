<?php

    function F_Twitter_Mount ($Args)
    {
        return $Args;
    }
    
    function F_Twitter_Send($Args)
    {
          $Context = stream_context_create(array(
            'http' => array(
              'method'  => 'POST',
              'header'  => sprintf("Authorization: Basic %s\r\n", base64_encode($Args['Point']['DSN']['Username'].':'.$Args['Point']['DSN']['Password'])).
                           "Content-type: application/x-www-form-urlencoded\r\n",
              'content' => http_build_query(array('status' => $Args['Message'])),
              'timeout' => 10,
            ),
          ));
          $Return = file_get_contents('http://twitter.com/statuses/update.xml', false, $Context);

        return false !== $Return;
    }