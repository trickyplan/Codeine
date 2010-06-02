<?php

    class Code
    {
        private static $_Included;
        private static $_Drivers;

        public static function Initialize ($Drivers)
        {
           self::$_Drivers = $Drivers;
           return true;
        }

        // E - сокращение от Execute
        public static function E ($NameSpace, $Function, $Operands = null, $Drivers = 'Default')
        {
            Timing::Go ('Code:'.$NameSpace);

            $Result = false;
            $F = false;

            if (($Drivers == 'Default' or empty($Drivers)) and isset (self::$_Drivers[$NameSpace]))
                $Drivers = self::$_Drivers[$NameSpace];

            if (!is_array($Drivers))
                $Drivers = array($Drivers);

            foreach ($Drivers as $Driver)
            {
                $F = 'F_'.$Driver.'_'.$Function;
                
                if (!isset(self::$_Included[$NameSpace][$Driver]))
                {
                   $DriverFiles =
                    array(
                            Root.Driver.$NameSpace.'/'.$Driver.'.php',
                            Engine.Driver.$NameSpace.'/'.$Driver.'.php'
                         );

                   foreach ($DriverFiles as $DriverFile)
                       if (file_exists($DriverFile))
                       {
                           if ((include_once ($DriverFile)) == true)
                           {
                               if (is_callable($F))
                                {
                                    Timing::Go    ('Code:'.$F);
                                    $Result = $F ($Operands);
                                    Log::Tap ($F);
                                    Timing::Stop  ('Code:'.$F);
                                }
                           }
                       }
                }
                else
                {
                    Timing::Go    ('Code:'.$F);
                        $Result = $F ($Operands);
                    Timing::Stop  ('Code:'.$F);
                    Log::Tap ($F);
                    break;
                }
                
            }          

            Timing::Stop ('Code:'.$NameSpace);
            return $Result;
        }

        // Execute With Caching

        public static function EC ($NameSpace, $Function, $Operands, $Driver = 'Default')
        {
            $CID = md5($NameSpace.$Function.print_r($Operands, true));
            
            if (null === ($Data = Data::CacheGet('_CodeCache', $CID)))
                return $Data;
            else
                return Data::CacheGet('_CodeCache', $CID, self::E($NameSpace,$Function, $Operands, $Driver));
        }

        // Remote Execute

        public static function ER ($NameSpace, $Function, $Operands, $Driver = 'Default')
        {
            // TODO Code RPC
        }

        public static function ED ($NameSpace, $Function, $Operands, $Driver = 'Default')
        {
            // TODO Code Deferred
        }
    }