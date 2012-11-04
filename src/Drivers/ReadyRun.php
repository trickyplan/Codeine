<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Scan', function ($Call)
    {
        // TODO Realize "Do" function
        $DOM = new DOMDocument();

        $DOM->loadHTML($Call['Output']);

        $Links = $DOM->getElementsByTagName('a');

        foreach ($Links as $Link)
            $Hrefs[] = $Link->getAttribute('href');

        $Hrefs = array_unique($Hrefs);

        unset($Hrefs[array_search('#', $Hrefs)]);
        unset($Hrefs[array_search('/logout', $Hrefs)]);

        foreach ($Hrefs as &$Href)
        {
            $Call['SID'] = $_COOKIE['SID'];
            $Call['URL'] = $Href;
            $Call['Environment'] = F::Environment();

            F::Run('Code.Run.Delayed', 'Run', [
                'Run' =>
                [
                    'Service' => 'ReadyRun',
                    'Method'  => 'Prefetch',
                    'Call' => $Call
                ]
            ]);
        }

        return $Call;
    });

    setFn('Prefetch', function ($Call)
    {
        $_COOKIE['SID'] = $Call['SID'];

        F::Run('IO', 'Write', ['Storage' => 'Ready Run', 'Scope' => 'RR',
            'Data' =>
            [
                'ID'  => $Call['SID'].'.'.$Call['URL'],
                'Page' => F::Run('Code.Flow.Front', 'Run', ['Run' => $Call['URL']])['Output']]]);

        return $Call['URL'].' prefetched';
    });

    setFn('Check', function ($Call)
    {
        $RR = F::Run('IO', 'Read',
            [
                'Storage' => 'Ready Run',
                'Scope' => 'RR',
                'Where' =>
                [
                    'ID' => $_COOKIE['SID'].'.'.$_SERVER['REQUEST_URI']
                ]
            ]);

        if (!empty($RR))
        {
            $Call['Output'] = $RR[0]['Page'];
            $Call['SkipRun'] = true;
        }

        return $Call;
    });
