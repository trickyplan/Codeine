<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.x
     */

     setFn('Make', function ($Call)
     {
         if (($Call['Value'] == $Call['TrueValue']) && !isset($Call['Checked']))
            $Call['Checked'] = true;

         if (isset($Call['Localized']) && $Call['Localized'])
                $Call['Label'] = '<l>'.$Call['Entity'].'.Entity:'.$Call['Key'].'.'.$Call['Value'].'</l>';
            else
                $Call['Label'] = '<l>'.$Call['Entity'].'.Entity:'.$Call['Key'].'</l>';

         return $Call;
     });