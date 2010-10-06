<?php

    class Code
    {
        private static $_Functions;
        private static $_Hooks;
        private static $_Drivers;

        public static function Initialize ($Drivers = null)
        {
           if (null !== $Drivers)
               self::$_Drivers = $Drivers;
           else
               self::$_Drivers = Core::$Conf['Drivers']['Installed'];

           self::$_Hooks = Core::$Conf['Hooks'];
           return true;
        }

        public static function Hook($Type, $Class, $Method, &$Arguments = null)
        {
            if (isset(self::$_Hooks[$Class][$Method]) and is_array(self::$_Hooks[$Class][$Method]))
            {
                foreach (self::$_Hooks[$Class][$Method] as $Hook)
                    self::E('Hooks/'.$Type.'/'.$Class,'Hook', $Arguments, $Hook);
                return true;
            }
            else
                return false;
        }

        // E - сокращение от Execute
        // TODO Atomic ACL!!!!!!!!!!!!!!!

        public static function E ($NameSpace, $FunctionName, $Operands = null, $Driver = 'Default')
        {
            Profiler::Go ('Code:'.$NameSpace);

                $Result = false;

                $NameSpace = str_replace(':','/',$NameSpace);

                $Catched = false;

                if (($Driver == 'Default' or empty($Driver)))
                {
                    if (isset (self::$_Drivers[$NameSpace]))
                        $Driver = self::$_Drivers[$NameSpace];
                    else
                    {
                        $NameSpace2 = explode('/', $NameSpace);
                        $Driver = $NameSpace2[count($NameSpace2)-1];
                    }
                }

                {
                    if (!isset(self::$_Functions[$NameSpace][$Driver][$FunctionName]))
                    {
                       $FN = Server::Locate('Driver', $NameSpace.'/'.$Driver.'.php');
                       
                       if (!empty($FN) && (include ($FN)) == true)
                       {
                           self::$_Functions[$NameSpace][$Driver][$FunctionName] = $$FunctionName;
                       }
                       else
                           throw new WTF ($NameSpace.' '.$Driver.' not found', 0);
                    }

                    $F = self::$_Functions[$NameSpace][$Driver][$FunctionName];
                    $Result = $F($Operands);

                }

                Profiler::Stop ('Code:'.$NameSpace);

                //if (!$Catched)
                //    throw new WTF ($NameSpace.' '.$_Function.' not found in driver '.$Drivers[0], 0);
            
            return $Result;
        }

        // Execute With Caching

        public static function EC ($NameSpace, $_Function, $Operands, $Driver = 'Default')
        {
            $CID = sha1($NameSpace.$_Function.json_encode($Operands));

            if (null === ($Data = Data::CacheGet('_CodeCache', $CID)))
                return $Data;
            else
                return Data::CacheGet('_CodeCache', $CID, self::E($NameSpace,$_Function, $Operands, $Driver));
        }

        // Remote Execute

        public static function ER ($NameSpace, $_Function, $Operands, $Driver = 'Default')
        {
            // TODO Code RPC
        }

        public static function ED ($NameSpace, $_Function, $Operands, $Driver = 'Default')
        {
            // TODO Deferred Execute
            $Command = new Object('_Command');
            return $Command->Create(array('NameSpace'=>$NameSpace, 'Function'=>$_Function, 'Operands'=>json_encode($Operands),'Driver'=>$Driver));
        }


    }