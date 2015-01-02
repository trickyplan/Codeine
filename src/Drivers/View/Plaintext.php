<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 8.x
     */

    setFn('Render', function ($Call)
    {
        if (is_array($Call['Output']))
        {
            $Call = F::Hook('beforePipeline', $Call);

                foreach ($Call['Output']['Content'] as $Key => $Widget)
                    if(is_array($Widget))
                    {
                        if (isset($Widget['Type']))
                            $Call['Output']['Content'][$Key] =
                                F::Run('View.Plaintext.Widget.' . $Widget['Type'], 'Make', $Widget)['Value'];
                        else
                            $Call['Output']['Content'][$Key] = implode("\t", $Widget);
                    }

                    else
                        $Call['Output']['Content'][$Key] = $Widget;

                $Call['Output'] = implode(PHP_EOL, $Call['Output']['Content']);

            $Call = F::Hook('afterPipeline', $Call);
        }

        return $Call;
    });
