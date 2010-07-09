<?php

// This is expirimental driver, only for testing.
function F_Anomal_Random ($Args)
    {
        if (mt_rand(0, mt_rand(0, mt_rand(0, 12))) == 2)
            return mt_rand($Args["Min"]*mt_rand(1, 3),$Args["Max"]/mt_rand(1, 3));
        else
            return mt_rand($Args["Min"],$Args["Max"]);
    }