<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Identificate', function ($Call)
    {
        $URL = 'https://www.facebook.com/dialog/oauth?client_id='.$Call['FB']['AppID'].'&scope='
            .$Call['FB']['Rights'].'&redirect_uri='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/oauth/FB').'&response_type=code';

        $Call = F::Run('System.Interface.Web', 'Redirect', $Call, ['Location' => $URL]);

        return $Call;

    });

    setFn('Authenticate', function ($Call)
    {
        $URL = 'https://graph.facebook.com/oauth/access_token?client_id='.$Call['FB']['AppID']
            .'&client_secret='.$Call['FB']['Secret'].'&code='.$Call['Request']['code']
            .'&redirect_uri='.urlencode($Call['HTTP']['Proto'].$Call['HTTP']['Host']).'/oauth/FB';

        parse_str(F::Run('IO', 'Read',
            [
                'Storage' => 'Web',
                'Where' => $URL
            ])[0], $Result);

        if (isset($Result['access_token']))
            F::Run('Entity','Update',
                [
                    'Entity' => 'User',
                    'One' => true,
                    'Where' => $Call['Session']['User']['ID'],
                    'Data' => ['FB' => ['Auth' => $Result['access_token']]]]);

        $Call = F::Hook('afterFBAuthenticate', $Call);

        return $Call;
    });

    setFn('Run', function ($Call)
    {
        if (isset($Call['Data']['FB']['Auth']))
        {
            $URL = 'https://graph.facebook.com'.$Call['Method'].'?&access_token='.$Call['Data']['FB']['Auth'];

            $Result = json_decode(F::Run('IO', 'Read',
                   [
                       'Storage' => 'Web',
                       'Where' =>
                       $URL
                   ])[0], true);
        }
        else
            $Result = null;

        if (isset($Call['Return Key']) && isset($Result[$Call['Return Key']]))
            $Result = $Result[$Call['Return Key']];
        return $Result;
    });

    setFn('Annulate', function ($Call)
    {
        F::Run('Entity','Update',
                [
                    'Entity' => 'User',
                    'One' => true,
                    'Where' => $Call['Session']['User']['ID'],
                    'Data' => ['FB' => ['Auth' => 0]]]);

        $Call = F::Hook('afterFBAnnulate', $Call);

        return $Call;
    });