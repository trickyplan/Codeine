<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeLogSpit', function ($Call)
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
        
        F::Log($Log['User String'], LOG_DEBUG, $Call['Channel']);

        return $Call;
    });