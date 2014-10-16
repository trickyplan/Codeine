<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read', $Call);

        if (empty($Elements))
            $Call['Output']['Content'] = null;
        else
            foreach ($Elements as $Element)
                $Call['Output']['Content'][] = $Element;

        return $Call;
    });