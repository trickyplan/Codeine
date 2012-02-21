<?php

    /* Codeine
     * @author BreathLess
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Parse', function ($Call)
     {
          foreach ($Call['Parsed'][1] as $Ix => $Match)
              $Call['Output'] = str_replace($Call['Parsed'][0][$Ix],
                  '<time datetime="'.date(DATE_ISO8601,$Match).'">'.date('Y.m.d H:i',$Match).'</time>',
                  $Call['Output']);

          return $Call['Output'];
     });