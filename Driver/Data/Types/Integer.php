<?php

    $Validate = function ($Args)
    {  
        return is_integer($Args['Value']);
    };

    $Input = function ($Args)
    {
        return $Args['Value'];
    };

    $Output = function ($Args)
    {
        return $Args['Value'];
    };