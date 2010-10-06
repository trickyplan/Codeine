<?php
/*
 * Data API Implementation E9
 * Codeine5, 2010
 */

    include 'Data.php';

    class Data implements IData
    {
        private static $_Data      = array();
        private static $_Storages  = array();

        private static $_Connected = array();
        private static $_Mounts    = array();
        private static $_MTab      = array();

        public static function GetORM ($Point = null)
        {
            if ($Point)
            {
                self::Mount($Point);

                if (null === $Point)
                    return self::$_ORMs;
                else
                {
                    if (isset(self::$_MTab[$Point]) and isset(self::$_Storages[self::$_MST[$Point]]))
                        return self::$_Storages[self::$_MST[$Point]]['ORM'];
                    else
                        return self::$_Storages['Default']['ORM'];
                }
            }
            else
                return null;
        }

        public static function Commit($Point)
        {
            // TODO: Implement Commit() method.
        }

        public static function Connect($Name)
        {
            $Returned = null;

            if (isset(self::$_Storages[$Name]) and !isset(self::$_Connected[$Name]))
                {
                    $Connected = Code::E('Data/Mounters','Mount', self::$_Storages[$Name], self::$_Storages[$Name]['Method']);

                    if ($Connected !== null)
                    {
                        self::$_Connected[$Name] = $Connected;
                        $Returned = true;
                        Log::Good('Data: Подключение к '.$Name.' успешно.');
                    }
                    else
                        Log::Error('Data: Подключение к '.$Name.' не удалось.');
                }
                else
                    if (self::$_Connected[$Name] !== null)
                        $Returned = true;
                    else
                        $Returned = false;
            
            return $Returned;
        }

        public static function Create($Point, $DDL)
        {
            // TODO: Implement Create() method.
        }

        public static function Delete($Point, $DDL)
        {
            // TODO: Implement Delete() method.
        }

        public static function Disconnect($Name)
        {
            // TODO: Implement Disconnect() method.
        }

        public static function Exist($Point, $DDL, $EnableCache = true)
        {
            // TODO: Implement Exist() method.
        }

        public static function Initialize()
        {
            Code::Hook('Core', __CLASS__, 'beforeInitialize');

            self::$_Storages = Core::$Conf['Storages'];
            self::$_Mounts   = Core::$Conf['Mounts'];

            Code::Hook('Core', __CLASS__, 'afterInitialize');

            return true;
        }

        public static function Mount($Point)
        {
            $Result = null;

            if (isset(self::$_Mounts[$Point]))
            {
                if (!isset(self::$_Mounted[$Point]) and !empty($Point))
                {
                    if (isset(self::$_Mounts[$Point]['Node']))
                        $StorageID = self::$_Mounts[$Point]['Node'];
                    else
                        return Log::Error('Node for Mount Point: '.$Point.'Not Defined');

                    // FIXME: Not once Storage

                    if (self::Connect($StorageID) !== null)
                    {
                        self::$_MST[$Point] = $StorageID;
                        Profiler::Stop('Mounting '.$Point);
                        return true;
                    }
                    else
                        $Result = null;

                    if (isset(self::$_Storages[$StorageID]['Transaction']))
                        self::Start ($Point);
                }
                else
                    $Result = true;
            }
            else
                $Result = false;
        }

        public static function Read($Point, $DDL, $EnableCache = true)
        {
            // TODO: Implement Read() method.
        }

        public static function Rollback($Point)
        {
            // TODO: Implement Rollback() method.
        }

        public static function Shutdown()
        {
            Code::Hook('Core', __CLASS__, 'beforeShutdown');

            $Unmounted = array();

            foreach(self::$_MTab as $Point => $Storage)
                if (!isset($Unmounted[$Storage]))
                    $Unmounted[$Storage] = self::Unmount($Point);

            Code::Hook('Core', __CLASS__, 'afterShutdown');
        }

        public static function Start($Point)
        {
            // TODO: Implement Start() method.
        }

        public static function Unmount($Point)
        {
            // TODO: Implement Unmount() method.
        }

        public static function Update($Point, $DDL)
        {
            // TODO: Implement Update() method.
        }
        
    }
