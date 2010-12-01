<?php

    /**
     * @author BreathLess
     * @date 11.11.10
     * @time 1:02
     */

    abstract class Component
    {
        public static function ConfWalk ($First, $Second = array())
        {
            if (is_array($Second))
                foreach ($Second as $Key => $Value)
                {
                    if (!isset($First[$Key]))
                        $First[$Key] = array();

                    if (is_array($Value))
                        $First[$Key] = self::ConfWalk($First[$Key],$Value);
                    else
                        $First[$Key] = $Value;
                }
            else
                $Second = $First;

            return $First;
        }

        protected static function _Configure($Name = 'Core')
        {
            $EngineConf = Engine.'Config/Core/'.$Name.'.json';
            $SiteConf   = Root.'Config/'._Host.'/'.$Name.'.json';

            if (file_exists($EngineConf))
            {
                if (($EngineConf = json_decode(file_get_contents($EngineConf), true))==null)
                    throw new Exception(500001);
            }
            else
                throw new Exception('Not found '.$EngineConf, 404001);

            if (file_exists($SiteConf))
            {
                if (($SiteConf = json_decode(file_get_contents($SiteConf), true))==null)
                    throw new Exception('Site configuration malformed.', 500002);
                $_Conf = self::ConfWalk ($EngineConf, $SiteConf);
            }
            else
                $_Conf = $EngineConf;

            return $_Conf;
        }
    }