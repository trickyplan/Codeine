<?php

    function F_Role_Fusion($Args)
    {
        if (preg_match_all('@<role name="(.*)">(.*)</role>@SsUu', $Args['Structure'], $Matches))
        {
            $IC = 0;
            if (Client::$Authorized)
                $Roles = Client::$Agent->Get('Role', false);
            else
                $Roles = array();

                foreach($Matches[0] as $IX => $Match)
                {
                    if (in_array($Matches[1][$IX], $Roles))
                    {
                        $OK[$IC]   = $Match;
                        $OV[$IC++] = $Matches[2][$IX];
                    }
                    else
                    {
                        $OK[$IC]   = $Match;
                        $OV[$IC++] = '';
                    }
                }

           $Args['Structure'] = str_replace($OK, $OV, $Args['Structure']);
        }

        return $Args['Structure'];
    }