<?php

    /* Codeine
     * @author BreathLess
     * @description: VX10 Algorithm
     * @package Codeine
     * @version 6.0
     * @date 04.12.10
     * @time 15:18
     */

    self::Fn('Get', function ($Call)
    {
          do
          {
              $GammaCount = mt_rand(1, 6);
              $GammaLevel = mt_rand($Call['Min'], $Call['Max']);
              $CubeSize = 5;

              for ($x = 1; $x <= $CubeSize; $x ++)
                  for ($y = 1; $y <= $CubeSize; $y ++)
                      for ($z = 1; $z <= $CubeSize; $z ++)
                          $Work[$x][$y][$z] = mt_rand($Call['Min'], $Call['Max']);

              for ($g = 1; $g <= $GammaCount; $g ++)
              {
                  $GammaS[$g] = mt_rand(0, $GammaLevel);
                  for ($mx = 1; $mx <= round($CubeSize); $mx ++)
                      for ($my = 1; $my <= round($CubeSize); $my ++)
                          for ($mz = 1; $mz <= round($CubeSize); $mz ++) {
                              $Work[$mx][$my][$mz] += mt_rand(0, $GammaS[$g]);
                              if ($Work[$mx][$my][$mz] > $Call['Max'])
                                  $Work[$mx][$my][$mz] = $Work[$mx][$my][$mz] % $Call['Max'];
                              if ($Work[$mx][$my][$mz] < $Call['Min'])
                                  $Work[$mx][$my][$mz] = $Work[$mx][$my][$mz] % $Call['Min'];
                          }
              }

              $tx = mt_rand(1, $CubeSize);
              $ty = mt_rand(1, $CubeSize);
              $tz = mt_rand(1, $CubeSize);
          }
          
          while (! ($Work[$tx][$ty][$tz] >= $Call['Min'])
                 and ($Work[$tx][$ty][$tz] <= $Call['Max']));
          
          return $Work[$tx][$ty][$tz];
    });
