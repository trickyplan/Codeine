<?php

    interface ICode extends ICore
    {
        public static function Hook($Situation, $Arguments = null);
        
        public static function Run($Call);

    }