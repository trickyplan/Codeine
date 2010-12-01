<?php

    class Profiler extends Component
    {
        private static $Ticks = array();
        private static $Results = array();
        private static $Memory;
        public static $Enabled;

        public static function Initialize()
        {
            return self::Go('Root');
        }

        public static function Shutdown()
        {
            return true;
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
            return self::$Results[$Source] += self::$Ticks[$Source][1] - self::$Ticks[$Source][0];
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
            return Code::Run(array(
                                  'F' => 'System/Profiling/Report::Generate',
                                  'Memory' => self::$Memory,
                                  'Ticks' => self::$Ticks,
                                  'Results' => self::$Results,
                                  'D' => 'Absolute'
                             ));
        }

        public static function Output()
        {
            Profiler::Stop('Root');
            return self::Report();
        }

        public static function MemFrom($Handle)
        {
            return self::$Memory[$Handle] = memory_get_usage();
        }

        public static function MemTo($Handle)
        {
            self::$Memory[$Handle] = memory_get_usage()-self::$Memory[$Handle];
            return self::$Memory[$Handle];
        }
    }