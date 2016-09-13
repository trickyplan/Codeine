<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
     setFn('Do', function ($Call)
     {
         $Achievements = F::loadOptions('Gamification.Achievement')['Achievements'];

         foreach ($Achievements as $Achievement)
             if ($Check = F::Run('Gamification.Achievement.'.$Achievement, 'Check', $Call))
             {
                 $Call['Output']['Content'][] =
                     [
                         'Type'     => 'Template',
                         'Scope'    => 'Gamification/Achievement/Show',
                         'ID'       => 'Short',
                         'Data'     => F::Run('Entity', 'Read',
                         [
                             'Entity' => 'Gamification.Achievement',
                             'Where'  =>
                             [
                                 'Award'    => $Achievement,
                                 'User'     => $Call['User']
                             ],
                             'One'  => true
                         ])
                     ];
             }
             else
             {
                 $Call['Output']['Content'][] =
                     [
                         'Type'     => 'Template',
                         'Scope'    => 'Gamification/Achievement/Show',
                         'ID'       => 'Default'
                     ];
             }

         return $Call;
     });