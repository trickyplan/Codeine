<?php

    function F_F_Parse ($Input)
    {
       $Args = json_decode($Input, true);
       if (!isset($Args['Driver']))
           $Args['Driver'] = 'Default';

       return Code::E($Args['Namespace'], $Args['Function'], $Args['Args'], $Args['Driver']);
    }
    
    