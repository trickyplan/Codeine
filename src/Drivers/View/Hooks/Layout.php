<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Layout Parslet 
     * @package Codeine
     * @version 8.x
     */

    setFn ('Parse', function ($Call)
    {
        $Call['Parsed'] = F::Run('Text.Regex', 'All',
            [
                'Pattern' => $Call['Layout Pattern'],
                'Value' => $Call['Value']
            ]);

        if ($Call['Parsed'])
        {
            foreach ($Call['Parsed'][1] as $IX => &$Match)
            {
                list($Asset, $ID) = F::Run('View', 'Asset.Route', ['Value' => $Match]); // FIXME
                $Match = F::Run ('View', 'Load', $Call, ['Scope' => $Asset, 'ID' => $ID]);
            }

            $Call['Value'] = str_replace ($Call['Parsed'][0], $Call['Parsed'][1], $Call['Value']);
        }

        return $Call;
    });