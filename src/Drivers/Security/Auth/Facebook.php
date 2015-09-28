<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Output']['Content'][] =
        [
            'Type'  => 'Template',
            'Scope' => 'Security.Auth',
            'ID'    => 'Facebook'
        ];

        return $Call;
    });

    setFn('Identificate', function ($Call)
    {

        return F::Run('System.Interface.HTTP', 'Redirect', $Call,
            [
                'Redirect' => 'https://www.facebook.com/dialog/oauth?'
                .'client_id='.$Call['Facebook']['AppID']
                .'&scope='.$Call['Facebook']['Rights']
                .'&response_type=code'
                .'&redirect_uri='
                .$Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/authenticate/Facebook?BackURL='.urlencode($Call['Request']['BackURL'])
            ]);
    });

    setFn('Authenticate', function ($Call)
    {
        $URL = 'https://graph.facebook.com/oauth/access_token';

        $Result = F::Run('IO', 'Read',
             [
                 'Storage'  => 'Web',
                 'Where'    => $URL,
                 'Data'     =>
                 [
                     'client_id'     => $Call['Facebook']['AppID'],
                     'client_secret' => $Call['Facebook']['Secret'],
                     'code'          => $Call['Request']['code'],
                     'redirect_uri'  => $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/authenticate/Facebook?BackURL='.urlencode($Call['Request']['BackURL'])
                 ]
             ]);

        $Result = array_pop($Result);
        parse_str($Result, $Result);

        if (isset($Result['access_token']))
        {
            $Facebook = F::Run('Code.Run.Social.Facebook', 'Run',
                    [
                        'Method'    => '/me',
                        'Call'      =>
                        [
                            'access_token'  => $Result['access_token']
                        ]
                    ]);

            if (isset($Call['Session']['User']['ID']))
            {
                $Call['User'] = F::Run('Entity', 'Read', $Call,
                [
                    'Entity' => 'User',
                    'One'    => true,
                    'Where'  => $Call['Session']['User']['ID']
                ]);

                $Call['Merge']['Social'] = 'Facebook';
                $Call['Merge']['ID'] = $Facebook['id'];
                $Call = F::Hook('socialMerge', $Call);
                if (isset($Call['Merge']['Updated']))
                    $Updated = $Call['Merge']['Updated'];
            }
            else
                $Call['User'] = F::Run('Entity', 'Read', $Call,
                [
                    'Entity' => 'User',
                    'One'    => true,
                    'Sort'   =>
                    [
                        'ID' => true
                    ],
                    'Where'  =>
                    [
                        'Facebook.ID' => $Facebook['id']
                    ]
                ]);

            if (empty($Call['User']))
            {
                $Call['User'] = F::Run('Entity', 'Create', $Call,
                [
                    'Entity' => 'User',
                    'One'    => true,
                    'Data'  =>
                    [
                        'Facebook' =>
                        [
                            'Active' => true,
                            'ID'     => $Facebook['id'],
                            'Auth'   => $Result['access_token'],
                            'Expire' => time()+$Result['expires']
                        ],
                        'Status' => 1
                    ]
                ]);
            }

            $Call = F::Hook('afterFacebookIdentification', $Call);

            $Updated = $Call['User'];
            $Updated['Facebook'] =
                [
                    'Active' => true,
                    'ID'     => $Facebook['id'],
                    'Auth'   => $Result['access_token'],
                    'Expire' => time()+$Result['expires'],
                    'Logged' => time()
                ];
            
            if (isset($Call['User']['Facebook']['LoginCount']))
                $Call['User']['Facebook']['LoginCount']++;
            else
                $Call['User']['Facebook']['LoginCount'] = 1;

                $Updated['Facebook']['LoginCount'] = $Call['User']['Facebook']['LoginCount'];

                if ($Updated['Facebook']['LoginCount'] == 1)
                    $Call = F::Hook('Facebook.FirstLogin', $Call);

            foreach ($Call['Facebook']['Mapping'] as $FacebookField => $CodeineField)
                if (isset($Facebook[$FacebookField]) && !empty($Facebook[$FacebookField]))
                    $Updated = F::Dot($Updated, $CodeineField,$Facebook[$FacebookField]);

            F::Run('Entity', 'Update',
                [
                    'Entity' => 'User',
                    'Where'  => $Call['User']['ID'],
                    'Data'   => $Updated
                ]);
        }

        $Call = F::Hook('afterFacebookAuthenticate', $Call);

        return $Call;
    });

    setFn('Annulate', function ($Call)
    {
        F::Run('Entity', 'Update', $Call,
        [
            'Entity' => 'User',
            'Where'  => $Call['Session']['User']['ID'],
            'Data'   =>
            [
                'Facebook' => -1
            ]
        ]);

        return $Call;
    });
