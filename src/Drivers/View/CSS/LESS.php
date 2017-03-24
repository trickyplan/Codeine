<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Check', function ($Call)
    {
        list($Asset, $ID) = F::Run('View', 'Asset.Route',
            [
                'Value' => $Call['CSS Name']
            ])
        ;

        $LESS = F::Run('IO', 'Execute',
            [
                'Execute' => 'Exist',
                'Storage' => 'LESS',
                'Scope'   => [$Asset, 'less'],
                'Where'   => $ID
            ]);

        if ($LESS === null)
            F::Log('LESS *not found* '.Root.'/Assets/'.$Asset.'/less/'.$ID.'.less', LOG_DEBUG, 'Developer');
        else
        {
            $LESSVersion = F::Run('IO', 'Execute',
            [
                'Execute' => 'Version',
                'Storage' => 'LESS',
                'Scope'   => [$Asset, 'less'],
                'Where'   => $ID
            ]);
            
            $CSSVersion = F::Run('IO', 'Execute',
                [
                    'Execute' => 'Version',
                    'Storage' => 'CSS',
                    'Scope'   => [$Asset, 'css'],
                    'Where'   => $ID
                ]);

            if ($LESSVersion > $CSSVersion or (isset($Call['HTTP']['Request']['Headers']['Pragma']) && $Call['HTTP']['Request']['Headers']['Pragma'] == 'no-cache'))
            {
                // FIXME! Temporary decision.
                if ($LessPath = F::findFile('Assets/'.$Asset.'/less/'.$ID.'.less'))
                {
                    $Command = 'lessc "'.$LessPath.'"';
                    // $Command = 'lessc --clean-css ' .Root.'/Assets/'.$Asset.'/less/'.$ID.'.less > '.Root.'/Assets/'.$Asset.'/css/'.$ID.'.min.css';
                    // shell_exec($Command);
                    F::Log($Command, LOG_INFO, 'Developer');
                    $Call['CSS']['Styles'][$Call['CSS Name']] = shell_exec($Command);
                    
                    if (null === $Call['CSS']['Styles'][$Call['CSS Name']])
                        F::Log('LESS *failed* '.Root.'/Assets/'.$Asset.'/less/'.$ID.'.less', LOG_ERR, 'Developer');
                    else
                        F::Log('LESS *processed* '.Root.'/Assets/'.$Asset.'/less/'.$ID.'.less', LOG_INFO, 'Developer');
                    
                    F::Run('IO', 'Write',
                    [
                        'Storage'   => 'CSS',
                        'Scope'     => [$Asset, 'css'],
                        'Where'     => $ID,
                        'Data'      => $Call['CSS']['Styles'][$Call['CSS Name']]
                    ]);
                }
            }
            else
                F::Log('LESS *skipped* '.Root.'/Assets/'.$Asset.'/css/'.$ID.'.css', LOG_INFO, 'Developer');
        }

        return $Call;
    });