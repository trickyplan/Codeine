<?php

    /**
     * @author BreathLess
     * @name Log
     * @description: Lynx logger
     * @package Codeine
     * @subpackage Core
     * @version 0.1
     * @date 28.10.10
     * @time 2:30
     */

    class Log
    {
        private static $_Writers = array();
        private static $_WritersObjects = array();

        private static function _Log($Type = 'Log', $Message, $Verbosity = 0)
        {
            foreach (self::$_Writers as $Writer)
                if ($Verbosity <= $Writer['Verbose'])
                    return Code::Run(
                            array(
                                'F' => 'System/Log/'.$Type,
                                'D' => $Writer['Driver'],
                                'Type' => $Type,
                                'Writer' => $Writer,
                                'Message' => $Message));
                else
                    return true;
        }


        public static function Bad ($Message, $Verbosity = 3)
        {
            self::_Log('Bad', $Message, $Verbosity);
            return false;
        }

        public static function Shutdown () {
            // TODO: Implement Shutdown() method.
        }

        public static function Dump ($Message, $Verbosity = 2)
        {
            self::_Log('Dump', $Message, $Verbosity);
            return true;
        }

        public static function Error ($Message, $Verbosity = 2)
        {
            self::_Log('Error', $Message, $Verbosity);
            return false;
        }

        public static function Good ($Message, $Verbosity = 4)
        {
            self::_Log('Good', $Message, $Verbosity);
            return true;
        }

        public static function Hint ($Message, $Verbosity = 4)
        {
            self::_Log('Hint', $Message, $Verbosity);
            return true;
        }

        public static function Important ($Message, $Verbosity = 1)
        {
            self::_Log('Important', $Message, $Verbosity);
            return true;
        }

        public static function Info ($Message, $Verbosity = 7)
        {
            self::_Log('Info', $Message, $Verbosity);
            return true;
        }

        public static function Initialize ()
        {
            self::$_Writers = Core::$Conf['Options']['Log']['Writers'];
            foreach (self::$_Writers as $Name => $Writer)
                self::$_WritersObjects[$Name] = Code::Run(
                            array(
                                'F' => 'System/Log/Initialize',
                                'D' => $Writer['Driver'],
                                'Writer' => $Writer));
            return true;
        }

        public static function Perfomance ($Message, $Verbosity = 6)
        {
            self::_Log('Perfomance', $Message, $Verbosity);
            return true;
        }

        public static function PHPError ($Code, $Error, $File, $Line)
        {
            return self::Error('PHP '.$Code.': '.'['.$File.':'.$Line.'] '.$Error);
        }

        public static function Stage ($Message, $Verbosity = 1)
        {
            self::_Log('Stage', $Message, $Verbosity);
            return true;
        }

        public static function Warning ($Message, $Verbosity = 2)
        {
            self::_Log('Warning', $Message, $Verbosity);
            return false;
        }
    
    }