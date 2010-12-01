<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Odd checker
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 30.10.10
     * @time 5:06
     */

     $Check = function ($Args)
     {        
         return ($Args['Value']%2 !== 0);
     };