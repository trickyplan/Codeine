<?php

    interface IProfiler extends ICore 
    {
        public static function Tap($Handle);

        public static function Start($Handle);
        public static function Stop ($Handle);

        public static function MemStart($Handle);
        public static function MemStop ($Handle);

        public static function Lap($Handle);
        public static function Get($Handle);
        public static function Erase($Handle);

        public static function Report();
        public static function Output();

    }