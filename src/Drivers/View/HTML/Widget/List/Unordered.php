<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

     setFn('Make', function ($Call)
     {
         $Output = '<ul'.(isset($Call['Class.List'])? ' class = "'.$Call['Class.List'].'"': '').'>';

         foreach($Call['Value'] as $Element)
             $Output.= '<li>'.$Element.'</li>';

         $Output .= '</ul>';

         return $Output;
     });