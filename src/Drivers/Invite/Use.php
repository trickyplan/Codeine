<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Check', function ($Call)
    {
        $Invite = F::Run('Entity', 'Read',
                    [
                         'Entity' => 'Invite',
                         'Where' => $Call['Request']['Invite']
                    ])[0];

        if (null === $Invite)
        {
            $Call = F::Hook('InviteNotExist', $Call);
        }
        else
        {
            if ($Invite['Active'])
                F::Run('Entity', 'Update',
                    [
                         'Entity' => 'Invite',
                         'Where' => $Call['Request']['Invite'],
                         'Data' => ['Active' => 0]
                    ]);
            else
                $Call = F::Hook('InviteAlreadyUsed', $Call);
        }

        return $Call;
    });