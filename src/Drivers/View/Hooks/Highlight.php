<?php

    /* Codeine
     * @author BreathLess
     * @description <k> tag 
     * @package Codeine
     * @version 7.x
     */

    setFn('Parse', function ($Call)
    {
        if (isset($Call['Query']))
        {
            $Call['Parsed'] = F::Run('Text.Regex', 'All',
            [
                'Pattern' => $Call['Query'],
                'Value' => $Call['Output']
            ]);

            foreach ($Call['Parsed'][0] as $IX => $Match)
                $Call['Output'] = str_replace($Match, '<strong>'.$Match.'</strong>', $Call['Output']);
        }

        return $Call;
    });