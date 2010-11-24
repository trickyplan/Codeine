<?php

    function F_Stringer_Generate($Data)
    {
        $Allowed = 'ABCDEFGHKMNPQRSTUVWXYZabcdefghkmnpqrstuvwxyz23458';
        $SZAllow = sizeof($Allowed);
        $Output = '';
        for ($a = 0; $a< 20; $a++)
            $Output.= mb_substr($Allowed,rand(0,$SZAllow),1);
        return $Data.$Output;
    }