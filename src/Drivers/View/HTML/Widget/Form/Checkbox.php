<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.x
     */

     setFn('Make', function ($Call)
     {
         if (isset($Call['Localized']) && $Call['Localized'])
                $Call['Label'] = '<l>'.$Call['Entity'].'.Entity:'.$Call['Key'].'.'.$Call['Value'].'</l>';
            else
                $Call['Label'] = $Call['Value'];

         return $Call;
     });