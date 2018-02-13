<?php
    /*
     * @author bergstein@trickyplan.com
     * @description: F Class
     * @package Codeine Framework
     * @subpackage Core
    */
    //gc_disable();
    define ('Codeine', __DIR__);
    define ('Started', microtime(true));
    define ('REQID', 'REQ'.base_convert(Started, 10, 16).'.'.sha1(mt_rand(4294967295, PHP_INT_MAX)));

    defined('DS')? null: define('DS', DIRECTORY_SEPARATOR);

    final class F
    {
        private static $_Environment = 'Production';
        private static $_Hostname = 'host';

        private static $_Options = [];
        private static $_Code = [];

        private static $_Service = 'Codeine';
        private static $_Method = 'Do';
        private static $_Color = false;

        private static $_Storage = [];
        private static $_Ticks = [];
        private static $_Counters = [];
        private static $_Log = [];

        private static $_Live = false;
        private static $_Staring = false;

        private static $_Performance = false;  // Internal Performance
        private static $_Debug = false;  // Internal Debugger
        private static $_Verbose; // can be float

        private static $_Stack;
        private static $NC = 0;
        private static $_Bubble = '';
        private static $_Paths = [];
        public static $_Perfect = false;

        public static function Environment()
        {
            return self::$_Environment;
        }

        public static function Bootstrap ($Call = [])
        {
            self::$_Hostname = gethostname();
            self::$_Live = true;
            self::$_Stack = new SplStack();
            self::$_Color = new SplStack();

            self::Start(self::$_Service . '.' . self::$_Method);

            mb_internal_encoding('UTF-8'); // FIXME

            libxml_use_internal_errors(true);

            if (isset($_SERVER['Environment']))
                self::$_Environment = $_SERVER['Environment'];

            if (isset($Call['Environment']) and null !== $Call['Environment'])
                self::$_Environment = $Call['Environment'];

            define('Environment', self::$_Environment);
            if (isset($Call['Paths']))
            {
                if (is_array($Call['Paths']))
                    self::$_Paths = array_merge($Call['Paths'], [Codeine]);
                else
                    self::$_Paths = [$Call['Paths'], Codeine];
            }
            else
                self::$_Paths = [Codeine];

            self::loadOptions('Codeine');

            date_default_timezone_set(self::$_Options['Codeine']['Timezone']);
            setlocale(LC_ALL, self::$_Options['Codeine']['Locale']);

            if (isset(self::$_Options['Codeine']['Verbose']))
                self::$_Verbose = self::$_Options['Codeine']['Verbose'];

            if (isset($_REQUEST['Performance']))
                self::$_Performance = true;

            if (isset($_SERVER['Verbose']))
                foreach (self::$_Verbose as $Channel => &$Level)
                {
                    $Level = $_SERVER['Verbose'];
                    self::$_Log[$Channel] = [];
                }

            if (isset($_REQUEST['Debug']))
            {
                self::$_Debug = true;
                foreach (self::$_Verbose as &$Level)
                    $Level = PHP_INT_MAX;
            }

            self::Log('Codeine started', LOG_NOTICE);
            F::Start('Preheat');

            if (isset($_COOKIE['Overlay'])
                && in_array($_COOKIE['Overlay'], self::$_Options['Codeine']['Overlays']))
                $Call['Overlay'] = $_COOKIE['Overlay'];

            if (isset($_REQUEST['Overlay'])
                && in_array($_REQUEST['Overlay'], self::$_Options['Codeine']['Overlays']))
            {
                setcookie('Overlay', $_REQUEST['Overlay'],
                    4294967296,  '/', $_SERVER['HTTP_HOST']);
                $Call['Overlay'] = $_REQUEST['Overlay'];
            }

            if (isset($Call['Overlay']))
            {
                define('Overlay', $Call['Overlay']);
                array_unshift(self::$_Paths, Codeine.'/Overlays/'.$Call['Overlay']);
                array_unshift(self::$_Paths, Root.'/Overlays/'.$Call['Overlay']);
                self::Log('Overlay enabled: '.$Call['Overlay'], LOG_NOTICE);
            }

            foreach (self::$_Paths as $Path)
                self::Log('Path registered *'.$Path.'*', LOG_DEBUG);

            set_error_handler ('F::Error');
            register_shutdown_function('F::Shutdown');

            self::Log('Environment: *'.self::$_Environment.'*', LOG_INFO);

            $Call = self::Hook('onBootstrap', $Call);

            self::$_Perfect = self::$_Options['Codeine']['Perfect'];

            $Call['Call']['Hostname'] = gethostname();
            $Call['Call']['Environment'] = F::Environment();

            F::Log('PHP '.PHP_SAPI.' '.phpversion(), LOG_INFO);
            
            $Call['Version'] = F::loadOptions('Version');
            
            F::Log('Codeine Version: *'.$Call['Version']['Codeine']['Major'].'*', LOG_INFO);
            
            if (isset($Call['Watch']))
            {
                if ($Call['Watch'] === null)
                    ;
                else
                    self::$_Options['Codeine']['Watch'][] = $Call['Watch'];
            }
            
            if (F::Environment() == 'Development')
                self::$_Bubble = str_repeat('*', 4096 * 1024);
            
            return self::Live($Call);
        }

        public static function Shutdown()
        {
            self::$_Bubble = '';
            self::Stop(self::$_Service . '.' . self::$_Method);

            $E = error_get_last();
            if ($E === null)
                ;
            else
            {
                if (self::$_Environment === 'Production')
                {
                    // header ('HTTP/1.1 500 Internal Server Error');
                    // TODO Real error triggering
                    F::Run('IO.Log', 'Spit', []);
                    file_put_contents('/tmp/codeine/fail-'.REQID, j(self::$_Log));
                }
                else
                {
                    self::Finish(implode("\t", $E));
                }
            }

            if (self::$_Debug)
            {
                ksort($Call);
                d(__FILE__, __LINE__, mb_strlen(serialize($Call)));
                d(__FILE__, __LINE__, $Call);
            }

            return true;
        }

        private static function _loadSource($Service)
        {
            $Path = strtr($Service, '.', '/');

            $Filenames = self::findFiles(self::$_Options['Codeine']['Driver']['Path'].'/'.$Path.self::$_Options['Codeine']['Driver']['Extension']);

            if (!empty($Filenames))
            {
                foreach ($Filenames as $Filename)
                {
                    self::Log($Filename, LOG_DEBUG);
                    include $Filename;
                }

                return true;
            }
            else
            {
                self::Log('*'.$Service.'* not found', LOG_NOTICE);
                self::Log(self::Stack(), LOG_NOTICE);
                return false;
            }
        }

        public static function loadOptions($Service = null, $Method = null, $Call = [], $Path = 'Options')
        {
            $Service = ($Service == null)? self::$_Service: $Service;
            
            // Если контракт уже не загружен
            if (isset(self::$_Options[$Service.$Method]))
                ;
            else
            {
                $Options = [];
                $Filenames = [];
                $ServicePath = strtr($Service, '.', '/');
                
                if ($Method === null)
                    ;
                else
                    $ServicePath.= '.'.$Method;

                if (self::$_Environment != 'Production')
                {
                    $Filenames[] = $Path.DS.$ServicePath.'.'.self::$_Hostname.'.json';
                    $Filenames[] = $Path.DS.$ServicePath.'.'.self::$_Environment.'.json';
                }

                $Filenames[] = $Path.DS.$ServicePath.'.json';

                if (($Filenames = self::findFiles ($Filenames)) !== null)
                {
                    foreach ($Filenames as $Filename)
                    {
                        $Current = jd(file_get_contents($Filename), true);

                        if ($Current)
                            self::Log('Options: *'.$Filename.'* loaded', LOG_DEBUG);
                        else
                        {
                            switch (json_last_error()) {
                                case JSON_ERROR_NONE:
                                    $JSONError =  ' - No errors';
                                break;
                                case JSON_ERROR_DEPTH:
                                    $JSONError =  ' - Maximum stack depth exceeded';
                                break;
                                case JSON_ERROR_STATE_MISMATCH:
                                    $JSONError =  ' - Underflow or the modes mismatch';
                                break;
                                case JSON_ERROR_CTRL_CHAR:
                                    $JSONError =  ' - Unexpected control character found';
                                break;
                                case JSON_ERROR_SYNTAX:
                                    $JSONError =  ' - Syntax error, malformed JSON';
                                break;
                                case JSON_ERROR_UTF8:
                                    $JSONError =  ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                                break;
                                default:
                                    $JSONError =  ' - Unknown error';
                                break;
                            }
                            self::Log('JSON Error: ' . $Filename.':'. $JSONError, LOG_CRIT); //FIXME
                            $Current = [];
                        }

                        if (isset($Current['Mixins']) && is_array($Current['Mixins']))
                            foreach($Current['Mixins'] as $Mixin)
                            {
                                self::Log('Options *'.$Filename.'* mixed with *'.$Mixin.'*', LOG_DEBUG);
                                $Current = self::Merge(self::loadOptions($Mixin), $Current);
                            }

                        $Options = self::Merge($Options, $Current);
                    }
                }
                else
                {
                    self::Log('No options for *'.$Service.':'.$Method.'*', LOG_DEBUG);
                    $Options = [];
                }

                self::$_Options[$Service.$Method] = $Options;
            }
            
            return self::Merge(self::$_Options[$Service.$Method], $Call);
        }

        public static function saveOptions ($Service, $Options, $Path = 'Options')
        {
            $Service = ($Service == null)? self::$_Service: $Service;
/*            $Method = ($Method == null)? self::$_Method: $Method;*/

            // Если контракт уже не загружен

            $ServicePath = strtr($Service, '.', '/');
            $Filename = Root.DS.$Path.DS.$ServicePath.'.json';
            file_put_contents($Filename, j($Options));
            self::$_Options[$Service] = $Options;
        }

        public static function hashCall($Call)
        {
            if (isset($Call['Call']))
                return $Call['Service'].':'.$Call['Method'].'('.sha1(j($Call['Call'])).')';
            else
                return $Call['Service'].':'.$Call['Method'].'()';
        }

        public static function isCall($Call)
        {
            return (is_array($Call) && isset($Call['Service']));
        }

        public static function Run($Service, $Method = null , $Call = [])
        {
            if (($sz = func_num_args()) > 3)
                for($ic = 3; $ic < $sz; $ic++)
                    if (is_array($Argument = func_get_arg ($ic)))
                        $Call = self::Merge($Call, $Argument);
             
            return self::Execute($Service, $Method, $Call);
        }

        public static function Apply($Service, $Method = null , $Call = [])
        {
            if (($sz = func_num_args())>3)
                for($ic = 3; $ic < $sz; $ic++)
                {
                    $Argument = func_get_arg ($ic);
                    if (is_array($Argument))
                        $Call = self::Merge($Call, $Argument);
                }

            $Result = self::Execute($Service, $Method, $Call);

            if ($Result === null)
                $Result = $Call;

            return $Result;
        }

        private static function Execute($Service, $Method, $Call)
        {
            if (isset(self::$_Options['Codeine']['Watch']) && F::Environment() == 'Development')
                foreach (self::$_Options['Codeine']['Watch'] as $Watch)
                {
                    $WatchValue = F::Dot($Call, $Watch);
                    if ($WatchValue === null)
                        ;
                    else
                    {
                        if (F::Get('Watch.'.$Watch) == $WatchValue)
                            ;
                        else
                        {
                            F::Log('Watch *' . $Watch . '* was *'.j(F::Get('Watch.'.$Watch)).'*, now *' . j($WatchValue). '* (' . $Service . ':' . $Method . '*)',
                                LOG_NOTICE,
                                'Developer',
                                true);
                            F::Set('Watch.'.$Watch, $WatchValue);
                        }
                    }
                }
            
            $OldService = self::$_Service;
            $OldMethod = self::$_Method;

            if ($Service !== null)
                self::$_Service = $Service;

            if ($Method !== null)
                self::$_Method  = $Method;

            self::Stop($OldService);
            self::Stop($OldService. ':' . $OldMethod);

            self::Start(self::$_Service);
            self::Start(self::$_Service . ':' . self::$_Method);

            self::$_Stack->push(self::$_Service.':'.self::$_Method);

            $Count = self::$_Stack->count();

            if ($Count > F::Get('MSS')) // Max Stack Size
                F::Set('MSS', $Count);

            $FnOptions = self::loadOptions();

            $Call = self::Merge($FnOptions, $Call);

            if ((null === self::getFn(self::$_Method)) && !self::_loadSource(self::$_Service))
            {
                # trigger_error('Service: '.self::$_Service.' not found');
                self::Log('Service: '.self::$_Service.' not found', LOG_WARNING);
                $Result = (is_array($Call) && isset($Call['Fallback']))? $Call['Fallback'] : null;
            }
            else
            {
                $F = self::getFn(self::$_Method);

                if (is_callable($F))
                {
                    if ($Behaviours = F::Dot($Call, 'Behaviours') and self::$_Method !== 'Run')
                    {
                        foreach ($Behaviours as $Behaviour => $Options)
                        {
                            if (F::Dot($Options, 'Enabled') === true)
                            {
                                F::Log('Behaviour active *'.$Behaviour.'*', LOG_INFO);
                                $Call = F::Dot($Call, 'Behaviours.'.$Behaviour.'.Enabled', -1);
                                $Call = F::Apply('Code.Run.Behaviours.'.$Behaviour, 'Run',
                                    [
                                        'Behaviours' => F::Dot($Call, 'Behaviours'),
                                        'Run' =>
                                            [
                                                'Service'   => self::$_Service,
                                                'Method'    => self::$_Method,
                                                'Call'      => $Call
                                            ]
                                    ]);
                            }
                        }
                    }
                    
                    if (isset($Call['Run']['Result']) or isset($Call['Run']['Skip']))
                    {
                        $Result = $Call['Run']['Result'];
                        F::Log('Behaviours active for '.self::$_Service.':'.self::$_Method.', skip Real Run', LOG_NOTICE);
                    }
                    else
                        $Result = $F($Call); // Real Run Here
                    
                    // if (self::$_Performance)
                    self::Counter(self::$_Service.'.'.self::$_Method);
                }
                else
                    $Result = isset($Call['Fallback']) ? $Call['Fallback'] : null;
            }

            self::Stop(self::$_Service);
            self::Stop(self::$_Service . ':' . self::$_Method);

            self::Start($OldService);
            self::Start($OldService. ':' . $OldMethod);

            self::$_Service = $OldService;
            self::$_Method = $OldMethod;

            self::$NC++;

            self::$_Stack->pop();

            return $Result;
        }

        public static function Stack()
        {
            $Output = [];

            $IX = count(self::$_Stack);
            foreach (self::$_Stack as $Element)
                $Output[] = $IX.str_pad(" ", $IX--).$Element;

            // $Output = '<pre>'.implode(array_reverse($Output)).'</pre>';

            return implode(PHP_EOL, array_reverse($Output));
        }

        public static function Live($Variable, $Call = [])
        {
            F::Start('Live');

            if ($Variable instanceof Closure)
                $Result = $Variable($Call);
            else
            {
                if (isset($Variable['NoLive']))
                    $Result = $Variable;
                else
                {
                    if (self::isCall($Variable))
                    {
                        if (($sz = func_num_args()) > 2)
                        {
                            for($ic = 2; $ic<$sz; $ic++)
                                if (is_array($Argument = func_get_arg ($ic)))
                                    $Call = self::Merge($Call, $Argument);
                        }

                        if (isset($Variable['Method']))
                            ;
                        else
                            $Variable['Method'] = 'Do';

                        if (isset($Call['Live Override']['Service']))
                            $Variable['Service'] = $Call['Live Override']['Service'];

                        if (isset($Call['Live Override']['Method']))
                            $Variable['Method'] = $Call['Live Override']['Method'];

                        $Result = self::Run($Variable['Service'], $Variable['Method'],
                            $Call, isset($Variable['Call'])? self::Variable($Variable['Call'], $Call): []);
                    }
                    else
                    {
                        if ($Variable === (array) $Variable)
                            foreach ($Variable as $Key => $cVariable)
                                $Variable = self::Dot($Variable, $Key, self::Live($cVariable, $Call));
                        else
                            $Variable = self::Variable($Variable, $Call);

                        $Result = $Variable;
                    }
                }
            }

            F::Stop('Live');
            return $Result;
        }

        public static function Variable ($Variable, $Call)
        {
            if (is_array($Variable))
                foreach ($Variable as &$cVariable)
                    $cVariable = self::Variable($cVariable, $Call);
            else
                if (is_string($Variable) && mb_strpos($Variable, '$') !== false && preg_match_all('@\$([\w\.]+)@Ssu', $Variable, $Pockets))
                {
                    foreach ($Pockets[1] as $IX => $Match)
                    {
                        $Subvariable = self::Dot($Call, $Match);
                        if (is_scalar($Subvariable) or $Subvariable === null)
                        {
                            if ($Subvariable !== null)
                                $Variable = str_replace($Pockets[0][$IX], $Subvariable, $Variable);
                        }
                        else
                            F::Log('Subvariable *'.$Match.'* is non-scalar ', LOG_WARNING);
                        
                    }
                }

            return $Variable;
        }

        public static function Hook($On, $Call)
        {
             F::startColor('f84000');
             if (isset($Call['Custom Hooks'][$On]))
                 $On = $Call['Custom Hooks'][$On];

             if (isset($Call['Hooks']) && ($Hooks = self::Dot($Call, 'Hooks.' . $On)))
             {
                 if (isset($Call['No'][$On]) && $Call['No'][$On] === true)
                     return $Call;

                 if (empty($Hooks))
                     ;
                 else
                 {
                     $Hooks = self::Sort($Hooks, 'Weight', SORT_DESC);

                     foreach ($Hooks as $HookName => $Hook)
                     {
                         if (substr($HookName,0,1) == '-')
                             ;
                         else
                         {
                             self::$_Stack->push('@'.$On.':'.$HookName);
                             self::Log($On.':'.$HookName, LOG_DEBUG);

                             if (self::isCall($Hook))
                             {
                                 if (isset($Hook['Method']))
                                     ;
                                 else
                                     $Hook['Method'] = 'Do';

                                 $Call = self::Apply($Hook['Service'],$Hook['Method'], isset($Hook['Call'])? $Hook['Call']: [], $Call, ['On' => $On]);
                             }
                             else
                                 $Call = self::Merge($Call, $Hook);
                             
                             self::$_Stack->pop();
                         }
                     }
                 }
             }
            
            F::stopColor();
            return $Call;
        }

        public static function Error($errno , $errstr , $errfile , $errline)
        {
            $ErrHash = strtoupper(substr(sha1($errno.$errstr.$errfile.$errline), -8, 8));
            $Message = 'EH: '.$ErrHash.PHP_EOL.' E'.$errno.':'.$errstr.PHP_EOL.
            '<a href="'.'ide://'.$errfile.':'.$errline.'">'.$errfile.'@'.$errline.'</a>';
            
            if (self::$_Perfect)
                self::Finish ($Message);
            
            self::Log($Message, LOG_CRIT, 'Developer', true);
        }
        
        public static function Finish ($Message)
        {
            $Logs = self::Logs();
            foreach ($Logs as $Channel => $Records)
                foreach ($Records as $Record)
                    $JSONLogs[] = [
                        'Channel'   => $Channel,
                        'Verbose'   => $Record[0],
                        'Time'      => $Record[1],
                        'Message'   => $Record[2],
                        'From'      => $Record[3],
                        'Depth'     => $Record[4],
                        'Stack'     => $Record[5]
                    ];
            
            if (PHP_SAPI == 'cli')
                $Output = $Message;
            else
            {
                $Output = file_get_contents(Codeine.'/Assets/Finish.html');
                $Output = str_replace('<finish:message/>', $Message, $Output);
                $Output = str_replace('<finish:stack/>',self::Stack(), $Output);
                $Output = str_replace('<finish:logs/>', j($JSONLogs), $Output);
            }

            echo $Output;
            /*if (function_exists('xdebug_print_function_stack'))
                xdebug_print_function_stack();*/
            exit();
        }

        public static function setLive($Live)
        {
            return self::$_Live = (bool) $Live;
        }

        public static function getLive()
        {
            return self::$_Live;
        }

        public static function reloadOptions ()
        {
            foreach (self::$_Options as $Service)
                self::loadOptions($Service);

            foreach (self::$_Code as $Service)
                self::_loadSource($Service);

            return true;
        }

        public static function startColor ($Color) // Colorize code sections
        {
            self::$_Color->push($Color);
            return $Color;
        }
        
        public static function stopColor () // Colorize code sections
        {
            return self::$_Color->pop();
        }
        
        public static function getColor () // Colorize code sections
        {
            if (self::$_Color->offsetExists(0))
                return self::$_Color->offsetGet(0);
            else
                return "ffffff";
        }
        
        public static function Log ($Message, $Verbose = 7, $Channel = 'Developer', $AppendStack = false)
        {
            if ($Channel == 'All')
                foreach (self::$_Verbose as $Channel => $V)
                    self::Log($Message, $Verbose, $Channel, $AppendStack);
            else
            {
                if (($Verbose <= self::$_Verbose[$Channel])
                or
                (isset($_SERVER['Verbose']) && $Verbose <= $_SERVER['Verbose']) or self::$_Staring)
                {
    /*                if ((self::Environment() == 'Development') && (self::$_Perfect === true) && $Verbose < LOG_WARNING)
                        trigger_error($Message);*/
    
                    // $Message = self::$_Service.': '.$Message;
                    $Time = sprintf('%.3F', microtime(true)-Started);
    
                    if (self::$_Stack instanceof SplStack)
                        $StackDepth = self::$_Stack->count();
                    else
                        $StackDepth = 0; // FIXME?
                    
                    if (self::$_Stack->offsetExists(1))
                        $Initiator = self::$_Stack->offsetGet(1);
                    else
                        $Initiator = 'Core';
                    
                    $From = '*'.self::$_Service.':'.self::$_Method.'*'.' from *'.$Initiator.'*';
                    
                    if ($Message instanceof Closure)
                        $Message = $Message();
                    
                    if ($Verbose < LOG_NOTICE or $AppendStack)
                        self::$_Log[$Channel][]
                            = [
                                $Verbose,
                                $Time,
                                $Message,
                                $From,
                                $StackDepth,
                                F::Stack(),
                                self::getColor()
                            ];
                    else
                        self::$_Log[$Channel][]
                            = [
                            $Verbose,
                            $Time,
                            $Message,
                            $From,
                            $StackDepth,
                            null,
                            self::getColor()
                        ];
                    
                    if (PHP_SAPI === 'cli')
                        self::CLILog($Time, $Message, $Verbose, $Channel, $AppendStack);
                    
                    if (self::$_Perfect && ($Verbose <= self::$_Options['Codeine']['Perfect Verbose'][$Channel]))
                        self::Finish ($Message);
                        
                }
            }
            
            return $Message;
        }

        public static function CLILog ($Time, $Message, $Verbose, $Channel, $AppendStack = false)
        {
            $Head = '['.getmypid()."] \033[0;90m".$Time."\033[0m"."\t\e[0;36m[".$Channel."]\e[1;37m\t".self::$_Service.":\t";
            
            if (is_scalar($Message))
                ;
            else
                $Message = j($Message);
            
            $Message = str_replace(' *'," \033[0;32m", $Message);
            $Message = str_replace('* ',"\033[1;37m ", $Message);
            $Message = preg_replace('@^\*@Ssum',"\033[0;32m", $Message);
            $Message = preg_replace('@\*$@Ssum',"\033[1;37m ", $Message);
            
            if ($AppendStack)
                $Message.= j(F::Stack());
            
            if (($Verbose <= self::$_Verbose[$Channel]) or !self::$_Live)
                switch (round($Verbose))
                {
                    case LOG_EMERG:
                        fwrite(STDERR, $Head."\033[0;31m ".$Message." \033[0m".PHP_EOL);
                    break;
                    
                    case LOG_ALERT:
                        fwrite(STDERR, $Head."\033[0;31m ".$Message." \033[0m".PHP_EOL);
                    break;

                    case LOG_CRIT:
                        fwrite(STDERR, $Head."\033[0;31m ".$Message." \033[0m".PHP_EOL);
                    break;

                    case LOG_ERR:
                        fwrite(STDERR, $Head."\033[0;31m ".$Message." \033[0m".PHP_EOL);
                    break;

                    case LOG_WARNING:
                        fwrite(STDERR, $Head."\033[0;33m ".$Message." \033[0m".PHP_EOL);
                    break;

                    case LOG_DEBUG:
                        if (self::$_Debug)
                            fwrite(STDERR, $Head."\033[0;90m ".$Message." \033[0m".PHP_EOL);
                    break;

                    case LOG_USER:
                        fwrite(STDERR, $Head."\033[0;96m ".$Message." \033[0m".PHP_EOL);
                    break;

                    case LOG_INFO:
                        fwrite(STDERR, $Head."\033[0;37m ".$Message." \033[0m".PHP_EOL);
                    break;

                    case LOG_NOTICE:
                        fwrite(STDERR, $Head."\033[0;31m ".$Message." \033[0m".PHP_EOL);
                    break;

                    default:
                        fwrite(STDERR, $Head.$Message.PHP_EOL);
                    break;
                }
        }
        
        public static function Logs()
        {
            /*
                $Output = [];
                foreach (self::$_Log as $Channel => $Logs)
                {
                    $Output[$Channel] = [];

                    foreach ($Logs as $Log)
                    {
                        if (($Log[0] <= self::$_Verbose[$Channel])
                            or
                            ((self::Environment() == 'Development') && $Log[0] > 8)
                            or
                            (isset($_SERVER['Verbose']) && $Log[0] <= $_SERVER['Verbose']))
                                $Output[$Channel][] = $Log;

                        if ($Log[0] <= self::$_Options['Codeine']['Panic Verbose'])
                        {
                            $Output[$Channel] = self::$_Log[$Channel];
                            break;
                        }
                    }
                }
                return $Output;
            */

            return self::$_Log;
        }

        public static function Dump($File, $Line, $Call)
        {
            if (PHP_SAPI === 'cli')
            {
                echo PHP_EOL.substr($File, strpos($File, 'Drivers')).'@'.$Line.' '.trim(file($File)[$Line-1]).PHP_EOL;
                echo j($Call).PHP_EOL;
            }
            else
            {
                echo
                    '<div class="console"><div>'
                    .substr($File, strpos($File, 'Drivers'))
                    .'@'
                    .$Line
                    .'</div>'
                    .'<code class="php">'
                    .trim(file($File)[$Line-1])
                    .'</code>'
                    .'<pre><code class="json">'
                    .htmlspecialchars(j($Call))
                    .'</code></pre>';
                echo '</div>';
            }

            return $Call;
        }

        public static function startStaring ()
        {
            self::$_Staring = true;
        }
        
        public static function stopStaring ()
        {
            return self::$_Staring = false;
        }
        
        public static function setFn($Function, $Code = null)
        {
            if (self::$_Service == 'Codeine')
                self::$$Function = $Code;
            else
            {
                $Function = (array) $Function;
                foreach ($Function as $CF)
                    self::$_Code[self::$_Service][$CF] = $Code;

                return $Code;
            }
            return null;
        }

        public static function getFn($Function)
        {
            if (isset(self::$_Code[self::$_Service][$Function]))
                return self::$_Code[self::$_Service][$Function];
            else
                if (isset(self::$_Code[self::$_Service]['Default']))
                    return self::$_Code[self::$_Service]['Default'];

            return null;
        }

        public static function Merge($Array, $Mixin)
        {
            F::Counter('Core:Merge');
            F::Start('Core:Merge');
            
            if ($Mixin === (array) $Mixin) // Если второй аргумент — массив
            {
                if ($Array === $Mixin)
                    ;
                else // Если аргументы не равны
                {
                    if ($Array === (array) $Array) // Если первый аргумент массив
                    {
                        foreach ($Mixin as $MixinKey => $MixinValue) // Проходим по второму
                        {
                            if ($MixinKey[strlen($MixinKey)-1]  === '!') // Если у нас ключ кончается на !
                                $Array[rtrim($MixinKey, '!')] = $MixinValue;
                            // Оверрайд
                            else
                            {
                                // Иначе, обычная замена
                                if (isset($Array[$MixinKey])) // Если ключ из второго массива присутствует в первом
                                {
                                    if ($MixinValue === (array) $MixinValue) // Если значение из второго массива — массив
                                    {
                                        if ($Array[$MixinKey] === $Mixin[$MixinKey]) // Если значения в первом и втором массивах совпадают, ничего не делаем
                                            ;
                                        else
                                        {
                                            $Array[$MixinKey] = self::Merge($Array[$MixinKey], $Mixin[$MixinKey]);
                                        } // Рекурсируем.
                                    }
                                    else
                                        $Array[$MixinKey] = $MixinValue; // Иначе, просто копируем значение

                                    // Строчки повторены, для читаемости
                                }
                                else
                                    $Array[$MixinKey] = $MixinValue; // Иначе, просто копируем значение
                            }
                        }
                    }
                    else
                        $Array = $Mixin; // Если первый аргумент не массив, то мерджить смысла нет.
                }
            }
            F::Stop('Core:Merge');
            return $Array;
        }

        public static function Diff ($First, $Second)
        {
            if (is_array($First) && is_array($Second))
                foreach ($First as $Key => $Value)
                {
/*                    if (isset($Second[$Key]) && $Second[$Key] === $Value)
                        continue;*/

                    if ($Value === '*')
                        ;
                    else
                    {
                        if (!isset($Second[$Key]))
                            $Diff[$Key] = $Value;
                        else
                        {
                            if (is_array($Second[$Key]))
                            {
                                $NewDiff = self::Diff($Value, $Second[$Key]);

                                if ($NewDiff !== null)
                                    $Diff[$Key] = $NewDiff;
                            }
                            else
                            {
                                if ($Second[$Key] != $Value)
                                    $Diff[$Key] = $Value;
                            }
                        }
                    }
                }
            else
                $Diff = ($First == $Second)? null: $Second;

            return !isset($Diff) ? null : $Diff;
        }

        public static function Extract($Array, $Keys, $ID = 'ID')
        {
            $Data = [];

            if (is_array($Array))
            {
                if (is_array($Keys))
                {
                    foreach ($Keys as $Key)
                        if (is_scalar($Key))
                        {
                            $Data[$Key] = array_column($Array, $Key, $ID);
                            // sort($Data[$Key]);
                        }
                }
                else
                {
                    $Data = array_column($Array, $Keys, $ID);
                    // sort($Data);
                }
            }

            return $Data;
        }

        public static function Sort($Array, $Key, $Direction = SORT_ASC)
        {
            $Data = [];
            $Result = [];

            $IC = 0;
            foreach ($Array as $ID => $Row)
                if (self::Dot($Row,$Key) !== null)
                    $Data[$ID] = self::Dot($Row,$Key);
                else
                    $Data[$ID] = $IC--;


            $Direction == SORT_ASC ? asort($Data): arsort($Data);

            foreach ($Data as $Key =>&$Value)
                $Result[$Key] = $Array[$Key];

            return $Result;
        }

        public static function Map ($Array, $Fn, $Data = null, $FullKey = '')
        {
            if (is_array ($Array))
                foreach ($Array as $Key => &$Value)
                {
                    $NewFullKey = is_numeric($Key)? $FullKey.'#': $FullKey.'.'.$Key;

                    $Fn($Key, $Value, $Data, $NewFullKey, $Array);

                    if (is_array ($Value))
                        $Array[$Key] = self::Map ($Value, $Fn, $Data, $NewFullKey, $Array);
                    else
                        $Array[$Key] = $Value;
                }
            else
                $Array = $Fn('', $Array, $Data, $FullKey);

            return $Array;
        }

        public static function Dot ($Array, $Key)
        {
            if (func_num_args() === 3)
            {
                $Value = func_get_arg(2);
                
                return self::WriteDot($Array, $Key, $Value);
            }
            else
                return self::ReadDot($Array, $Key);
        }
        
        public static function CopyDot ($Array, $From, $To)
        {
            return self::WriteDot($Array, $To, self::ReadDot($Array, $From));
        }

        private static function ReadDot($Array, $Key)
        {
            if (isset($Array[$Key]))
                return $Array[$Key];

            if (strpos($Key, '.') !== false)
            {
                $Keys = explode('.', $Key);

                $Tail = $Array;

                foreach ($Keys as $iKey)
                {
                    if (isset($Tail[$iKey]))
                        $Tail = $Tail[$iKey];
                    else
                    {
                        if (isset($Array[$Key]))
                            return $Array[$Key];
                        else
                            return null;
                    }
                }
            }
            else
                $Tail = isset($Array[$Key])? $Array[$Key]: null;

            return $Tail;
        }
        
        private static function WriteDot($Array, $Key, $Value)
        {
            if ($Array === (array) $Array)
            {
                if (strpos($Key, '.') !== false)
                {
                    $Keys = explode('.', $Key);
                    $Key = array_shift($Keys);

                    if (isset($Array[$Key]))
                        ;
                    else
                        $Array[$Key] = [];

                    $Array[$Key] = self::WriteDot($Array[$Key], implode('.', $Keys), $Value);
                }
                else
                {
                    if ($Value === null)
                        unset($Array[$Key]);
                    else
                        $Array[$Key] = $Value;
                }
            }

            return $Array;
        }

        public static function Set ($Key, $Value)
        {
            if (count(self::$_Storage) > self::$_Options['Codeine']['Core Storage Limit'])
                array_shift(self::$_Storage);
            
            return self::$_Storage[$Key] = $Value;
        }

        public static function Get ($Key)
        {
            return (isset(self::$_Storage[$Key]) ? self::$_Storage[$Key]: null);
        }

        public static function Counter ($Key, $Value = 1)
        {
            if (isset(self::$_Counters['C'][$Key]))
                self::$_Counters['C'][$Key]+= $Value;
            else
                self::$_Counters['C'][$Key] = $Value;

            return self::$_Counters['C'][$Key];
        }

        public static function Start ($Key)
        {
            // if (isset(self::$_Performance))
                return self::$_Ticks['T'][$Key] = microtime(true);
        }

        public static function Stop ($Key)
        {
            // if (isset(self::$_Performance))
            {
                if (isset(self::$_Counters['T'][$Key]))
                    return self::$_Counters['T'][$Key] += round((microtime(true) - self::$_Ticks['T'][$Key])*1000, 4);
                else
                {
                    if (isset(self::$_Ticks['T'][$Key]))
                        return self::$_Counters['T'][$Key] = round((microtime(true) - self::$_Ticks['T'][$Key])*1000, 4);
                    else
                        return self::$_Counters['T'][$Key] = 0;
                }
            }
        }

        public static function Time($Key)
        {
            if (isset(self::$_Counters['T'][$Key]))
                return self::$_Counters['T'][$Key];
            else
                return null;
        }

        public static function MStart ($Key)
        {
            return self::$_Ticks['M'][$Key] = memory_get_peak_usage(true);
        }

        public static function MStop ($Key)
        {
            return self::$_Counters['M'][$Key] += memory_get_peak_usage(true) - self::$_Ticks['M'][$Key];
        }

        public static function Memory($Key)
        {
            if (isset(self::$_Counters['M'][$Key]))
                return self::$_Counters['M'][$Key];
            else
                return null;
        }

        public static function getPaths()
        {
            return self::$_Paths;
        }

        public static function file_exists($Filename)
        {
            if (is_scalar($Filename))
                return
                    (isset(self::$_Storage['FE'][$Filename]) ?
                    self::$_Storage['FE'][$Filename]: self::$_Storage['FE'][$Filename] = file_exists($Filename) && is_file($Filename));
            else
                return null;
        }

        public static function findFile($Names)
        {
           $Names = (array) $Names;

           foreach ($Names as $Name)
           {
               if (mb_substr($Name,0,1) == '/' && self::file_exists($Name))
                   return $Name;

               foreach (self::$_Paths as $ic => $Path)
                   if (self::file_exists($Filenames[$ic] = $Path.'/'.$Name))
                       return $Filenames[$ic];
           }

           return null;
        }

        public static function findFiles ($Names)
        {
/*            if ($Results = F::Get($FFID) === null)*/
            {
                $Results = [];
                $Names = (array) $Names;

                foreach (self::$_Paths as $ic => $Path)
                    foreach ($Names as $Name)
                    {
                        if (substr($Name,0,1) == '/' && self::file_exists($Name))
                            return [$Name];

                        if (self::file_exists($Filenames[$ic] = $Path . '/' . $Name))
                            $Results[] = $Filenames[$ic];
                    }

                $Results = array_reverse($Results);

                /*F::Set($FFID, $Results);*/
            }

            if (empty($Results))
                return null;
            else
                return $Results;
        }
    }

    function j($Call)
    {
        if (Environment === 'Development')
            return json_encode($Call, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        else
            return json_encode($Call, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
    }

    /**
     * Alias for json_decode($Value, true)
     * @param string $Call
     * @return mixed the value encoded in <i>json</i> in appropriate
     * PHP type. Values true, false and
     * null (case-insensitive) are returned as <b>TRUE</b>, <b>FALSE</b>
     * and <b>NULL</b> respectively. <b>NULL</b> is returned if the
     * <i>json</i> cannot be decoded.
     */
    function jd($Call)
    {
        return json_decode($Call, true);
    }

    function d()
    {
        if (F::Environment() != 'Production' or PHP_SAPI == 'cli')
            call_user_func_array(['F','Dump'], func_get_args());

        return func_get_arg(2);
    }

    function setFn($Name, $Function)
    {
        return F::setFn($Name, $Function);
    }