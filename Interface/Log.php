<?php

    interface ILog extends ICore
    {
        public static function Info();
        public static function Hint();
        public static function Perfomance();
        public static function Dump();

        public static function Important();
        public static function Stage();

        public static function Error();
        public static function Warning();

        public static function Bad();
        public static function Good();

        public static function PHPError();
    }