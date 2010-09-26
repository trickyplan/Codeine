<?php

    class Code
    {
        private static $_Included;
        private static $_Drivers;

        public static function Initialize ($Drivers = null)
        {
           if (null !== $Drivers)
               self::$_Drivers = $Drivers;
           else
               self::$_Drivers = Core::$Conf['Drivers']['Installed'];
           
           return true;
        }

        public static function Hook($Type, $Class, $Method)
        {
            if (isset(Core::$Conf['Hooks'][$Class][$Method]) and is_array(Core::$Conf['Hooks'][$Class][$Method]))
                foreach (Core::$Conf['Hooks'][$Class][$Method] as $Hook)
                    self::E('Hooks/'.$Type.'/'.$Class,'Hook', array(), $Hook);
        }

        // E - сокращение от Execute
        // TODO Atomic ACL!!!!!!!!!!!!!!!

        public static function E ($NameSpace, $Function, $Operands = null, $Drivers = 'Default')
        {
            Profiler::Go ('Code:'.$NameSpace);

                $Result = false;

                $NameSpace = str_replace(':','/',$NameSpace);

                $F = false;
                $Catched = false;

                if (($Drivers == 'Default' or empty($Drivers)))
                {
                    if (isset (self::$_Drivers[$NameSpace]))
                        $Drivers = self::$_Drivers[$NameSpace];
                    else
                    {
                        $NameSpace2 = explode('/', $NameSpace);
                        $Drivers = $NameSpace2[count($NameSpace2)-1];
                    }
                }

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
                                        Profiler::Go    ('Code:'.$F);
                                        $Result = $F ($Operands);
                                        $Catched = true;
                                        Log::Tap ($F);
                                        Profiler::Stop  ('Code:'.$F);
                                    }
                               }
                           }
                    }
                    else
                    {
                        Profiler::Go    ('Code:'.$F);
                            $Result = $F ($Operands);
                            $Catched = true;
                        Profiler::Stop  ('Code:'.$F);
                        Log::Tap ($F);
                        break;
                    }

                }

                Profiler::Stop ('Code:'.$NameSpace);

                if (!$Catched)
                    throw new WTF ($NameSpace.' '.$Function.' not found in driver '.$Drivers[0], 4047);
            
            return $Result;
        }

        // Execute With Caching

        public static function EC ($NameSpace, $Function, $Operands, $Driver = 'Default')
        {
            $CID = sha1($NameSpace.$Function.json_encode($Operands));

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
            // TODO Deferred Execute
            $Command = new Object('_Command');
            return $Command->Create(array('NameSpace'=>$NameSpace, 'Function'=>$Function, 'Operands'=>json_encode($Operands),'Driver'=>$Driver));
        }


    }