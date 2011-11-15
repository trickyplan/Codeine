<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('Process', function ($Call)
    {
        $Result = F::Run (array(
                     '_N' => 'Services.Validators.HTML.Services_W3C_HTMLValidator',
                     '_F' => 'Code',
                     'Value' => $Call['Output']));

        if (!$Result['validity'])
        {
            $Call['Output'] = '<h1>Heil Validator!</h1><h2>W3C Nazi angry!</h2>';

            foreach ($Result['errors'] as $Error)
                $Call['Output'] .= $Error->message.'<br/> Line '.$Error->line.': <code>'.$Error->source.'</code>';

        }

        return $Call;
     });