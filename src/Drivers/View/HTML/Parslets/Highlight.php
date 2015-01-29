<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Skype Parslet
     * @package Codeine
     * @version 6.0
     */

     setFn('Parse', function ($Call)
     {
          foreach ($Call['Parsed'][2] as $Ix => $Match)
            $Call['Output'] = str_replace($Call['View']['Highlight'], '<strong>'.$Call['View']['Highlight'].'</strong>', $Call['Output']);

          return $Call;
     });