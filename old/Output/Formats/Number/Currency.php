<?php

    function F_Currency_Format ($Number)
    {
       if ($Number == 0)
           return '?';
       else
           return round($Number,2).' руб.';
    }