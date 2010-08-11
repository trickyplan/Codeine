<?php

/*
 * Unified Memory Architecture
 * C4 Implementation
 */

class Data // UMA
{
    private static $_Mounts;
    private static $_Storages;
    
    private static $_Mounted;
    private static $_Connected;

    private static $_MST;       // Mount - Storage Table

    private static $_Pool;
    private static $_Cache;
    public  static $Data;
    public  static $Queries = array();

    private static $_Transactions;

    public static function GetMounted ($Index = null)
    {
        if (null === $Index)
            return self::$_Points;
        else
            return self::$_Points[$Index];
    }

    public static function GetORM ($Point = null)
    {
        if ($Point)
        {
            self::Mount($Point);

            if (null === $Point)
                return self::$_ORMs;
            else
            {
                if (isset(self::$_MST[$Point]) and isset(self::$_Storages[self::$_MST[$Point]]))
                    return self::$_Storages[self::$_MST[$Point]]['ORM'];
                else
                    return null;
            }
        }
        else
            return null;
    }

    public static function Connect ($Name)
    {
        Timing::Go ('Сonnecting to '.$Name);

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
        
        Timing::Stop ('Сonnecting to '.$Name);
        
        return $Returned;
    }

    public static function Mount ($Point)
    {
        Timing::Go ('Mounting '.$Point);
        
        $Result = null;

        if (isset(self::$_Mounts[$Point]))
        {
            if (!isset(self::$_Mounted[$Point]) and !empty($Point))
            {
                if (isset(self::$_Mounts[$Point]['Node']))
                    $StorageID = self::$_Mounts[$Point]['Node'];
                else
                {
                    Timing::Stop('Mounting '.$Point);
                    return Log::Error('Node for Mount Point: '.$Point.'Not Defined');
                }
                
                // FIXME: Not once Storage

                if (self::Connect($StorageID) !== null)
                {
                    self::$_MST[$Point] = $StorageID;
                    Timing::Stop('Mounting '.$Point);
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

        Timing::Stop('Mounting '.$Point);

        return $Result;
    }

    public static function Unmount($Point)
    {           
        Timing::Go ('Unmounting '.$Point);

        $Name = self::$_MST[$Point];
        if (isset(self::$_Storages[$Name]))
            $Result = Code::E('Data/Mounters','Unmount', self::$_Connected[$Name], self::$_Storages[$Name]['Method']);
        else
            $Result = true;
       
        Timing::Stop('Unmounting '.$Point);

        return $Result;
    }

    public static function Initialize ()
    {
        Timing::Go('Data:Initialize');

            self::$_Storages = Core::$Conf['Storages'];
            self::$_Mounts   = Core::$Conf['Mounts'];

        Timing::Stop('Data:Initialize');
        return true;
    }

    public static function _UnmountAll()
    {
        $Unmounted = array();
        
        foreach(self::$_MST as $Point => $Storage)
            if (!isset($Unmounted[$Storage]))
                $Unmounted[$Storage] = self::Unmount($Point);
            
        return true;
    }

    private static function CRUD ($Method, $Point, $DDL)
    {
        Timing::Go ('Data '.$Point.':*');
        Timing::Go ('Data '.$Point.':'.$Method);
        
        $Result = null;
        
        if (!isset(self::$_Mounted[$Point]))
            self::Mount ($Point);

        $QID = $Method.$Point.json_encode($DDL);

        // if (in_array($QID, self::$Queries))
        //    Log::Info('Reduntant Query: '.$QID);
        
        // self::$Queries[] = $QID;

        $Storage    = &self::$_Storages [self::$_MST[$Point]];
        $Connected  = &self::$_Connected[self::$_MST[$Point]];
        $Mount      = &self::$_Mounts   [$Point];

        // TODO: Blades
        if (is_string($DDL))
        {
            if (null === ($DDL2 = json_decode($DDL, true)))
                return Log::Error('Data: В JSON вызове обнаружена ошибка.'.$DDL);
            else
                $DDL = $DDL2;
        }
        elseif (!is_array($DDL))
            return Log::Error('Data: Неверный формат вызова.'.$DDL);

        if (isset($Storage['Processor']))
            $DDL = Code::E('Data/Processors', $Method.'_Pre', $DDL, $Storage['Processor']);

        $Result
            = Code::E('Data/Mounters', 
                        $Method,
                        array(
                            'DDL'     => $DDL,
                            'Dir'     => $Mount['Dir'],
                            'Storage' => $Connected,
                            ), $Storage['Method']);

        if (isset($Storage['Processor']))
            $DDL = Code::E('Data/Processors', $Method.'_Post', $DDL, $Storage['Processor']);

        Log::Tap('Data:'.$Method, 'Data:'.$Point, 'Data:All');
        
        Timing::Stop ('Data '.$Point.':*');
        Timing::Stop ('Data '.$Point.':'.$Method);

        return $Result;
    }

    public static function Create($Point, $DDL)  // Create
    {       
        if (isset(self::$_Storages[self::$_MST[$Point]]['Transaction']))
            self::$_Transactions[$Point][] = array($DDL, 'Create');
        else
            return self::CRUD ('Create', $Point, $DDL);
    }

    public static function Read ($Point, $DDL, $EnableCache = true)    // Read
    {
        if ($EnableCache)
            {
                $DRID = md5($Point.json_encode($DDL));

                if (!($Data = self::CacheGet($Point, $DRID)) != null)
                {
                    $Data = self::CRUD ('Read', $Point, $DDL);
                    
                    self::CachePut($Point, $DRID, $Data);
                }

                return $Data;
            }
        else
            return self::CRUD ('Read', $Point, $DDL);
    }

    public static function Update ($Point, $DDL) // Update
    {
        if (isset(self::$_Storages[self::$_MST[$Point]]['Transaction']))
            self::$_Transactions[$Point][] = array($DDL, 'Update');
        else
            return self::CRUD ('Update', $Point, $DDL);
    }

    public static function Delete ($Point, $DDL) // Delete
    {
        if (isset(self::$_Storages[self::$_MST[$Point]]['Transaction']))
            self::$_Transactions[$Point][] = array($DDL, 'Delete');
        else
            return self::CRUD ('Delete', $Point, $DDL);
    }

    public static function Exist ($Point, $DDL, $EnableCache = true) // Exist
    {
        if ($EnableCache)
            {
                $ERID = md5('E'.$Point.$DDL);

                if (($Data = self::CacheGet($Point, $ERID, null, 'Exist')) !== null)
                        Log::Tap('Data:Cache:Exist:Hit');
                else
                {
                    $Data = self::CRUD ('Exist', $Point, $DDL);

                    self::CachePut($Point, $ERID, $Data, 'Exist');
                    
                    Log::Tap('Data:Cache:Exist:Miss');
                }

                return $Data;
            }
        else
            return self::CRUD ('Exist', $Point, $DDL);
    }

    public static function Start ($Point)
    {
        self::$_Transactions[$Point] = array();
        return true;
    }

    public static function Commit ($Point)
    {
        $DDL = null;
        
        if (!empty(self::$_Transactions[$Point]))
        {
            Log::Info('Commit '.$Point);
            $CLog = self::$_Transactions[$Point];
            
            if (!empty($CLog))
                {
                    foreach($CLog as $CRUD)
                        {
                            list ($DDL, $Method) = $CRUD;
                            self::CRUD($Point, $DDL, $Method);
                        }
                    self::$_Transactions[$Point] = array();
                }
        }
        return true;
    }
    
    public static function Rollback ($Point = null)
    {
        if (null !== $Point)
            self::$_Transactions[$Point] = array();
        else
            self::$_Transactions = array();
        return true;
    }

    // Методы кеширования

    public static function CachePut ($Point, $ID, $Value, $Type = 'Read')
    {
        if (isset(self::$_Mounts[$Point][$Type.'Cache']))
        {
            Log::Tap('Data:Cache:Put');

            if (!isset(self::$_Mounts[$Point][$Type.'Cache:TTL']))
                        self::$_Mounts[$Point][$Type.'Cache:TTL'] = Core::$Conf['Options']['Defaults'][$Type.'Cache:TTL'];

            if (self::$_Mounts[$Point][$Type.'Cache'] == 'Static')
                return (self::$_Cache[$ID] = $Value);
            else
                return self::CRUD('Create', self::$_Mounts[$Point][$Type.'Cache'],
                    array('I'=>$ID,'Expire'=>(time()+self::$_Mounts[$Point][$Type.'Cache:TTL']),
                                                'V'=>$Value));
        }
        else return null;
    }

    public static function CacheGet ($Point, $ID, $Or = null, $Type = 'Read')
    {
        if (isset(self::$_Mounts[$Point][$Type.'Cache']))
        {
            if (self::$_Mounts[$Point][$Type.'Cache'] == 'Static')
            {
                if (isset(self::$_Cache[$ID]))
                    return self::$_Cache[$ID];
                else
                    return $Or;
            }
            else
            {
                Log::Tap('Data:Cache:Get:Call');

                $Result = self::CRUD('Read', self::$_Mounts[$Point][$Type.'Cache'], '{"I":"'.$ID.'"}');

                if ($Result !== null)
                    if ($Result['Expire'] >= time())
                    {
                        Log::Tap('Data:Cache:Get:Hit');
                        return $Result['V'];
                    }
                    else
                        return $Or;
                else
                    Log::Tap('Data:Cache:Get:Miss');
            }
        }

        return $Or;
    }

    public static function CacheDel($Point, $ID)
    {
        return self::Delete($Point, '{"I":"'.$ID.'"}');
    }


    // Методы пула

    public static function PoolPut ($ID, $Value)
    {
        Log::Tap('Data:Pool:Put');
        self::$_Pool[$ID] = $Value;
        return true;
    }

    public static function PoolGet ($ID)
    {
        if (isset(self::$_Pool[$ID]))
        {
            Log::Tap('Data:Pool:Get:Hit');
            return self::$_Pool[$ID];
        }
        else
        {
            Log::Tap('Data:Pool:Get:Miss');
            return null;
        }
    }

    public static function Shutdown()
    {
        self::_UnmountAll();
    }
}
