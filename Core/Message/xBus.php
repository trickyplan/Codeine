<?php

    /**
     * @author BreathLess
     * @date 24.11.10
     * @time 17:24
     */

    final class Message extends Component
    {
        private static $_Locked;
        protected static $_Points;
        protected static $_Conf;

        protected static function _LockMessage()
        {
            return self::$_Locked = !self::$_Locked;
        }

        public static function Initialize ()
        {
            self::$_Conf = self::_Configure (__CLASS__);
        }

        public static function Shutdown ()
        {
            
        }

        public static function Connect ($Point)
        {
            self::$_Points[$Point] =
                Code::Run(array(
                           'N' => 'Message.'.self::$_Conf['Points'][$Point]['Method'],
                           'F' => 'Connect',
                           'Point' => self::$_Conf['Points'][$Point]
                      ));
            
            if (null === self::$_Points[$Point])
                Code::On(__CLASS__, 'errMessageConnectFailed', $Point);
        }

        public static function Disconnect ($Call)
        {
            return self::$_Points[$Call['Point']] =
                Code::Run(array(
                           'N' => 'Message.'.self::$_Conf['Points'][$Call['Point']]['Method'],
                           'F' => 'Disconnect',
                           'Point' => self::$_Conf['Points'][$Call['Point']]
                      ));
        }

        public static function Send ($Call)
        {
            if (!isset(self::$_Points[$Call['Point']]))
                self::Connect($Call['Point']);

            return Code::Run(array(
                       'N' => 'Message.'.self::$_Conf['Points'][$Call['Point']]['Method'],
                       'F' => 'Send',
                       'Point' => self::$_Points[$Call['Point']],
                       'Call' => $Call
                      ));
        }

        public static function Receive ($Call)
        {
            if (!isset(self::$_Conf['Points'][$Call['Point']]))
                self::Connect($Call['Point']);
            
            return Code::Run(array(
                       'N' => 'Message.'.self::$_Conf['Points'][$Call['Point']]['Method'],
                       'F' => 'Receive',
                       'Point' => self::$_Conf['Points'][$Call['Point']],
                       'Call' => $Call
                      ));
        }

        public static function isValidCall ($Call)
        {
            return is_array($Call) && isset($Call['Message']);
        }
    }