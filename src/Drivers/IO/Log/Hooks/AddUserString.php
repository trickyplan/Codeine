<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Log.Spit.Channel.Before', function ($Call)
    {
        if (F::Dot($Call, 'Storages.'.$Call['Channel'].'.Log.AddUserString'))
        {
            if (PHP_SAPI == 'cli')
            {
                $Log['User String'] = posix_getpwuid(posix_getuid())['name'].' from CLI ';

                if (empty($SSH = shell_exec('echo $SSH_CLIENT')))
                    ;
                else
                    $Log['User String'].= 'SSH from: '.$SSH;
            }
            else
                $Log['User String'] = '*'.$Call['HTTP']['Agent'].'* from *'.$Call['HTTP']['IP'].'*';

            F::Log($Log['User String'], LOG_NOTICE, $Call['Channel'], false, true);
        }

        return $Call;
    });