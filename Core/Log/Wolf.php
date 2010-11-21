<?php

  class Log
  {
        private static $_Logger   = null;
        private static $_Counter    = 0;

        private static $_Verbosity   = 0;
        public static $Detected    = array();
        public static $Counters    = array();

        public static function Initialize ($Verbosity = null)
        {
            if (null !== $Verbosity)
                self::$_Verbosity = $Verbosity;
            else
                self::$_Verbosity = Core::$Conf['Log']['Verbose'];
            
            if (self::$_Verbosity != 0)
                return self::$_Logger = Code::E('Error/Loggers', 'Initialize', null, Core::$Conf['Log']['Driver']);//$Args
            else
                return null;
        }

        public static function Tap ()
        {
            $Counters = func_get_args();

            foreach($Counters as $Name)
            {
                if (isset(self::$Counters[$Name]))
                    self::$Counters[$Name]++;
                else
                    self::$Counters[$Name] = 1;
            }

            return true;
        }
        
        private static function _Log ($Type = 'Info', $Message = null, $Verbose = 6, $Args)
        {
            if ($Verbose <= self::$_Verbosity)
                return Code::E('Error/Loggers', $Type, array('Logger'=>self::$_Logger,'Message'=>$Message), Core::$Conf['Log']['Driver']);//$Args
            else
                return null;
        }
        
        public static function Important ($Message, $Verbose = 0, $Args = null)
        {
            self::_Log('Important', $Message, $Verbose, $Args);
            return true;
        }

        public static function Error ($Message, $Verbose = 0, $Args = null)
        {
            self::_Log('Error', $Message, $Verbose, $Args);
            return false;
        }

        public static function Warning ($Message, $Verbose = 1, $Args = null)
        {
            self::_Log('Warning', $Message, $Verbose, $Args);
            return false;
        }

        public static function Bad ($Message, $Verbose = 2, $Args = null)
        {
            self::_Log('Bad', $Message, $Verbose, $Args);
            return false;
        }

        public static function Dump ($Message, $Verbose = 3, $Args = null)
        {           
            self::_Log('Dump', $Message, $Verbose, $Args);
            return true;
        }

        public static function Stage ($Message, $Verbose = 3, $Args = null)
        {
            self::_Log('Stage', $Message, $Verbose, $Args);
            return true;
        }

        public static function Good ($Message, $Verbose = 4, $Args = null)
        {
            self::_Log('Good', $Message, $Verbose, $Args);
            return true;
        }

        public static function Perfomance ($Message, $Verbose = 5, $Args = null)
        {
            self::_Log('Perfomance', $Message, $Verbose, $Args);
            return true;
        }

        public static function Hint ($Message, $Verbose = 6, $Args = null)
        {
            self::_Log('Hint', $Message, $Verbose, $Args);
            return true;
        }

        public static function Info ($Message, $Verbose = 6, $Args = null)
        {
            self::_Log('Info', $Message, $Verbose, $Args);
            return true;
        }

        public static function PHPError ($Code, $Error, $File, $Line)
        {
            self::Error('PHP '.$Code.': '.'['.$File.':'.$Line.'] '.$Error);
        }
}

