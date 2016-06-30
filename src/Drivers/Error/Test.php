<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        F::Log('Test: Developer Info',          LOG_INFO,       'Developer');
        F::Log('Test: Developer Warning',       LOG_WARNING,    'Developer');
        F::Log('Test: Developer Error',         LOG_ERR,        'Developer');
        F::Log('Test: Developer Critical',      LOG_CRIT,       'Developer');
        F::Log('Test: Developer Alert',         LOG_ALERT,      'Developer');
        F::Log('Test: Developer Emergency',     LOG_EMERG,      'Developer');

        F::Log('Test: Administrator Info',      LOG_INFO,       'Administrator');
        F::Log('Test: Administrator Warning',   LOG_WARNING,    'Administrator');
        F::Log('Test: Administrator Error',     LOG_ERR,        'Administrator');
        F::Log('Test: Administrator Critical',  LOG_CRIT,       'Administrator');
        F::Log('Test: Administrator Alert',     LOG_ALERT,      'Administrator');
        F::Log('Test: Administrator Emergency', LOG_EMERG,      'Administrator');
        
        F::Log('Test: Security Info',           LOG_INFO,       'Security');
        F::Log('Test: Security Warning',        LOG_WARNING,    'Security');
        F::Log('Test: Security Error',          LOG_ERR,        'Security');
        F::Log('Test: Security Critical',       LOG_CRIT,       'Security');
        F::Log('Test: Security Alert',          LOG_ALERT,      'Security');
        F::Log('Test: Security Emergency',      LOG_EMERG,      'Security');
        
        F::Log('Test: Marketing Info',          LOG_INFO,       'Marketing');
        F::Log('Test: Marketing Warning',       LOG_WARNING,    'Marketing');
        F::Log('Test: Marketing Error',         LOG_ERR,        'Marketing');
        F::Log('Test: Marketing Critical',      LOG_CRIT,       'Marketing');
        F::Log('Test: Marketing Alert',         LOG_ALERT,      'Marketing');
        F::Log('Test: Marketing Emergency',     LOG_EMERG,      'Marketing');
        
        F::Log('Test: Performance Info',      LOG_INFO,       'Performance');
        F::Log('Test: Performance Warning',   LOG_WARNING,    'Performance');
        F::Log('Test: Performance Error',     LOG_ERR,        'Performance');
        F::Log('Test: Performance Critical',  LOG_CRIT,       'Performance');
        F::Log('Test: Performance Alert',     LOG_ALERT,      'Performance');
        F::Log('Test: Performance Emergency', LOG_EMERG,      'Performance');

        $Call['Output']['Content'][] = 'Test executed';
        return $Call;
    });