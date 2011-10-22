<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Create.Issue', function ($Call)
     {
         $Issue = array(
             'issue' => array(
                'project_id' => 2,
                'subject' => 'Test issue'
             )
         );

         var_dump(F::Run(array(
             '_N' => 'Data.Store.Net.HTTP',
             '_F' => 'Create',
             'ID' => 'http://62.109.24.121/redmine/issues.json?key=f480231a4adc3e39810a3127f279fef07e0c4af6',
             'Data' => json_encode($Issue)
         )));
     });