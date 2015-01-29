<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
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