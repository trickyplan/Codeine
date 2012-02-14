<?php

    /* Codeine
     * @author BreathLess
     * @description Layout Parslet 
     * @package Codeine
     * @version 7.1
     */

    self::setFn ('Parse', function ($Call)
        {
            foreach ($Call['Parsed'][1] as $Ix => $Match)
                $Call['Output'] = str_replace ($Call['Parsed'][0][$Ix],
                   F::Run ('Engine.Template', 'Load', array('Scope' => 'Layout', 'ID' => $Match)),$Call['Output']);

            return $Call['Output'];
        });