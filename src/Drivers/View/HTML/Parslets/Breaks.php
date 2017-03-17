<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Skype Parslet
     * @package Codeine
     * @version 6.0
     */

     setFn('Parse', function ($Call)
     {
          foreach ($Call['Parsed']['Value'] as $IX => $Match)
                $Call['Output'] = str_replace($Call['Parsed']['Match'][$IX], nl2br($Match), $Call['Output']);

          return $Call;
     });