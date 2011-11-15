<?php

    /* Codeine
     * @author BreathLess
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Parse', function ($Call)
     {
          foreach ($Call['Parsed'][1] as $Ix => $Match)
          {
              list($Tag, $Attr) =explode('/',$Match);
              $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], '<a href="http://htmlbook.ru/html/'.$Match.'">'.$Attr.'</a>', $Call['Output']);
          }

          return $Call['Output'];
     });