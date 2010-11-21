<?php

    function F_PassportExpire_Check($Args)
    {
        $Time = time();
        $Pass = $Args['Passport'];
        $Born = $Args['Born'];
        $Age  = floor ((time() - $Born)/31622400);

        $Y20 = $Born + 20*31449600;
        $Y45 = $Born + 45*31449600;

        if (($Pass<$Y20&&$Age>=20) or ($Pass<$Y45&&$Age>=45))
            return 'False';
        else
            return 'True';
    }