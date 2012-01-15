<?php

    /* Codeine
     * @author BreathLess
     * @description Cache Driver 
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Do', function ($Call)
     {
         /*$Storage = isset($Call['Storage'])? $Call['Storage']: 'Cache';
         $CacheID = F::hashCall($Call['Value']);

         $Cached = F::Run(
             array(
                 'Data' => array ('Load', $Storage),
                 'Scope' => 'Cache',
                 'ID' => $CacheID
             )
         );

         $Cached = $Cached[$CacheID];

         if (null !== $Cached && is_array($Cached))
         {
             if (($Cached['Time'] + $Call['TTL']) >= microtime(true))
                 return $Cached['Result'];
             else
                 F::Run(
                          array(
                              'Data' => array ('Delete' , $Storage),
                              'Scope' => 'Cache',
                              'ID' => $CacheID
                          )
                      );
         }

         $Cached = array('Result' => F::Run($Call['Value']));
         $Cached['Time'] = microtime(true);

         F::Run(
                  array(
                      'Data' => array ('Create', $Storage),
                      'Scope' => 'Cache',
                      'ID' => $CacheID,
                      'Value' => $Cached
                  )
              );*/

         return $Cached['Result'];
     });