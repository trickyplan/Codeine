<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Test = F::loadOptions($Call['Test']);

        if (isset($Test['Tests']))
            foreach ($Test['Tests']['Suites'] as $SuiteName => $Suite)
            {
                $Call['Output']['Content'][] = '<h2>'.$SuiteName.'</h2>';
                foreach ($Suite as $CaseName => $Case)
                {
                    $Call['Output']['Content'][] = '<h4>'.$CaseName.'</h4>';

                    $Result = F::Live($Case['Run']);

                    if ($Result == $Case['Result']['Equal'])
                        $Call['Output']['Content'][] = '<div class="alert alert-success">Test passed.</div>';
                    else
                    {
                        $Call['Output']['Content'][] = '<div class="well">Expected result: '.$Case['Result']['Equal'].'</div>';

                    if (isset($Result['Output']['Content']))
                        $Result = print_r($Result['Output']['Content'], true);

                    $Call['Output']['Content'][] = '<div class="well">Actual result: '.$Result.'</div>';

                        $Call['Output']['Content'][] = '<div class="alert alert-danger">Test failed.</div>';
                    }
                }
            }
        else
            $Call = F::Hook('NotFound', $Call);

        return $Call;
    });