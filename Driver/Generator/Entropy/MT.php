<?php

    function F_MonteCarlo_Random($Args)
    {
        return mt_rand($Args['Min'],$Args['Max']);
    }