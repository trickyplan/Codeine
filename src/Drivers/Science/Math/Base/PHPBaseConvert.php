<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
     setFn('Convert', function ($Call)
     {
        return base_convert($Call['Value'], $Call['From'], $Call['To']);
     });