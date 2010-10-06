<?php

    interface IData
    {
        // Base methods
        public static function Initialize ();
        public static function Shutdown   ();

        // Mountpoints
        public static function Mount   ($Point);
        public static function Unmount ($Point);

        // Storages
        public static function Connect   ($Name);
        public static function Disconnect($Name);

        // CRUD+E
        public static function Create ($Point, $DDL);
        public static function Read   ($Point, $DDL, $EnableCache = true);
        public static function Update ($Point, $DDL);
        public static function Delete ($Point, $DDL);
        public static function Exist  ($Point, $DDL, $EnableCache = true);

        // Transactions 
        public static function Start ($Point);
        public static function Commit ($Point);
        public static function Rollback ($Point);
    }