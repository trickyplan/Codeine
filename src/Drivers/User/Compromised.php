<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $Users = F::Run('Entity', 'Read', $Call,
            [
                'Entity' => 'User'
            ]);
        
        foreach ($Users as $User)
        {
            $User['Password'] = F::Live($Call['User']['Compromised']['Generator']);
            
            F::Run('Entity', 'Update',
                [
                    'Entity' => 'User',
                    'Where'  => $User['ID'],
                    'Data'   => $User
                ]);
            $Call['Output']['Content'][] = $User['EMail'].' = '.$User['Password'];
        }
        
        return $Call;
    });