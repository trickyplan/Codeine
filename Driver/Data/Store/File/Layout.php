<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: LayoutFS Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 0.2
     * @date 18.11.10
     * @time 5:37
     */

    self::Fn('Open', function ($Call)
    {
        if (isset($Call['Options']['DSN']))
            return $Call['Options']['DSN'];
        else
            return 'Default';
    });

    self::Fn('Read', function ($Call)
    {
        Code::On('Data.FS.Query',$Call);
        
        if (!is_array ($Call['Where']['ID']))
            $IDs = array ($Call['Where']['ID']);
        else
            $IDs = $Call['Where']['ID'];

        $Layout = '';
        $Candidates = array();

        $SZC = count($IDs);

        $IC = 0;

        for ($a = 0; $a<$SZC; $a++)
            {
                $Candidates[$IC++] = Root.$Call['Options']['DSN'].$Call['Options']['Scope'].DS.$IDs[$a].'.html';
                $Candidates[$IC++] = Engine.$Call['Options']['DSN'].$Call['Options']['Scope'].DS.$IDs[$a].'.html';
            }

        for ($a = 0; $a<$IC; $a++)
        {
            if (file_exists($Candidates[$a]))
                return file_get_contents($Candidates[$a]);
        }

        Code::On('Layout.Read.Candidates.No', $Call);
        // TODO Limit on Candidates
        return '<content/>';
    });
