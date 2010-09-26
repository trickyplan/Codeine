<?php

class Profiler
{
    private static $Ticks = array();
    private static $Results = array();
    private static $Memory;
    public static $Enabled;

    public static function Initialize()
    {
        return self::Go('Root');
    }

    public static function Autotest ()
    {
        return abs(microtime(true) - microtime(true)) + (1/2147483647);
    }

    public static function Go($Source)
    {
        if (!isset(self::$Results[$Source]))
            self::$Results[$Source] = 0;
        return self::$Ticks[$Source][0] = microtime(true);
    }

    public static function Stop($Source)
    {
        self::$Ticks[$Source][1] = microtime(true);
        self::$Results[$Source] += self::$Ticks[$Source][1] - self::$Ticks[$Source][0]; 
    }

    public static function Get ($Source)
    {
        return round(self::$Ticks[$Source][1] - self::$Ticks[$Source][0], 6);
    }

    public static function Erase ($Source)
    {
        unset(self::$Ticks[$Source]);
        unset(self::$Results[$Source]);
        return true;
    }

    public static function Lap ($Source)
    {
        return microtime(true) - self::$Ticks[$Source][0];
    }

    public static function Report ()
    {
        $Report = array();

        $IC=0;
        foreach (self::$Results as $Source => $Result)
            {
                
                if (!isset (self::$Ticks[$Source][1]))
                    self::$Ticks[$Source][1] = self::Lap('Root') && Log::Error ('Не остановлен таймер '.$Source);
                elseif ($Result == 0)
                    $Result = 0.000001;

                $Percentage = round($Result/self::$Results['Root']* 100, 2);
                $Report[++$IC] = array('T' => $Result, 'S' => $Source, 'C' => $Percentage);

                if (isset(Core::$Conf['Sensors']['Timing']))
                    if (isset(Core::$Conf['Sensors']['Timing'][$Source]))
                    {
                        if (Core::$Conf['Sensors']['Timing'][$Source]['Min']>$Result or Core::$Conf['Sensors']['Timing'][$Source]['Max']<$Result)
                            Log::Error('Timer "'.$Source. '" out of control!');
                    } elseif (isset(Core::$Conf['Sensors']['Timing'][$Source.'%']))
                        if (Core::$Conf['Sensors']['Timing'][$Source.'%']['Min']>$Percentage or Core::$Conf['Sensors']['Timing'][$Source.'%']['Max']<$Percentage)
                            Log::Error('Timer "'.$Source. '" out of control!');
            }

        $Report[] = 'Timer frequency '.round( (1 / self::Autotest()) / 1000, 2).' KHz';
        arsort($Report);
        return $Report;
    }

    public static function Output()
    {
        Profiler::Stop('Root');
        $Report = self::Report();
        
        if (Core::$Conf['Options']['Profiling'] == 0)
            return false;

        return Code::E('System/Profilers', 'Output', $Report);
    }

    public static function MemFrom($Handle)
    {
        return self::$Memory[$Handle] = memory_get_usage();
    }

    public static function MemTo($Handle)
    {
        self::$Memory[$Handle] = memory_get_usage()-self::$Memory[$Handle];
        Log::Perfomance ('Memory delta for '.$Handle.' is '.self::$Memory[$Handle].' bytes');
        return self::$Memory[$Handle];
    }
}