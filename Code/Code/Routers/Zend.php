<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Zend Router
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 10.11.10
     * @time 23:16
     */

  $Route = function ($Call)
  {
        if (!is_string($Call['Call']))
            return null;
        
        $Query = '';
        $Routed = array();

        $Call = explode ('/', substr($Call['Call'],1));

        $Pieces = count($Call);

        if ($Pieces % 2 == 1)
        {
            list ($Context, $Application, $Plugin) = $Call;

            for ($ic = 3; $ic <= $Pieces; $ic+=2)
                if (!empty($Call[$ic+1]))
                    $Routed[$Call[$ic]] = $Call[$ic+1];

            $Routed['F'] = 'Controller/'.$Plugin.'/'.$Plugin;
        }
        else
            $Routed = null;

      return $Routed;
  };