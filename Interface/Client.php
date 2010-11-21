<?php

    /**
     * @author BreathLess
     * @type Interface
     * @description: Interface for Client Core Module
     * @package Codeine
     * @subpackage Core
     * @version 0.1
     * @date 28.10.10
     * @time 2:35
     */

    interface IClient extends ICore
    {
        public static function Audit ();

        public static function Register ();
        public static function Annulate();
        
        public static function Attach ($Username);
        public static function Detach ();

        public static function Redirect($URL);
    }