<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Required', function ($Call)
     {
         return F::Run(
             array(
                 '_N' => 'Science.Natural.Physics.Speed.Sound',
                 '_F' => 'Get'
             )
         );
     });

     self::Fn('Type', function ($Call)
     {
         F::Run(
                      array(
                          '_N' => 'Science.Natural.Physics.Speed.Sound',
                          '_F' => 'Get',
                          'Environment' => 'C2H5OH'
                      )
                  );

         echo F::Run(
              array(
                  '_N' => 'Science.Natural.Physics.Speed.Sound',
                  '_F' => 'Get',
                  'Environment' => 'H2O'
              )
          );
     });