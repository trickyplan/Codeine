<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $Call = F::Apply('Entity', 'Load', $Call, ['Entity' => 'User']);

        $Call = F::Apply('Session', 'Write', $Call,
            [
                'Session Data' =>
                [
                    'Secondary' => $Call['ID'],
                    'User' => $Call['Session']['User']['ID']
                ]
            ]);
        
        F::Log('User '.$Call['Session']['User']['ID'].' impersonated himself as '.$Call['ID'], LOG_WARNING, 'Security');
        
        $Call = F::Hook('afterUserLoginDo', $Call);

        return $Call;
    });