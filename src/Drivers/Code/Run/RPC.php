<?php

    /* Codeine
     * @author BreathLess
     * @description Deferred Run 
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Do', function ($Call)
     {
         return F::Run(
             array(
                 '_N' => 'Code.Format.'.$Call['Method'],
                 '_F' => 'DecodeResponse',
                 'Value' =>
                         F::Run(array(
                                      'Data' => array('Create', 'HTTP'),
                                      'ID' => $Call['URL'],
                                      'Value' => F::Run(
                                                   array(
                                                           '_N' => 'Code.Format.'.$Call['Method'],
                                                           '_F' => 'EncodeRequest',
                                                           'Value' => $Call['Value']
                                                       ))
                                      )
                                  )
             )
         );


     });