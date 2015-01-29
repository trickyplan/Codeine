<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

     setFn('Make', function ($Call)
     {
         if (isset($Call['Data']))
             return $Call['Data'];
         else
             return null;
     });