<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Positive checker
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 30.10.10
     * @time 5:06
     */

     $Check = function ($Args)
     {        
         return ($Args['Value']>=0);
     };