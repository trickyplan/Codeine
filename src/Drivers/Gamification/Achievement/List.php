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
         {
             $Award = F::Run('Entity', 'Read',
             [
                 'Entity' => 'Gamification.Achievement',
                 'Where'  =>
                 [
                     'Award'    => $Achievement,
                     'User'     => $Call['User']
                 ],
                 'One'  => true
             ]);
             
             if ($Check = F::Run('Gamification.Achievement.'.$Achievement, 'Check', $Call) and !empty($Award))
             {
                     $Call['Output']['Content'][] =
                         [
                             'Type'     => 'Template',
                             'Scope'    => 'Gamification/Achievement/Show',
                             'ID'       => 'Short',
                             'Data'     => $Award
                         ];
             }
             else
             {
                 $Call['Output']['Content'][] =
                     [
                         'Type'     => 'Template',
                         'Scope'    => 'Gamification/Achievement/Show',
                         'ID'       => 'Default',
                         'Data'     =>
                         [
                             'Award' => $Achievement
                         ]
                     ];
             }
         }

         return $Call;
     });