<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
    setFn('Check', function ($Call)
    {
        $Call['User'] = isset($Call['Session']['User']['ID'])? $Call['Session']['User']['ID']: $Call['Data'];

        foreach ($Call['Achievements'] as $Achievement)
        {
            $Check = F::Run('Gamification.Achievement.'.$Achievement, 'Check', $Call);

            if ($Check)
            {
                F::Log('Achievement '.$Achievement.' processed', LOG_INFO);
                $Already = F::Run('Entity', 'Read',
                [
                    'Entity' => 'Gamification.Achievement',
                    'Where'  =>
                    [
                        'Award' => $Achievement,
                        'User' => $Call['User']
                    ]
                ]);

                if (empty($Already))
                {
                    F::Log('Achievement '.$Achievement.' unlocked!', LOG_INFO);
                    F::Run('Entity', 'Create',
                    [
                        'Entity' => 'Gamification.Achievement',
                        'Data'   =>
                        [
                            'Award'  => $Achievement,
                            'User'  => $Call['User']
                        ]
                    ]);
                }
                else
                    F::Log('Achievement already unlocked', LOG_INFO);
            }
            else
                F::Log('Achievement '.$Achievement.' skipped', LOG_INFO);
        }

        return $Call;
    });