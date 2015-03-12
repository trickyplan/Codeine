<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
     setFn('Get', function ($Call)
     {
         return (int) shell_exec('nproc');
     });