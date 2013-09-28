<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.x
     */

     setFn('Make', function ($Call)
     {
         if (!isset($Call['Value']) or !$Call['Value'])
             $Call['Value'] = 1;

         if (isset($Call['Localized']) && $Call['Localized'])
                $Call['Label'] = '<l>'.$Call['Entity'].'.Entity:'.$Call['Key'].'.'.$Call['Value'].'</l>';
            else
                $Call['Label'] = '<l>'.$Call['Entity'].'.Entity:'.$Call['Key'].'</l>';

         return $Call;
     });