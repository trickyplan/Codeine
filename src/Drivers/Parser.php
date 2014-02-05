<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */
    include_once 'phpQuery.php';

/*    setFn('Traverse', function ($Call)
    {
        F::Log('Scan '.$Call['Directory'], LOG_WARNING);
        $Files = explode(PHP_EOL,
            shell_exec('find '.$Call['Directory'].' -type f  -prune  -print'));

        shuffle($Files);

        $FC = count($Files);

        F::Log($FC.' files found', LOG_WARNING);

        $IX = 1;
        $Count = count($Files);

        $Call['Nodes'] = F::loadOptions('Parser/'.$Call['Schema'])['Nodes'];

        foreach ($Files as $File)
        {
            if (!is_file($File))
                continue;

            F::Log($IX.'/'.$Count.':'.$File.' started.', LOG_WARNING);
            F::Log(round(memory_get_usage()/1024).' kb of RAM consumed', LOG_WARNING);

            $Call['Data'] = [];

            $Call['Markup'] = file_get_contents($File);

            if (!empty($Call['Markup']))
            {
                $Call = F::Run(null, 'Parse', $Call);

                if ($Call['Data']['Percent'] > 33)
                {
                    $Directory = '/var/cache/wines/processed/'.$Call['Schema'].'/'.$Call['Data']['Percent'];

                    if (!is_dir($Directory))
                        mkdir($Directory, 0777, true);

                    $Call['Data']['Source'] = $File;
                    file_put_contents(
                        $Directory.'/'.basename($File).'.json',
                        json_encode($Call['Data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES)
                    );
                }
            }
            echo PHP_EOL;

            if (isset($Call['Data']['Type']))
                $Types[$Call['Data']['Type']] = $Call['Data']['Type'];


            $IX++;
        }

        print_r($Types);

        return $Call;
    });*/

    setFn('Do', function ($Call)
    {
        phpQuery::newDocumentHTML($Call['Markup']);

        $Call['Nodes'] = F::loadOptions('Parser/'.$Call['Schema'])['Nodes'];

        $Data = [];

        $Keys = 0;

        foreach ($Call['Nodes'] as $Key => $Rule)
        {
            phpQuery::each(pq($Rule['Selector']),function($Index, $Element) use (&$Data, $Key, $Rule)
            {
                if (isset($Rule['Text']))
                    $Value = preg_replace ('/\\s{2,}|\\s{2,}$/Ssm', "\n", pq($Element)->text());
                elseif (isset($Rule['Content']))
                    $Value = preg_replace ('/\\s{2,}|\\s{2,}$/Ssm', "\n", pq($Element)->attr('content'));
                else
                    $Value = preg_replace ('/\\s{2,}|\\s{2,}$/Ssm', "\n", pq($Element)->html());

                if (empty($Value))
                    F::Log($Key.' not defined', LOG_ERR);
                else
                {
                    if (isset($Rule['Regex']))
                    {
                        if (preg_match($Rule['Regex'], $Value, $Pockets))
                            $Value = $Pockets[1];
                        else
                            $Value = null;
                    }

                    $Value = trim($Value);

                    F::Log($Key.'.'.$Index.' is '.$Value, LOG_INFO);

                    if (!empty($Value))
                        $Data = F::Dot($Data, $Key.'.'.$Index, $Value);
                }
            });

            $Value = F::Dot($Data, $Key);

            if (null !== $Value)
                $Keys++;

            if (count($Value) == 1)
                $Data = F::Dot($Data, $Key, $Value[0]);
        }

        $Data['Percent'] = floor($Keys/count($Call['Nodes'])*100);

        $Call['Data'] = $Data;

        phpQuery::unloadDocuments();

        return $Call;
    });