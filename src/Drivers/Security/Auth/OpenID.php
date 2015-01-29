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
            'ID'    => 'OpenID'
        ];

        return $Call;
    });

    setFn('Identificate', function ($Call)
    {
        $Page = F::Run('IO', 'Read',
        [
            'Storage' => 'Web',
            'Where'   =>
            [
                'ID' => $Call['Request']['Identity']
            ]
        ]);

        $Page = array_pop($Page);

        $DOM = new DOMDocument('1.0', 'utf-8');
        $DOM->loadHTML($Page);

        $XPath = new DomXPath($DOM);
       	$Result = $XPath->query('.//head/link[@rel="openid2.provider"]');
        $Provider = $Result->item(0)->getAttribute('href');

        $Query =
            [
                'openid.mode'       => 'checkid_setup',
                'openid.sreg.optional' => 'nickname,fullname',
                'openid.identity'   => $Call['Request']['Identity'],
                'openid.assoc_handle' => $Call['Session']['ID'],
                'openid.return_to'  => $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/authenticate/OpenID'
            ];


        $Call = F::Run('System.Interface.HTTP', 'Redirect', $Call, ['Location' => $Provider.'?'.http_build_query($Query)]);

        return $Call;
    });

    setFn('Authenticate', function ($Call)
    {
        $Page = F::Run('IO', 'Read',
        [
            'Storage' => 'Web',
            'Where'   =>
            [
                'ID' => $Call['Request']['openid_identity']
            ]
        ]);

        $Page = array_pop($Page);

        $DOM = new DOMDocument('1.0', 'utf-8');
        $DOM->loadHTML($Page);

        $XPath = new DomXPath($DOM);
       	$Result = $XPath->query('.//head/link[@rel="openid2.provider"]');
        $Provider = $Result->item(0)->getAttribute('href');
        $Query = $Call['Request'];

        $Data = [];

        foreach ($Query as $Key => $Value)
            $Data[str_replace('openid_', 'openid.', $Key)] = $Value;

        $Data['openid.mode'] = 'check_authentication';
        $Result = F::Run('IO', 'Read',
             [
                 'Storage' => 'Web',
                 'Where' =>
                 [
                     'ID' => $Provider.'?'.http_build_query($Data)
                 ]
             ]);

        $Result = array_pop($Result);

        d(__FILE__, __LINE__, $Result);
        die();

        return $Call;
    });