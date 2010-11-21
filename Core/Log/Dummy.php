<?php

    /**
     * @author BreathLess
     * @name Log
     * @description: Dummy logger
     * @package Codeine
     * @subpackage Core
     * @version 0.1
     * @date 28.10.10
     * @time 2:30
     */

    class Log implements ILog
    {
        public static function Bad ()
        {
            return false;
        }

        public static function Shutdown () {
            // TODO: Implement Shutdown() method.
        }

        public static function Dump ()
        {
            return true;
        }

        public static function Error ()
        {
            return false;
        }

        public static function Good ()
        {
            return true;
        }

        public static function Hint ()
        {
            return true;
        }

        public static function Important ()
        {
            return true;
        }

        public static function Info () {
            return true;
        }

        public static function Initialize ()
        {
            return true;
        }

        public static function Perfomance ()
        {
            return true;
        }

        public static function PHPError ()
        {
            return true;
        }

        public static function Stage ()
        {
            return true;
        }

        public static function Warning ()
        {
            return true;
        }
    
    }