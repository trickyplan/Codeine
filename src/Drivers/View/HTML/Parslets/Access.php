<?php

    /* Codeine
     * @author BreathLess
     * @description Exec Parslet 
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Parse', function ($Call)
     {
          foreach ($Call['Parsed'][2] as $IX => $Match)
          {
                $Root = simplexml_load_string($Match);

                $Outer = '';
                $Inner = (string) $Root->asXML();

                $Call['Run'] = array();
                $Call['Run']['Service'] = (string) $Root->attributes()->service;
                $Call['Run']['Method'] = (string) $Root->attributes()->method;

                F::Run('Security.Access', 'Check', $Call);

                if ($Call['Decision'])
                    $Outer = $Inner;
                else
                    $Outer = '';

                $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);



          }

          return $Call;
     });