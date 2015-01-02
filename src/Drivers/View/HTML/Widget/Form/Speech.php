<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 8.x
     */

     setFn('Make', function ($Call)
     {
         if (!isset($Call['Subtype']))
             $Call['Subtype'] = 'text';

         return F::Run ('View', 'Load',
                           [
                                'Scope' => 'Default',
                                'ID'    => 'UI/Form/Speech',
                                'Data'  => $Call
                           ]);
     });