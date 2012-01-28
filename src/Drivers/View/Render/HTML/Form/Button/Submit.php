<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.0
     */

     self::setFn('Make', function ($Call)
     {
         return F::Run ('Engine.Template', 'LoadParsed', $Call,
                        array(
                             'Scope' => 'UI',
                             'ID'    => 'HTML/Form/Button/Submit',
                             'Data'  => $Call
                        ));
     });