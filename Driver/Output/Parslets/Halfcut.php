<?php

    function F_HalfCut_Parse ($Inputs)
    {
        $Words = explode(' ',$Inputs);
        $DID = uniqid('HCut');
        if (sizeof($Words) > 20)
            return mb_substr($Inputs, 0, mb_strpos($Inputs, $Words[20])).'<span style="font-weight: bold;" class="Action" action="$(\'#'.$DID.'\').fadeIn(\'fast\');$(this).replaceWith(\'\')"> <l>UI:ReadMore</l>&hellip; </span>'.
                '<span id="'.$DID.'" class="hide">'.mb_substr($Inputs, mb_strpos($Inputs, $Words[20])).'</span>';
        else
            return $Inputs;
    }
    
    