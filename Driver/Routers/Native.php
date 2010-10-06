<?php

  $Route = function ($Call)
      {
            $Query = '';
            $Routed = array();

            $Call = explode ('/', mb_substr($Call,1));

            switch (count($Call))
            {
                case 3:
                    list ($Routed['Interface'], $Routed['Name'], $Routed['Plugin']) = $Call;
                break;

                case 4:
                    list ($Routed['Interface'], $Routed['Name'], $Routed['Plugin'], $Routed['ID']) = $Call;
                break;

                case 5:
                    list ($Routed['Interface'], $Routed['Name'], $Routed['Plugin'], $Routed['Mode'], $Routed['ID']) = $Call;
                break;

                case 6:
                    list ($Routed['Interface'], $Routed['Name'], $Routed['Plugin'], $Routed['Mode'], $Routed['ID'], $Routed['Aspect']) = $Call;
                break;

                default:
                    $Routed = null;
                break;
            }

          return $Routed;
      };