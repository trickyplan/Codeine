<?php

    function F_JSFile_Process ($Data)
    {
        if (preg_match_all('@<js>(.*)</js>@SsUu', $Data, $Matches))
        {
            $Sources = array();
            $JSFiles = array();

            foreach($Matches[0] as $IX => $Match)
            {
                $FN = Server::Locate('JS',$Matches[1][$IX].'.js');
                $JSFiles[$FN] = filemtime($FN);
            }

            $JSHash = Code::E('Process/Hash','Get', implode('',$JSFiles));
            $JSHashFN = Server::Path('Temp').'JS/'.$JSHash.'.js';

            if (!file_exists(Root.$JSHashFN))
            {
                foreach ($JSFiles as $FN => &$Value)
                    $Value = file_get_contents($FN);

                file_put_contents(Root.$JSHashFN, implode($JSFiles));
            }

            $Data = str_replace('<place>JS</place>',
                  '<script type="text/javascript" src="/'.$JSHashFN.'" ></script>', $Data);

            $Data = str_replace($Matches[0], '', $Data);
        }

        return $Data;
    }