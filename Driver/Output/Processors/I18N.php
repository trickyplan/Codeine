<?php

function F_I18N_Process ($Data)
    {
        $Pockets = array();

        $Language = new Object('_Language', Client::$Language);

        if (Client::$UID == $Language->Get('Owner'))
            $CanTranslate = true;
        else
            $CanTranslate = false;

        if (preg_match_all('@<l>(.*)<\/l>@SsUu', $Data, $Pockets))
        {
            $From = array();
            $To = array();
            $Pockets[1] = array_unique($Pockets[1]);
            if (Client::$Language == 'ru_RU')
                $Language = '["ru_Ru"]';
            else
                $Language = '["ru_Ru","'.Client::$Language.'"]';

            $RAW = Data::Read ('_Language',
                '{"Fields":["I","K","V"],"Where":{"k1 IN (v1) and k2 in (v2)":{"I":'.$Language.', "K":'.json_encode($Pockets[1]).'}}}');

            if (is_array($RAW))
            {
                foreach($RAW as $Row)
                    $I18N[$Row['K']][$Row['I']] = $Row['V'];
            }
            
            if ($CanTranslate)
            {
                foreach($Pockets[1] as $IX => $Match)
                {
                    $From[$Match] = $Pockets[0][$IX];
                    $To[$Match] = '<nrl token=\''.$Match.'\'>'.$Match.'</nrl>';

                    if (isset($I18N[$Match]))
                    {
                        if (isset($I18N[$Match][Client::$Language]))
                            $To[$Match] = $I18N[$Match][Client::$Language];
                        elseif (isset($I18N[$Match]['ru_RU']))
                            $To[$Match] = '<nrl token=\''.$Match.'\'>'.$I18N[$Match]['ru_RU'].'</nrl>';
                        else
                            $To[$Match] = '<nrl token=\''.$Match.'\'>'.$Match.'</nrl>';
                    }
                }
            }
            else
            {
                foreach($Pockets[1] as $IX => $Match)
                {
                    $From[$Match] = $Pockets[0][$IX];
                    $To[$Match] = $Match;

                    if (isset($I18N[$Match]))
                    {
                        if (isset($I18N[$Match][Client::$Language]))
                            $To[$Match] = $I18N[$Match][Client::$Language];
                        elseif (isset($I18N[$Match]['ru_RU']))
                            $To[$Match] = $I18N[$Match]['ru_RU'];
                    }
                }
            }
                
            
            $Data = str_replace($From, $To, $Data);
        }

        return $Data;
    }