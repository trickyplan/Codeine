<?php

    class Core
    {
        const OptionsPath = 'Options';

        protected static $_Options;
        protected static $_OptionsLoaded = false;

        protected static function _Lock($Component)
        {
            call_user_func(array($Component, '_Lock'.$Component));
        }

        public static function getOption($Option)
        {
            if (isset(self::$_Options[$Option]))
                return self::$_Options[$Option];
            
            list($Namespace, $Keys) = explode('::', $Option);
            $Keys = explode('.', $Keys);

            if (!isset(self::$_Options[$Namespace]))
                self::_loadOptions($Namespace);

            $Value = self::$_Options[$Namespace];

            foreach ($Keys as $cKey)
                if (isset($Value[$cKey]))
                    $Value = $Value[$cKey];
                else
                    return null;

            self::$_Options[$Option] = $Value;
            
            return $Value;
        }

        protected static function _loadOptions($Name = 'Core')
        {
            $Options = Engine.Core::OptionsPath.'/'.$Name.'.json';

            if (file_exists($Options))
            {
                if (($Options = json_decode(file_get_contents($Options), true))==null)
                    throw new Exception('Malformed configuration');
            }
            else
                throw new Exception('Not found '.$Options, 404001);

           /* array_walk_recursive($_Options,
                function(&$value, $key)
                {
                    $value = Core::Any($value);
                });*/

            // TODO Site specific options, on-the-fly options
            self::$_Options[$Name] = $Options;
            return true;
        }

        public static function Load ($Core)
        {
            $Class = Engine.'Library/Core/'.$Core.'.php';

            if ((include_once($Class)) === false)
                throw new Exception($Core.' not found');

            call_user_func(array($Core, 'Initialize'));
            register_shutdown_function($Core.'::Shutdown');

            return true;
        }

        public static function Initialize()
        {           
            try
            {
                define ('DS', DIRECTORY_SEPARATOR);
                define ('Engine', realpath(__DIR__.'/../../').DS);

                defined('Environment') || define('Environment',
                    (getenv('Codeine_Enviroment') ? getenv('Codeine_Enviroment') : 'Production'));

                define ('Host', $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST']: 'localhost');
                define ('_Host', 'http://'.Host);

                spl_autoload_register ('Core::Load');

                return true;
            }
            catch (Exception $e)
            {
                die($e->getMessage());
            }
        }

        public static function Any($Variable, $Routing = false)
        {
            if ($Routing || Code::isValidCall($Variable))
            {
                if (($Result = Code::Run($Variable)) !== null)
                    return $Result;
            }

            if ($Routing || Data::isValidCall($Variable))
            {
                if (($Result = Data::Read($Variable)) !== null)
                    return $Result;
            }

            if ($Routing || Message::isValidCall($Variable))
            {
                if (($Result = Message::Send($Variable)) !== null)
                    return $Result;
            }

            return $Variable;
        }
    }

    Core::Initialize();
