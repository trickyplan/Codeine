<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    include_once 'phpQuery/phpQuery.php';

    setFn('Do', function ($Call)
    {
    //    $Call['Markup'] = mb_convert_encoding($Call['Markup'], 'utf-8', 'cp1251');

            $Call = F::loadOptions('Parser/'.$Call['Schema'], null, $Call);

            $Data = $Call['Data'] = [];

            $Keys = 0;

            if (isset($Call['Nodes']))
            {
                if (empty($Call['Nodes'] ))
                    F::Log('Parser Nodes are *empty*', LOG_ERR);
                else
                {
                    F::Log('Loaded *'.count($Call['Nodes']).'* Parser Nodes', LOG_INFO);
                    phpQuery::newDocumentHTML($Call['Markup']);
                    F::Log('Loaded *'.strlen($Call['Markup']).'* bytes of markup', LOG_INFO);

                    foreach ($Call['Nodes'] as $Key => $Rule)
                    {
                        if (isset($Rule['XPath']))
                        {
                            if (isset ($XML))
                                ;
                            else
                            {
                                $DOM = new DOMDocument('1.0', 'utf-8');
                                $DOM->loadHTML($Call['Markup']);

                                $DOMXPath = new DOMXPath($DOM);
                            }

                            $nodes = $DOMXPath->query($Rule['XPath']);
                            foreach ($nodes as $i => $node)
                            {
                                $Value = $node->nodeValue;
                                if (isset($Rule['Regex']))
                                {
                                    if (preg_match($Rule['Regex'], $Value, $Pockets))
                                    {
                                        if (isset($Pockets[1]))
                                            $Value = $Pockets[1];
                                        else
                                            if (isset($Rule['Value']))
                                                $Value = $Rule['Value'];
                                    }
                                    else
                                        $Value = null;
                                }

                                $Data = F::Dot($Data, $Key.'.'.$i, $Value);

                            }
                        }
                        elseif (isset($Rule['Selector']))
                        {
                            phpQuery::each(pq($Rule['Selector']),function($Index, $Element) use (&$Data, $Key, $Rule, $Call)
                            {
                                F::Log('Selector fired '.$Rule['Selector'], LOG_NOTICE);

                                if (isset($Rule['Text']))
                                    $Value = preg_replace ('/\\s{2,}|\\s{2,}$/Ssm', PHP_EOL, pq($Element)->text());
                                elseif (isset($Rule['Attr']))
                                    $Value = preg_replace ('/\\s{2,}|\\s{2,}$/Ssm', PHP_EOL, pq($Element)->attr($Rule['Attr']));
                                else
                                    $Value = preg_replace ('/\\s{2,}|\\s{2,}$/Ssm', PHP_EOL, pq($Element)->html());

                                if (empty($Value))
                                    F::Log($Key.' not defined', LOG_INFO);
                                else
                                {
                                    F::Log($Key.'.'.$Index.' is '.$Value, LOG_INFO);

                                    if (isset($Rule['Regex']))
                                    {
                                        if (preg_match($Rule['Regex'], $Value, $Pockets))
                                        {
                                            if (isset($Pockets[1]))
                                                $Value = $Pockets[1];
                                        }
                                        else
                                            $Value = null;
                                    }

                                    $Value = trim($Value);

                                    if (!empty($Value))
                                        $Data = F::Dot($Data, $Key.'.'.$Index, $Value);
                                }
                            });
                        }
                        elseif (isset($Rule['Regex']))
                        {
                            if (preg_match($Rule['Regex'], $Call['Markup'], $Pockets))
                            {
                                if (isset($Pockets[1]))
                                    $Value = [$Pockets[1]];
                            }
                            else
                                $Value = null;

                            $Data = F::Dot($Data, $Key, $Value);
                        }


                        $Value = F::Dot($Data, $Key);

                        if (null !== $Value)
                            $Keys++;

                        if (count($Value) == 1)
                            $Data = F::Dot($Data, $Key, $Value[0]);

                        $Call['Data'] = $Data;
                    }

                    $Data['Percent'] = floor($Keys/count($Call['Nodes'])*100);
                    $Call = F::Apply('Parser.'.$Call['Schema'], 'Do', $Call);
                    phpQuery::unloadDocuments();
                }
            }
            else
                F::Log('Parser Nodes *not defined*', LOG_ERR);

        return $Call;
    });

    setFn('Discovery', function ($Call)
    {
        foreach ($Call['Parser']['Discovery'] as $Rule)
            if (preg_match($Rule['Match'], $Call['URL']))
                return $Rule['Schema'];

        return null;
    });