<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 2020.x.x
     */

    setFn('Do', function ($Call) {
        $Output = [];

        $Call = F::Merge($Call, F::loadOptions($Call['Entity'] . '.Entity'));

        $Call = F::Hook('beforeRAWList', $Call);

        $Elements = F::Run('Entity', 'Read', $Call, ['Skip Enum Live' => true]);

        if ($Elements !== null) {
            foreach ($Elements as $Element) {
                if (isset($Element[$Call['Primary']])) {
                    $Output[$Element[$Call['Primary']]] = F::Dot($Element, $Call['Key']);
                }
            }
        }

        $Call = F::Hook('afterRAWList', $Call);

        return $Output;
    });

    setFn('Call', function ($Call) {
        $Call['Output'] = [];

        $Call = F::Merge($Call, F::loadOptions($Call['Entity'] . '.Entity'));

        $Call = F::Hook('beforeRAWList', $Call);

        $Elements = F::Run('Entity', 'Read', $Call, ['Skip Enum Live' => true]);

        if ($Elements !== null) {
            foreach ($Elements as $Element) {
                if (isset($Element[$Call['Primary']])) {
                    $Call['Output'][$Element[$Call['Primary']]] = F::Dot($Element, $Call['Key']);
                }
            }
        }

        $Call = F::Hook('afterRAWList', $Call);

        return $Call;
    });
