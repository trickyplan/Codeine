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

        public static function mergeOptions($First, $Second)
        {
            if (is_array($Second))
                foreach ($Second as $Key => $Value)
                    if (array_key_exists($Key, $First) && is_array($Value))
                        $First[$Key] = Core::mergeOptions($First[$Key], $Second[$Key]);
                    else
                        $First[$Key] = $Value;
            else
                return $Second;

            return $First;
        }

        public static function getOption($Option, $Array = null)
        {
            if ($Array == null)
                $Array = &self::$_Options;

            if (isset($Array[$Option]))
                return $Array[$Option];

            if (strpos($Option, '::'))
            {
                list($Namespace, $Keys) = explode('::', $Option);

                if (!isset($Array[$Namespace]))
                    self::_loadOptions($Namespace);
            }
            else
            {
                $Namespace = 0;
                $Keys = $Option;
                $Array = array($Array);
            }

            if (strpos($Keys, '.'))
                $Keys = explode('.', $Keys);
            else
                $Keys = array($Keys);

            $Value = $Array[$Namespace];


            foreach ($Keys as $cKey)
                if (isset($Value[$cKey]))
                    $Value = $Value[$cKey];
                else
                    return null;

            $Array[$Option] = $Value;
            
            return $Value;
        }

        protected static function _loadOptions($Name = 'Core')
        {
            $Options = Engine.Core::OptionsPath.'/'.$Name.'.json';
            $SiteSpecific = Root.Core::OptionsPath.'/'.$Name.'.json';

            if (file_exists($Options))
            {
                if (($Options = json_decode(file_get_contents($Options), true))==null)
                    throw new Exception('Malformed engine configuration');
            }
            else
                throw new Exception('Not found '.$Options, 404001);

            if (file_exists($SiteSpecific))
            {
                if (($SiteOptions = json_decode(file_get_contents($SiteSpecific), true))==null)
                    throw new Exception('Malformed site configuration');

                $Options = self::mergeOptions($Options, $SiteOptions);
            }

           array_walk_recursive($Options,
                function(&$value, $key)
                {
                    $value = Core::Any($value);
                });

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
                    (getenv('Enviroment') ? getenv('Enviroment') : 'Production'));

                define ('Host', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST']: 'localhost');
                define ('_Host', 'http://'.Host);

                spl_autoload_register ('Core::Load');

                return true;
            }
            catch (Exception $e)
            {
                die($e->getMessage());
            }
        }

        public static function Any($Call, $Routing = false)
        {
            if ($Routing || Code::isValidCall($Call))
            {
                if (($Result = Code::Run($Call)) !== null)
                    return $Result;
                else
                    return null;
            }

            if ($Routing || Data::isValidCall($Call))
            {
                if (($Result = Data::Read($Call)) !== null)
                    return $Result;
                else
                    return null;
            }

            if ($Routing || Message::isValidCall($Call))
            {
                if (($Result = Message::Send($Call)) !== null)
                    return $Result;
                else
                    return null;
            }

            return $Call;
        }
    }

    Core::Initialize();
