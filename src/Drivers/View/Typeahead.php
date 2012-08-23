<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn ('Render', function ($Call)
    {
      // $Call['Headers']['Content-type:'] = 'text/json';

       $JSON = array();
       if (is_array($Call['Output']))
            foreach ($Call['Output']['Content'] as $Key => $Widget)
                if (is_array($Widget))
                    $JSON[] = F::Run($Call['Renderer'] . '.Element.' . $Widget['Type'], 'Make', $Widget);


       $Call['Output'] = json_encode($JSON);
        echo  $Call['Output'];
       return $Call;
    });