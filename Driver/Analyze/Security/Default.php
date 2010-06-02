<?php
/**
 * Codeine Platform
 * @package Drivers
 * @name 
 * @author BreathLess
 * @version 0.1
 * @copyright BreathLess, 2010
 */

function F_Default_SecurityAnalyze($Args)
  {
        $Authorizers = $Args->Get('Authorizer:Installed', false);
        $Weights = array('Keyword'=>10,'Keyfile'=>25, 'KeyIP'=>50);
        
        $Level = 0;

        foreach ($Authorizers as $Authorizer)
        {
            $Keys[$Authorizer] = $Args->Get('Authorizer:'.$Authorizer, false);
            $Level += sizeof($Keys[$Authorizer])*$Weights[$Authorizer];
        }
        
        $Level *= sizeof($Authorizers);

        $Out = 'Critical';
        
        if ($Level >= 10)
            $Out = 'Low';
        if ($Level >= 25)
            $Out = 'Normal';
        if ($Level >= 50)
            $Out = 'High';

        return $Out;
  }