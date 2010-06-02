<?php

class Timing
{
    private static $Ticks = array();
    private static $Results = array();
    public static $Enabled;

    public static function Initialize()
    {
        // xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
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
                
                $Report[++$IC] = array('T' => $Result, 'S' => $Source, 'C' => round(($Result/self::$Results['Root']) * 100, 2).' %');
            }
            
        $Perfomance = 'Частота системного таймера: '.round( (1 / Timing::Autotest()) / 1000, 2).' KHz';
        arsort($Report);
        return $Report;
    }

    public static function Profiler()
    {
        if (Core::$Conf['Options']['Profiling'] == 0)
            return false;

        Timing::Stop('Root');
        return Code::E('System/Profilers', 'Profile', self::Report());

        /* $xhprof_data = xhprof_disable();
        include_once Engine.'Class/xhprof_lib/utils/xhprof_lib.php';
        include_once Engine.'Class/xhprof_lib/utils/xhprof_runs.php';
        
        $xhprof_runs = new XHProfRuns_Default();
        $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_test");
        # Хост, который Вы настроили ранее на GUI профайлера
        echo "Report: http://xhprof/index.php?run=$run_id&source=xhprof_test";
        echo "\n";

        
        */
    }
}