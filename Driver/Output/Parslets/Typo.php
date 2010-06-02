<?php

    function F_Typo_Parse ($Input)
    {
        $TRs = array('...'=>'&hellip;', '(c)'=>'&copy;', '(r)'=>'&reg;');
        $Output = '';
        $Input = strtr($Input, $TRs);
        $Input = explode("\n", $Input);
        foreach ($Input as $Input)
            if (trim($Input) != '')
                $Output.= '<p>'.$Input.'</p>';
        return $Output;
    }
    
    