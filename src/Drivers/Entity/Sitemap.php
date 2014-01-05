<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Generate', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call);

        $Elements = F::Run('Entity', 'Read', $Call, ['Fields' => ['Slug'], 'Partial' => true]);

        $Data = [];

        if (!isset($Call['Slug']))
            $Call['Slug'] = strtolower($Call['Entity']);

        if (count($Elements) > 0)
            foreach ($Elements as $Element)
                if (isset($Element['Slug']))
                    $Data[] = $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/'.$Call['Slug'].'/'.$Element['Slug']; // FIXME!

        return $Data;
    });