<?php

  class Log // implements iLog
  {
        private static $_Messages   = array();
        private static $_Counter    = 0;
        public static  $Types
            = array('Important','Error','Warning',
                    'Bad', 'Dump', 'Stage', 
                    'Good','Perfomance', 'Hint',
                    'Info');

        public static  $Verbosity   = 0;
        public static  $Detected    = array();
        public static  $Counters    = array();
        
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
        
        private static function _Log ($Type = 9, $Message = null, $Verbose = 6)
        {
            if ($Verbose <= self::$Verbosity)
            {
                self::Tap('Log:'.$Type);
                self::$_Messages[self::$_Counter++] = array(round(microtime(true)-Core::$StartTime, 4)*1000, $Type, $Message);
            }
            return null;
        }
        
        public static function Important ($Message, $Verbose = 0)
        {
            self::_Log(0, $Message, $Verbose);
            return true;
        }

        public static function Error ($Message, $Verbose = 0)
        {
            self::_Log(1, $Message, $Verbose);
            return false;
        }

        public static function Warning ($Message, $Verbose = 1)
        {
            self::_Log(2, $Message, $Verbose);
            return false;
        }

        public static function Bad ($Message, $Verbose = 2)
        {
            self::_Log(3, $Message, $Verbose);
            return false;
        }

        public static function Dump ($Message, $Verbose = 3)
        {           
            self::_Log(4, '<var><pre>'.print_r($Message, true).'</pre></var>', $Verbose);
            return true;
        }

        public static function Stage ($Message, $Verbose = 3)
        {
            self::_Log(5, $Message, $Verbose);
            return true;
        }

        public static function Good ($Message, $Verbose = 4)
        {
            self::_Log(6, $Message, $Verbose);
            return true;
        }

        public static function Perfomance ($Message, $Verbose = 5)
        {
            self::_Log(7, $Message, $Verbose);
            return true;
        }

        public static function Hint ($Message, $Verbose = 6)
        {
            self::_Log(8, $Message, $Verbose);
            return true;
        }

        public static function Info ($Message, $Verbose = 6)
        {
            self::_Log(9, $Message, $Verbose);
            return true;
        }

        public static function PHPError ($Code, $Error, $File, $Line)
        {
            self::Error('PHP '.$Code.': '.'['.$File.':'.$Line.'] '.$Error);
        }
        

        public static function Output ()
        {
            return Code::E('Error/Loggers', 'Output', self::$_Messages);
        }
  }

