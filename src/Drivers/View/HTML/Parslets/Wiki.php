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
            $Call['Output'] = str_replace($Call['Parsed'][0][$Ix],
                '<img src="http://wikipedia.org/favicon.ico" class="icon"/> <a target="_blank" href="http://ru.wikipedia.org/wiki/'.$Match.'">'.$Match.'</a>', $Call['Output']);

          return $Call;
     });