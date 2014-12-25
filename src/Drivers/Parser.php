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
                                $Value = $Pockets[1];
                            else
                                $Value = null;
                        }

                        $Data = F::Dot($Data, $Key.'.'.$i, $Value);

                    }
                }
                else
                {
                    phpQuery::each(pq($Rule['Selector']),function($Index, $Element) use (&$Data, $Key, $Rule, $Call)
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
                            F::Log($Key.'.'.$Index.' is '.$Value, LOG_INFO);

                            if (isset($Rule['Regex']))
                            {
                                if (preg_match($Rule['Regex'], $Value, $Pockets))
                                    $Value = $Pockets[1];
                                else
                                    $Value = null;
                            }
                            $Value = trim($Value);

                            if (!empty($Value))
                                $Data = F::Dot($Data, $Key.'.'.$Index, $Value);
                        }
                    });
                }

                $Value = F::Dot($Data, $Key);

                if (null !== $Value)
                    $Keys++;

                if (count($Value) == 1)
                    $Data = F::Dot($Data, $Key, $Value[0]);
            }

            $Data['Percent'] = floor($Keys/count($Call['Nodes'])*100);

            $Call['Data'] = $Data;

        $Call = F::Apply('Parser.'.$Call['Schema'], 'Do', $Call);

        phpQuery::unloadDocuments();

        return $Call;
    });

    setFn('File', function ($Call)
    {
        $Markup = file_get_contents($Call['Filename']);
        $Call = F::Run(null, 'Do', $Call, ['Markup' => $Markup]);

        $Call['View']['Renderer'] = ['Service' => 'View.JSON', 'Method' => 'Render'];
        $Call['Output']['Content'][] = $Call['Data'];
        return $Call;
    });