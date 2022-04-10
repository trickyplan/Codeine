<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description HTML Textfield Driver
     * @package Codeine
     * @version 8.x
     */

    setFn('Make', function ($Call) {
        if (isset($Call['Value'])) {
            $Call['Value'] = (bool)$Call['Value'];
        } else {
            $Call['Value'] = false;
        }

        $Call['Checked'] = $Call['Value'];

        if (isset($Call['Entity'])) {
            if (isset($Call['Localized']) && $Call['Localized']) {
                $Call['Label'] = $Call['Entity'] . '.Entity:' . $Call['Key'] . '.Label';
            } else {
                $Call['Label'] = $Call['Entity'] . '.Entity:' . $Call['Key'];
            }
        }

        return F::Run(
            'View.HTML.Widget.Base',
            'Make',
            $Call,
            [
                'Tag' => 'input',
                'Type' => 'checkbox'
            ]
        );
    });
