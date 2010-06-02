<?php

/**
 * Description of 
 *
 * @author breathless
 */
class Message {

    private static $_Points;
    private static $_Methods;

    public static function Mount ($Point, $Method, $DSN)
    {
        Timing::Go('Mount:'.$Point);
            self::$_Points [$Point] = Code::E('Message/Transports','Mount', array('DSN' =>$DSN), $Method);
            self::$_Methods[$Point] = $Method;
        Timing::Stop('Mount:'.$Point);
        return self::$_Points[$Point];
    }

    public static function Unmount ($Point)
    {
        Timing::Go('Unmount:'.$Point);

            $Result = Code::E('Message/Transports','Send',
                array('Point'       => self::$_Points[$Point]), self::$_Methods[$Point]);

        Timing::Stop('Unmount:'.$Point);
    }

    public static function Send($Point, $Subject, $Message, $To, $From, $Type = 'Message', $Additional = '')
    {
        Timing::Go('Send:'.$Point);
            $Result = Code::E('Message/Transports','Send',
                    array('Point'       => self::$_Points[$Point],
                          'Subject'     => $Subject,
                          'Message'     => $Message,
                          'To'          => $To,
                          'From'        => $From,
                          'Type' => $Type,
                          'Additional'  => $Additional),
                    self::$_Methods[$Point]);
        Timing::Stop('Send:'.$Point);
        return $Result;
    }

    public static function Receive($Point)
    {

    }

    public static function Folder ($Point, $Name)
    {
        
    }
    
}
