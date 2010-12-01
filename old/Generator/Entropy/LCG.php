<?php

function F_LCG_Random($Args)
    {
        return ($Args["Min"]+round(lcg_value()*($Args["Max"]-$Args["Min"])));
    }