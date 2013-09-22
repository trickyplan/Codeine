<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.x
     */

    setFn('Render', function ($Call)
    {
        if (is_array($Call['Output']))
        {
            $Call = F::Hook('beforePipeline', $Call);

                foreach ($Call['Output']['Content'] as $Key => $Widget)
                    if(is_array($Widget))
                        $Call['Output']['Content'][$Key] =
                            F::Run('View.Plaintext.Widget.' . $Widget['Type'], 'Make', $Widget)['Value'].PHP_EOL;
                    else
                        $Call['Output']['Content'][$Key] = $Widget.PHP_EOL;

                $Call['Output'] = implode('', $Call['Output']['Content']);

            $Call = F::Hook('afterPipeline', $Call);
        }

        return $Call;
    });
