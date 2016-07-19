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
    define ('REQID', Started.rand());

    defined('DS')? null: define('DS', DIRECTORY_SEPARATOR);

    final class F
    {
        private static $_Environment = 'Production';

        private static $_Options = [];
        private static $_Code = [];

        private static $_Service = 'Codeine';
        private static $_Method = 'Do';

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
        private static $_Paths = [];
        public static $_Perfect = false;

        public static function Environment()
        {
            return self::$_Environment;
        }

        public static function Bootstrap ($Call = [])
        {
            self::$_Live = true;
            self::$_Stack = new SplStack();

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
                    self::$_Log[$Channel] = new SplFixedArray(1000);
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
            return self::Live($Call);
        }

        public static function Shutdown()
        {
            self::Stop(self::$_Service . '.' . self::$_Method);

            $E = error_get_last();

            if (empty($E))
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
                    echo $E['message'];
                    echo '<pre>';
                    echo j(self::$_Log);
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
/*            $Method = ($Method == null)? self::$_Method: $Method;*/

            // Если контракт уже не загружен
            if (isset(self::$_Options[$Service]))
                ;
            else
            {
                $Options = [];
                $ServicePath = strtr($Service, '.', '/');
                $Filenames = [];

                if (self::$_Environment != 'Production')
                    $Filenames[] = $Path.DS.$ServicePath.'.'.self::$_Environment.'.json';

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
                    self::Log('No options for *'.$Service.'*', LOG_DEBUG);
                    $Options = [];
                }

                self::$_Options[$Service] = $Options;
            }
            return self::Merge(self::$_Options[$Service], $Call);
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
                    if (!isset($Call['No Memo']) && isset($FnOptions['Contract'][self::$_Service][self::$_Method]['Memo']))
                    {
                        $Memo = [self::$_Service, self::$_Method];

                        foreach ($FnOptions['Contract'][self::$_Service][self::$_Method]['Memo'] as $Key)
                        {
                            $Key = self::Dot($Call, $Key);
                            if (null === $Key)
                                ;
                            else
                                $Memo[] = $Key;
                        }

                        $CacheID = sha1(j($Memo));
                    }

                    $ST = 0;

                    if (isset($CacheID) && ($Result = self::Get($CacheID)) !== null)
                        self::Log(self::$_Service.':'.self::$_Method.'('.$CacheID.') memoized.', LOG_INFO, 'Performance');
                    else
                    {
                        if (isset($FnOptions['Contract'][self::$_Service][self::$_Method]['RTTL']) && !isset($Call['RTTL']))
                            $Call['RTTL'] = $FnOptions['Contract'][self::$_Service][self::$_Method]['RTTL'];

                        if (isset($Call['RTTL']) and isset($CacheID)
                            && isset(self::$_Options['Codeine']['Run Cache Enabled']) && self::$_Options['Codeine']['Run Cache Enabled'] === true)
                        {
                            $RTTL = $Call['RTTL'];
                            unset($Call['RTTL']);

                            $Result = self::Execute('Code.Run.Cached', 'Run',
                                [
                                    'Run' =>
                                        [
                                            'Service' => self::$_Service,
                                            'Method'  => self::$_Method,
                                            'Call'    => $Call,
                                            'CacheID' => $CacheID,
                                            'RTTL'    => $RTTL,
                                            'Memo'    => $Memo
                                        ]
                                ]);
                        }
                        else
                        {
                            $ST = microtime(true);
                            $Result = $F($Call); // Real Run Here
                            $ST = microtime(true)-$ST;
                        }
                    }


                    // if (self::$_Performance)
                    self::Counter(self::$_Service.'.'.self::$_Method);

                    if (!isset($Call['No Memo']) && isset($CacheID) && $ST > self::$_Options['Codeine']['Memo Threshold'])
                        self::Set($CacheID, $Result);
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
                        if (is_array($Variable))
                            foreach ($Variable as $Key => &$cVariable)
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
                        if ($Subvariable !== null)
                            $Variable = str_replace($Pockets[0][$IX], $Subvariable, $Variable);
                    }
                }

            return $Variable;
        }

        public static function Hook($On, $Call)
        {
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
                         }
                     }
                 }
             }

            return $Call;
        }

        public static function Error($errno , $errstr , $errfile , $errline , $errcontext)
        {
            if (self::$_Perfect)
            {
                $Logs = self::Logs();

                echo '<h2>Perfect Mode</h2>';
                echo '<pre class="console">'.self::Stack().'</pre>'.PHP_EOL;
                echo $errno.' '.$errstr.' '.$errfile.'@'.$errline.'<pre>';

                foreach ($Logs as $Channel => $Records)
                    foreach ($Records as $Log)
                        echo $Channel."\t".$Log[1]."\t".$Log[2]."\t".PHP_EOL;

                echo '</pre>';
                die();
            }

            self::Log('<pre>'.self::Stack().'</pre>'.PHP_EOL.'Err '.$errno.':'.$errstr.PHP_EOL.$errfile.'@'.$errline, LOG_CRIT);
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

        public static function Log ($Message, $Verbose = 7, $Channel = 'Developer', $AppendStack = true)
        {
            if (($Verbose <= self::$_Verbose[$Channel])
                or
            (isset($_SERVER['Verbose']) && $Verbose <= $_SERVER['Verbose']) or self::$_Staring)
            {
                if (is_scalar($Message))
                    ;
                else
                    $Message = j($Message,
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

/*                if ((self::Environment() == 'Development') && (self::$_Perfect === true) && $Verbose < LOG_WARNING)
                    trigger_error($Message);*/

                // $Message = self::$_Service.': '.$Message;
                $Time = sprintf('%.3F', microtime(true)-Started);

                if (PHP_SAPI === 'cli')
                {
                    $Head = "\033[0;90m".$Time."\033[0m"."\t[".$Channel."]\t".self::$_Service.":\t";
                    
                    if (($Verbose <= self::$_Verbose[$Channel]) or !self::$_Live)
                        switch (round($Verbose))
                        {
                            case LOG_EMERG:
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
                else
                   self::$_Log[$Channel][]
                        = [
                            $Verbose,
                            $Time,
                            $Message,
                            self::$_Service.':'.self::$_Method,
                            self::$_Stack->count()
                        ];

                if ($Verbose < LOG_NOTICE and $AppendStack)
                    F::Log(F::Stack(), $Verbose, $Channel, false);
            }

            return $Message;
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
            if (PHP_SAPI == 'cli')
            {
                echo PHP_EOL.substr($File, strpos($File, 'Drivers')).'@'.$Line.' '.trim(file($File)[$Line-1]).PHP_EOL;
                echo var_export($Call).PHP_EOL;
            }
            else
            {
                echo '<div class="console"><h5>'.
                    substr($File, strpos($File, 'Drivers')).'@'.$Line.'&nbsp; '.trim(file($File)[$Line-1]).'</h5>';
                var_dump($Call);
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
            F::Start('Merge');
            if (is_array($Mixin)) // Если второй аргумент — массив
            {
                if ($Array === $Mixin)
                    ;
                else // Если аргументы не равны
                {
                    if (is_array($Array)) // Если первый аргумент массив
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
                                    if (is_array($MixinValue)) // Если значение из второго массива — массив
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
            F::Stop('Merge');
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

                if (is_array($Array))
                {
                    if ($Key === (int) $Key)
                        $Key = (int) $Key;

                    if (strpos($Key, '.') !== false)
                    {
                        $Keys = explode('.', $Key);
                        $Key = array_shift($Keys);

                        if (!isset($Array[$Key]))
                            $Array[$Key] = [];

                        $Array[$Key] = self::Dot($Array[$Key], implode('.', $Keys), $Value);
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
            return json_encode($Call, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        else
            return json_encode($Call, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

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