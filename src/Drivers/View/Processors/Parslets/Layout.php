<?php

    /* Codeine
     * @author BreathLess
     * @description Layout Parslet 
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Parse', function ($Call)
        {
            foreach ($Call['Parsed'][1] as $Ix => $Match)
                $Call['Output'] = str_replace ($Call['Parsed'][0][$Ix],
                   F::Run ('Engine.Template', 'Load', array('ID' => $Match)),$Call['Output']);

            return $Call['Output'];
        });