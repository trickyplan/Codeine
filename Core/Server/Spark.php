<?php

class Server
{
    private static $_R = array();
    public static $Drivers;
    public static $REST;
    public static $Conf;
    public static $Domain;

    public static function Data()
    {
        return self::$_R;
    }

    public static function Get($Key)
    {
        Timing::Go('Server Var');
        
        if (isset(self::$_R[$Key]) && !empty(self::$_R[$Key]))
            $R = self::$_R[$Key];
        else
            $R = null;
        
        Timing::Stop('Server Var');
        return $R;
    }

    private static function Sanitarize ()
    {
        Timing::Go ('Sanitarization');

            $_REQUEST = array_merge($_ENV, $_REQUEST, $_COOKIE, $_POST, $_GET);
            $VAR = $_REQUEST;

            self::$_R = Code::E('Input/Filters','Filter', $VAR);

            if (isset($_FILES))
                foreach($_FILES as $Key => $Value)
                    self::$_R[$Key] = $Value['tmp_name'];

                foreach($_SERVER as $Key => $Value)
                    self::$_R[$Key] = $Value;

        Timing::Stop ('Sanitarization');

        return true;
    }
    
    public static function Initialize()
    {
        Timing::Go('Codeine Server Initialize');

            set_error_handler('Log::PHPError');
            register_shutdown_function('Server::Shutdown');
           
            mb_regex_encoding('UTF-8');
            mb_regex_set_options('dim');
            mb_internal_encoding('UTF-8');
            mb_http_output('UTF-8');

            self::$Domain = array_reverse(explode('.',$_SERVER['HTTP_HOST'].'.'));

            Log::$Verbosity    = Core::$Conf['Options']['Verbose'];

            foreach(Core::$Conf['Paths'] as $Key => $Value)
                define($Key, $Value.'/');

            Data::Initialize();
            Code::Initialize(Core::$Conf['Drivers']['Installed']);
            Page::Initialize();
            Access::Initialize();
            Application::Initialize();
            
            self::Sanitarize();            
            self::$REST = strtolower(self::$_R['REQUEST_METHOD']);
            
        Timing::Stop('Codeine Server Initialize');
    }

    public static function FatalHandler($Data)
    {
        if ((mb_strpos($Data,'Fatal error') !== false) and Core::$Conf['Options']['FatalCatch'] == true)
        {
            if (Client::$TrustIP)
                $Data2 = $Data;
            else
                $Data2 = file_get_contents(EngineShared.Layout.'/Errors/Fatal.html');
        }
            $Data2 = $Data;

        //if (mb_strpos(Server::Get('HTTP_ACCEPT_ENCODING'), 'gzip') !== false)
        //    $Data = ob_gzhandler($Data, 9);

        // self::Shutdown();
        return $Data2;
    }

    public static function Shutdown ()
    {
        try
            {               
                Data::Shutdown();
                if (!Core::$Crash)
                    Client::Shutdown();

                if (Client::$TrustIP)
                {
                    echo Timing::Profiler();
                    Log::Output ();
                }
                ob_flush();
            }
        catch (Exception $e) {
            die('Проблемы при терминировании: '.$e->getMessage());
        }
    }
}
