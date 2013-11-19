<?php

    /* Codeine
     * @author BreathLess
     * @description Layout Parslet 
     * @package Codeine
     * @version 7.x
     */

    setFn ('Parse', function ($Call)
    {
        $Call['Parsed'] = F::Run('Text.Regex', 'All', $Call,
            [
                'Pattern' => $Call['Layout Pattern']
            ]);

        if ($Call['Parsed'])
        {
            foreach ($Call['Parsed'][1] as $IX => &$Match)
            {
                list($Asset, $ID) = F::Run('View', 'Asset.Route', ['Value' => $Match]);
                $Match = F::Run ('View', 'Load', $Call, ['Scope' => $Asset, 'ID' => $ID]);
            }

            $Call['Value'] = str_replace ($Call['Parsed'][0], $Call['Parsed'][1], $Call['Value']);
        }

        return $Call;
    });