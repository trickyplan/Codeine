<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */
    include_once 'phpQuery.php';

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
                    elseif (isset($Rule['Attr']))
                        $Value = preg_replace ('/\\s{2,}|\\s{2,}$/Ssm', "\n", pq($Element)->attr($Rule['Attr']));
                    else
                        $Value = preg_replace ('/\\s{2,}|\\s{2,}$/Ssm', "\n", pq($Element)->html());

                    if (empty($Value))
                        F::Log($Key.' not defined', LOG_INFO);
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