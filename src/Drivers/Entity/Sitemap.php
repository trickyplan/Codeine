<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Generate', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read', $Call, ['Fields' => ['Slug'], 'Partial' => true]);

        $Data = [];

        if (count($Elements) > 0)
            foreach ($Elements as $Element)
                if (isset($Element['Slug']))
                    $Data[] = $Call['Host'].'/'.strtolower($Call['Entity']).'/'.$Element['Slug']; // FIXME!

        return $Data;
    });