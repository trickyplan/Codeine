<?php

function F_I18N_Process ($Data)
    {
        return $Data;
        $Pockets = array();
        
        if (preg_match_all('@<l>(.*)<\/l>@SsUu', $Data, $Pockets))
        {
            $From = array();
            $To = array();
            
            if (Client::$Language == 'ru_RU')
                $Language = '["ru_Ru"]';
            else
                $Language = '["ru_Ru","'.Client::$Language.'"]';

            $RAW = Data::Read ('_Language',
                '{"Fields":["I","K","V"],"Where":{"k1 IN (v1) and k2 in (v2)":{"I":'.$Language.', "K":'.json_encode($Pockets[1]).'}}}');

            if (is_array($RAW))
            {
                foreach($RAW as $Row)
                    $I18N[$Row['I']][$Row['K']] = $Row['V'];
            }
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
            
            $Data = str_replace($From, $To, $Data);
        }

        return $Data;
    }