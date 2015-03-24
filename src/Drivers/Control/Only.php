<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
     setFn('Redirect', function ($Call)
     {
         if (isset($Call['Session']['User']['ID']))
             $Call['Location'] = '/control';
         else
             $Call['Location'] = '/login';

         return F::Run('System.Interface.HTTP', 'Redirect', $Call);
     });