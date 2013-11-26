<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Check', function ($Call)
    {
        $Response = F::Run('IO', 'Write', [
            'Storage' => 'Web',
            'Where' => 'http://'.$Call['Akismet']['Key'].'.rest.akismet.com/1.1/comment-check',
            'User Agent' => 'Codeine/7.x | Akismet/1.0',
            'Data' =>
            [
                'blog' => $Call['HTTP']['Host'],
                'user_ip' => F::Live($Call['IP']),
                'user_agent' => $Call['UA'],
                'referrer' => '',
                'permalink' => $Call['HTTP']['URL'],
                'comment_type' => 'comment',
                'comment_author' =>
                    isset($Call['User']['Title']) ? $Call['User']['Title']: '',
                'comment_author_email' =>
                    isset($Call['User']['EMail']) ? $Call['User']['Title']: '',
/*                'comment_author_url' => $Call['User']['URL'],*/
                'comment_content' => $Call['Value']
            ]
        ]);

        F::Log('Akismet response: '.$Response, LOG_INFO);

        return !($Response === 'false');
    });

    setFn('Calculate', function ($Call)
    {
        if (isset($Call['Data']['User']))
            $Call['User'] = F::Run('Entity', 'Read',
                 [
                     'Entity' => 'User',
                     'Where'  => $Call['Data']['User'],
                     'One'    => true
                 ]);

        return F::Run(null, 'Check', $Call)? 50: 0;
    });