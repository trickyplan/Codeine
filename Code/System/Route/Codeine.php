<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Codeine Router
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 10.11.10
     * @time 23:16
     */

     $Route = function ($Call)
     {
            $Query = '';
            $Routed = array();

            $Call = explode ('/', mb_substr($Call['Call'],1));

            // Context/Entity/Action/ID
            switch (count($Call))
            {
                case 3:
                    list (
                            $Routed['Context'],
                            $Routed['Entity'],
                            $Routed['F']) = $Call;
                break;

                case 4:
                    list (
                            $Routed['Context'],
                            $Routed['Entity'],
                            $Routed['F'],
                            $Routed['ID']) = $Call;
                break;

                case 5:
                    list (
                            $Routed['Context'],
                            $Routed['Entity'],
                            $Routed['F'],
                            $Routed['ID'],
                            $Routed['Mode']) = $Call;
                break;

                case 6:
                    list (
                            $Routed['Context'],
                            $Routed['Entity'],
                            $Routed['F'],
                            $Routed['ID'],
                            $Routed['Mode'],
                            $Routed['Aspect']) = $Call;
                break;

                default:
                    $Routed = null;
                break;
            }

          $Routed['F'] = 'Controller/'.$Routed['F'].'/'.$Routed['F'];

          return $Routed;
      };