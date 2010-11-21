<?php

    interface IApplication extends ICore
    {
        public static function Route ($Call);
        public static function Run ($Call = null);
    }