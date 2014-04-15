<?php

    /*
     * @author BreathLess
     * @description: F Class
     * @package Codeine Framework
     * @subpackage Core
    */
    define ('Codeine', __DIR__);
    define ('Started', microtime(true));
    define ('REQID', Started.rand());

    defined('DS')? null: define('DS', DIRECTORY_SEPARATOR);

    define ('LOG_GOOD', 9);
    define ('LOG_BAD', 10);
    define ('LOG_IMPORTANT', 11);

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
            setlocale(LC_ALL, "ru_RU.UTF-8"); // FIXME

            libxml_use_internal_errors(true);

            if (isset($_SERVER['Environment']))
                self::$_Environment = $_SERVER['Environment'];

            if (isset($Call['Environment']) and null !== $Call['Environment'])
                self::$_Environment = $Call['Environment'];

            if (isset($Call['Paths']))
            {
                if ((array) $Call['Paths'] === $Call['Paths'])
                    self::$_Paths = array_merge($Call['Paths'], [Codeine]);
                else
                    self::$_Paths = [$Call['Paths'], Codeine];
            }
            else
                self::$_Paths = [Codeine];

            self::loadOptions('Codeine');

            date_default_timezone_set(self::$_Options['Codeine']['Timezone']);

            if (isset(self::$_Options['Codeine']['Verbose']))
                self::$_Verbose = self::$_Options['Codeine']['Verbose'];

            if (isset($_REQUEST['Performance']))
                self::$_Performance = true;

            if (isset($_REQUEST['Debug']))
            {
                self::$_Debug = true;
                foreach (self::$_Verbose as &$Level)
                    $Level = PHP_INT_MAX;
            }

            F::Log('Codeine started', LOG_IMPORTANT);

            if (isset(self::$_Options['Codeine']['Overlays']))
                self::$_Paths =
                    array_merge(
                        [Root],
                        self::$_Options['Codeine']['Overlays'],
                        [Codeine]);

            foreach (self::$_Paths as $Path)
                F::Log('Path registered *'.$Path.'*', LOG_INFO);

            set_error_handler ('F::Error');

            F::Log('Environment: *'.self::$_Environment.'*', LOG_INFO);

            $Call = F::Hook('onBootstrap', $Call);

            self::$_Perfect = self::$_Options['Codeine']['Perfect'];

            return F::Live($Call);
        }

        public static function Shutdown($Call = array())
        {
            self::Stop(self::$_Service . '.' . self::$_Method);

            $E = error_get_last();

            if (!empty($E))
            {
                if (self::$_Environment == 'Production')
                {
                    // header ('HTTP/1.1 500 Internal Server Error');
                    // TODO Real error triggering
                }
                else
                {
                    echo $E['message'];
                    echo '<pre>';
                    print_r(self::$_Log);
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
                    F::Log($Filename, LOG_DEBUG);
                    include $Filename;
                }

                return true;
            }
            else
            {
                F::Log('*'.$Service.'* not found', LOG_BAD);
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
                        $Current = json_decode(file_get_contents($Filename), true);

                        if ($Current)
                            F::Log('Options: *'.$Filename.'* loaded', LOG_DEBUG);
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
                            F::Log('JSON Error: ' . $Filename.':'. $JSONError, LOG_CRIT); //FIXME
                            $Current = [];
                        }

                        if (isset($Current['Mixins']) && is_array($Current['Mixins']))
                            foreach($Current['Mixins'] as $Mixin)
                            {
                                F::Log('Options *'.$Filename.'* mixed with *'.$Mixin.'*', LOG_DEBUG);
                                $Current = F::Merge(F::loadOptions($Mixin), $Current);
                            }

                        $Options = self::Merge($Options, $Current);
                    }
                }
                else
                {
                    F::Log('No options for *'.$Service.'*', LOG_DEBUG);
                    $Options = [];
                }

                self::$_Options[$Service] = $Options;
            }
            return F::Merge(self::$_Options[$Service], $Call);
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

        public static function isCall($Call)
        {
            return (((array) $Call === $Call) && isset($Call['Service']));
        }

        public static function Run($Service, $Method = null , $Call = [])
        {
            if (($sz = func_num_args())>3)
                for($ic = 3; $ic < $sz; $ic++)
                    if (is_array($Argument = func_get_arg ($ic)))
                        $Call = F::Merge($Call, $Argument);

            return F::Execute($Service, $Method, $Call);
        }

        public static function Apply($Service, $Method = null , $Call = [])
        {
            if (($sz = func_num_args())>3)
                for($ic = 3; $ic < $sz; $ic++)
                    if (is_array($Argument = func_get_arg ($ic)))
                        $Call = F::Merge($Call, $Argument);

            $Result = F::Execute($Service, $Method, $Call);

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

            self::Stop($OldService. '.' . $OldMethod);
            self::Start(self::$_Service . '.' . self::$_Method);

            self::$_Stack->push(self::$_Service.':'.self::$_Method);

            $FnOptions = self::loadOptions();

            $Call = self::Merge($FnOptions, $Call);

            if ((null === self::getFn(self::$_Method)) && !self::_loadSource(self::$_Service))
                $Result = (is_array($Call) && isset($Call['Fallback']))? $Call['Fallback'] : $Call;
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
                            $Key = F::Dot($Call, $Key);
                            if (null === $Key)
                                ;
                            else
                                $Memo[] = $Key;
                        }

                        $Memo = sha1(serialize($Memo));
                    }

                    if (!isset($Memo) || ($Result = F::Get($Memo)) == null)
                    {
                        if (isset($Call['RTTL']))
                        {
                            $RTTL = $Call['RTTL'];
                            unset($Call['RTTL']);

                            $Result = F::Execute('Code.Run.Cached', 'Run',
                                [
                                    'Run' =>
                                        [
                                            'Service' => self::$_Service,
                                            'Method'  => self::$_Method,
                                            'Call'    => $Call,
                                            'RTTL'    => $RTTL
                                        ]
                                ]);
                        }
                        else
                            $Result = $F($Call);
                        // if (self::$_Performance)
                        self::Counter(self::$_Service.'.'.self::$_Method);
                    }
                    else
                        F::Log(self::$_Service.':'.self::$_Method.' memoized', LOG_DEBUG, 'Administrator');

                    if (!isset($Call['No Memo']) && isset($Memo))
                        F::Set($Memo, $Result);
                }
                else
                    $Result = isset($Call['Fallback']) ? $Call['Fallback'] : null;
            }

            self::Stop(self::$_Service . '.' . self::$_Method);
            self::Start($OldService. '.' . $OldMethod);

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
                $Output[] = str_pad(" ", $IX--).$Element;

            // $Output = '<pre>'.implode(array_reverse($Output)).'</pre>';

            return implode(PHP_EOL, array_reverse($Output));
        }

        public static function Live($Variable, $Call = [])
        {
            if ($Variable instanceof Closure)
                return $Variable($Call);

            if (isset($Variable['NoLive']))
                return $Variable;

            if (self::isCall($Variable))
            {
                if (($sz = func_num_args())>2)
                {
                    for($ic = 2; $ic<$sz; $ic++)
                        if (is_array($Argument = func_get_arg ($ic)))
                            $Call = F::Merge($Call, $Argument);
                }

                if (!isset($Variable['Method']))
                    $Variable['Method'] = 'Do';

                return F::Run($Variable['Service'], $Variable['Method'],
                    $Call, isset($Variable['Call'])? self::Variable($Variable['Call'], $Call): []);

                // FIXME?
            }
            else
            {
                if ((array) $Variable === $Variable)
                    foreach ($Variable as $Key => &$cVariable)
                        $Variable = F::Dot($Variable, $Key, self::Live($cVariable, $Call));
                else
                    $Variable = self::Variable($Variable, $Call);

                return $Variable;
            }
        }

        public static function Variable ($Variable, $Call)
        {
            if (is_array($Variable))
                foreach ($Variable as &$cVariable)
                    $cVariable = self::Variable($cVariable, $Call);
            else
                if (preg_match_all('@\$([\w\.]+)@Ssu', $Variable, $Pockets))
                {
                    foreach ($Pockets[1] as $IX => $Match)
                    {
                        $Subvariable = F::Dot($Call, $Match);
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

             if (isset($Call['Hooks']) && ($Hooks = F::Dot($Call, 'Hooks.' . $On)) && (!isset($Call['No'][$On])))
             {
                 if (count($Hooks) > 0)
                 {
                     $Hooks = F::Sort($Hooks, 'Weight', SORT_DESC);
                     foreach ($Hooks as $HookName => $Hook)
                     {
                         if (substr($HookName,0,1) != '-')
                         {
                             F::Log($On.':'.$HookName, LOG_DEBUG);

                             if (F::isCall($Hook))
                             {
                                 if (!isset($Hook['Method']))
                                     $Hook['Method'] = 'Do';

                                 $Call = F::Apply($Hook['Service'],$Hook['Method'], isset($Hook['Call'])? $Hook['Call']: [], $Call, ['On' => $On]);
                             }
                             else
                                 $Call = F::Merge($Call, $Hook);
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
                $Logs = F::Logs();

                echo '<h2>Perfect Mode</h2>';
                echo '<pre>'.self::Stack().'</pre>'.PHP_EOL;
                echo $errno.' '.$errstr.' '.$errfile.'@'.$errline.'<pre>';



                foreach ($Logs as $Channel => $Records)
                    foreach ($Records as $Log)
                        echo $Channel."\t".$Log[1]."\t".$Log[2]."\t".PHP_EOL;

                echo '</pre>';
                die();
            }

            F::Log(F::Stack().PHP_EOL.$errno.' '.$errstr.' '.$errfile.'@'.$errline, LOG_CRIT);
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
                F::loadOptions($Service);

            foreach (self::$_Code as $Service)
                F::_loadSource($Service);

            return true;
        }

        public static function Log ($Message, $Verbose = 7, $Channel = 'Developer')
        {
            if (($Verbose <= self::$_Verbose[$Channel])
                or
               ((F::Environment() == 'Development') && $Verbose > 8)
                or
                (isset($_SERVER['Verbose']) && $Verbose <= $_SERVER['Verbose']))
            {
                if (!is_string($Message))
                    $Message = json_encode($Message,
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                $Time = date('d.m.Y H:i:s').' '.sprintf('%.4f', microtime(true)-Started).' ';

                if (PHP_SAPI == 'cli')
                {
                    switch (round($Verbose))
                    {
                        case LOG_EMERG:
                            fwrite(STDERR, $Time.$Channel.": \033[0;31m ".$Message." \033[0m".PHP_EOL);
                        break;

                        case LOG_CRIT:
                            fwrite(STDERR, $Time.$Channel.": \033[0;31m ".$Message." \033[0m".PHP_EOL);
                        break;

                        case LOG_ERR:
                            fwrite(STDERR, $Time.$Channel.": \033[0;31m ".$Message." \033[0m".PHP_EOL);
                        break;

                        case LOG_WARNING:
                            fwrite(STDERR, $Time.$Channel.": \033[0;33m ".$Message." \033[0m".PHP_EOL);
                        break;

                        case LOG_DEBUG:
                            fwrite(STDERR, $Time.$Channel.": \033[0;37m ".$Message." \033[0m".PHP_EOL);
                        break;

                        case LOG_USER:
                            fwrite(STDERR, $Time.$Channel.": \033[0;37m ".$Message." \033[0m".PHP_EOL);
                        break;

                        case LOG_INFO:
                            fwrite(STDERR, $Time.$Channel.": \033[1;34m ".$Message." \033[0m".PHP_EOL);
                        break;

                        case LOG_IMPORTANT:
                            fwrite(STDERR, $Time.$Channel."> \033[0;31m ".$Message." \033[0m".PHP_EOL);
                        break;

                        default:
                            fwrite(STDERR, $Channel.'> '.$Message.PHP_EOL);
                        break;
                    }
                }
                else
                {
                    self::$_Log[$Channel][]
                        = [
                        $Verbose,
                        round(microtime(true) - Started, 3),
                        $Message,
                        self::$_Service.':'.self::$_Method];
                }
            }

            return $Message;
        }

        public static function Logs()
        {
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
        }

        public static function getFn($Function)
        {
            if (isset(self::$_Code[self::$_Service][$Function]))
                return self::$_Code[self::$_Service][$Function];
            else
                if (isset(self::$_Code[self::$_Service]['Default']))
                    return self::$_Code[self::$_Service]['Default'];


        }

        public static function Merge($Array, $Mixin)
        {
            if ((array) $Mixin === $Mixin) // Если второй аргумент — массив
            {
                if ($Array === $Mixin)
                    ;
                else // Если аргументы не равны
                {
                    if ((array) $Array === $Array) // Если первый аргумент массив
                    {
                        foreach ($Mixin as $MixinKey => $MixinValue) // Проходим по второму
                        {
                            if (preg_match('/(.*)!$/Ssu', $MixinKey, $Pockets)) // Если у нас ключ кончается на !
                                $Array[$Pockets[1]] = $MixinValue;
                            // Оверрайд
                            else
                            {
                                // Иначе, обычная замена
                                if (isset($Array[$MixinKey])) // Если ключ из второго массива присутствует в первом
                                {
                                    if ((array) $MixinValue === $MixinValue) // Если значение из второго массива — массив
                                    {
                                        if ($Array[$MixinKey] === $Mixin[$MixinKey]) // Если значения в первом и втором массивах совпадают, ничего не делаем
                                            ;
                                        else
                                            $Array[$MixinKey] = self::Merge($Array[$MixinKey], $Mixin[$MixinKey]); // Рекурсируем.
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

            return $Array;
        }

        public static function Diff ($First, $Second)
        {
            if (((array) $First === $First) && ((array) $Second === $Second))
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
                            if ((array) $Second[$Key] === $Second[$Key])
                            {
                                $NewDiff = F::Diff($Value, $Second[$Key]);

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
            $Keys = (array) $Keys;

            if (is_array($Array))
                foreach ($Keys as $Key)
                    if (is_scalar($Key))
                        $Data[$Key] = array_column($Array, $Key, $ID);

            return $Data;
        }

        public static function Sort($Array, $Key, $Direction = SORT_ASC)
        {
            $Data = [];
            $Result = [];

            $IC = 0;
            foreach ($Array as $ID => $Row)
                if (F::Dot($Row,$Key) !== null)
                    $Data[$ID] = F::Dot($Row,$Key);
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
            if (func_num_args() == 3)
            {
                $Value = func_get_arg(2);

                if (is_array($Array))
                {
                    if (is_numeric($Key))
                        $Key = (int) $Key;

                    if (strpos($Key, '.') !== false)
                    {
                        $Keys = explode('.', $Key);
                        $Key = array_shift($Keys);

                        if (!isset($Array[$Key]))
                            $Array[$Key] = [];

                        $Array[$Key] = F::Dot($Array[$Key], implode('.', $Keys), $Value);
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

            if (strpos($Key, '.') !== false)
            {
                $Keys = explode('.', $Key);

                $Tail = $Array;

                foreach ($Keys as $iKey)
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

        private static function Start ($Key)
        {
            // if (isset(self::$_Performance))
                return self::$_Ticks['T'][$Key] = microtime(true);
        }

        private static function Stop ($Key)
        {
            // if (isset(self::$_Performance))
            {
                if (isset(self::$_Counters['T'][$Key]))
                    return self::$_Counters['T'][$Key] += round((microtime(true) - self::$_Ticks['T'][$Key])*1000,2);
                else
                {
                    if (isset(self::$_Ticks['T'][$Key]))
                        return self::$_Counters['T'][$Key] = round((microtime(true) - self::$_Ticks['T'][$Key])*1000,2);
                    else
                        return self::$_Counters['T'][$Key] = 0;
                }
            }
        }

        private static function Snapshot ($Call = [])
        {
            return j($Call);
        }

        private static function MStart ($Key)
        {
            return self::$_Ticks['M'][$Key] = memory_get_peak_usage(true);
        }

        private static function MStop ($Key)
        {
            return self::$_Counters['M'][$Key] += memory_get_peak_usage(true) - self::$_Ticks['M'][$Key];
        }

        public static function getPaths()
        {
            return self::$_Paths;
        }

        public static function file_exists($Filename)
        {
            return
                (isset(self::$_Storage['FE'][$Filename]) ?
                self::$_Storage['FE'][$Filename]: self::$_Storage['FE'][$Filename] = file_exists($Filename) && is_file($Filename));
        }

        public static function findFile($Names)
        {
           $Names = (array) $Names;

           foreach ($Names as $Name)
           {
               if (mb_substr($Name,0,1) == '/' && F::file_exists($Name))
                   return $Name;

               foreach (self::$_Paths as $ic => $Path)
                   if (F::file_exists($Filenames[$ic] = $Path.'/'.$Name))
                       return $Filenames[$ic];
           }

           return null;
        }

        public static function findFiles ($Names)
        {
            $Results = [];

            $Names = (array) $Names;

            foreach (self::$_Paths as $ic => $Path)
                foreach ($Names as $Name)
                {
                    if (substr($Name,0,1) == '/' && F::file_exists($Name))
                        return [$Name];

                    if (F::file_exists($Filenames[$ic] = $Path . '/' . $Name))
                        $Results[] = $Filenames[$ic];
                }

            $Results = array_reverse($Results);

            if (empty($Results))
                return null;
            else
                return $Results;
        }
    }

    function j($Call)
    {
        return json_encode($Call, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
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