<?php

    function F_CSSFile_Process ($Data)
    {
        if (preg_match_all('@<css>(.*)</css>@SsUu', $Data, $Matches))
        {
            $Sources = array();
            $CSSFiles = array();

            foreach($Matches[0] as $IX => $Match)
            {
                $FN = Server::Locate('CSS',$Matches[1][$IX].'.css');
                $CSSFiles[$FN] = filemtime($FN);
            }

            $CSSHash = Code::E('Process/Hash','Get', implode('',$CSSFiles));
            $CSSHashFN = Server::Path('Temp').'CSS/'.$CSSHash.'.css';

            if (!file_exists(Root.$CSSHashFN))
            {
                foreach ($CSSFiles as $FN => &$Value)
                    $Value = file_get_contents($FN);

                file_put_contents(Root.$CSSHashFN, implode($CSSFiles));
            }

            $Data = str_replace('<place>CSS</place>',
                  '<link type="text/css" rel="stylesheet" href="/'.$CSSHashFN.'" />', $Data);

            $Data = str_replace($Matches[0], '', $Data);
        }

        return $Data;
    }