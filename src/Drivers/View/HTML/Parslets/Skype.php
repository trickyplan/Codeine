<?php

    /* Codeine
     * @author BreathLess
     * @description Skype Parslet
     * @package Codeine
     * @version 6.0
     */

     setFn('Parse', function ($Call)
     {
          foreach ($Call['Parsed'][2] as $Ix => $Match)
            $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], '<a class="skype" href="skype:'.$Match.'">Skype: '.$Match.'</a>', $Call['Output']);

          return $Call;
     });