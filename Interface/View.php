<?php

    interface IView extends ICore
    {
        public static function Start();
        public static function Body();
        public static function Reset ();

        public static function Render ();
        public static function Output ();

        public static function Load ();
        public static function Fusion();
        
        public static function Replace();
        public static function Get();

        public static function Add();
        public static function Buffer();
        public static function Flush();
    }
