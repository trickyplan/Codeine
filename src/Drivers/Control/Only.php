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
             $Call['Redirect'] = '/control';
         else
             $Call['Redirect'] = '/login';

         return F::Run('System.Interface.HTTP', 'Redirect', $Call);
     });