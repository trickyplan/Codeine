<?php

    function F_Numbers_Generate($Args)
    {
        $password = '';
        $length = $Args['Length'];

        for ($a = 0; $a<$length; $a++)
        $password.= rand(0, 9);

        return $password;
    }
