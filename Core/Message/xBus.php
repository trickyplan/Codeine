<?php

    /**
     * @author BreathLess
     * @date 24.11.10
     * @time 17:24
     */

    class Message extends Component
    {
        protected static $_Points;
        protected static $_Conf;

        public static function Initialize ()
        {
            self::$_Conf = self::_Configure (__CLASS__);
        }

        public static function Shutdown ()
        {
            
        }

        public static function Connect ($Point)
        {
            return self::$_Points[$Point] = 
                Code::Run(array(
                           'F' => 'Message/'.self::$_Conf['Points'][$Point]['Method'].'/Connect',
                           'Point' => self::$_Conf['Points'][$Point]
                      ));
        }

        public static function Disconnect ($Call)
        {
            return self::$_Points[$Call['Point']] =
                Code::Run(array(
                           'F' => 'Message/'.self::$_Conf['Points'][$Call['Point']]['Method'].'/Disconnect',
                           'Point' => self::$_Conf['Points'][$Call['Point']]
                      ));
        }

        public static function Send ($Call)
        {
            if (!isset(self::$_Conf['Points'][$Call['Point']]))
                self::Connect($Call['Point']);

            return Code::Run(array(
                       'F' => 'Message/'.self::$_Conf['Points'][$Call['Point']]['Method'].'/Send',
                       'Point' => self::$_Conf['Points'][$Call['Point']],
                       'Call' => $Call
                      ));
        }

        public static function Receive ($Call)
        {
            if (!isset(self::$_Conf['Points'][$Call['Point']]))
                self::Connect($Call['Point']);
            
            return Code::Run(array(
                       'F' => 'Message/'.self::$_Conf['Points'][$Call['Point']]['Method'].'/Receive',
                       'Point' => self::$_Conf['Points'][$Call['Point']],
                       'Call' => $Call
                      ));
        }
    }