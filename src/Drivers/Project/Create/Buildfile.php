<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
     setFn('Do', function ($Call)
     {
         $VCall = $Call;
             $VCall['View']['Renderer'] = ['Service' => 'View.XML', 'Method' => 'Render'];

             $VCall['Output']['Root'] = 'project';
             $VCall['Attributes'] =
             [
                 'default' => 'final',
                 'basedir' => 'src'
             ];
             $VCall['Attributes']['name'] = $VCall['Task']['ID'].'-${branch}';

             $VCall = F::Hook('beforeAddBuildfileImports', $VCall);

                 foreach ($Call['Project']['Create']['Buildfile']['Imports'] as $Import)
                     $VCall['Output']['Content'][] = ['import' => ['@file' => $Import]];

             $VCall = F::Hook('afterAddBuildfileImports', $VCall);

             $VCall = F::Hook('beforeAddBuildfileProperties', $VCall);

                 foreach ($Call['Project']['Create']['Buildfile']['Properties'] as $Name => $Key)
                     $VCall['Output']['Content'][] = ['property' => ['@name' => $Name, '@value' => F::Dot($VCall, $Key)]];

             $VCall = F::Hook('afterAddBuildfileProperties', $VCall);


             $VCall['Output']['Content'][] = ['target' => ['@name' => 'final', '@depends' => 'debian']];

             $VCall = F::Run('View', 'Render', $VCall);
             d(__FILE__, __LINE__, $VCall['Output']);die();

         return $Call;
     });