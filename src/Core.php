<?php

    /*
     * @author BreathLess
     * @description: F Class
     * @package Codeine Framework
     * @subpackage Core
          */
    define ('Codeine', __DIR__);
    define ('REQID', microtime(true).rand());
    define ('DS', DIRECTORY_SEPARATOR);
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
        private static $_Memory= 0;

        private static $_SR71 = false;  // Internal Profiler
        private static $_Prism = false; // Prism records calls ))
        private static $_Calls;

        private static $NC = 0;
        private static $_Verbose = 3;


        public static function Environment()
        {
            return self::$_Environment;
        }

        public static function Bootstrap ($Call = null)
        {
            self::$_Live = true;

            if (isset($_REQUEST['SR71']))
                self::$_SR71 = true;

            if (isset($_REQUEST['Prism']))
                self::$_Prism = true;

            self::Start(self::$_Service . '.' . self::$_Method);

            mb_internal_encoding('UTF-8');
            libxml_use_internal_errors(true);
            setlocale(LC_ALL, "ru_RU.UTF-8");

            if (isset($_SERVER['Environment']))
                self::$_Environment = $_SERVER['Environment'];

            if (isset($Call['Environment']))
                self::$_Environment = $Call['Environment'];


            if (isset($Call['Path']))
            {
                if ((array) $Call['Path'] === $Call['Path'])
                    self::$_Options['Path'] = array_merge($Call['Path'], [Codeine]);
                else
                    self::$_Options['Path'] = [$Call['Path'], Codeine];
            }
            else
                self::$_Options['Path'] = [Codeine];

            if (isset($_COOKIE['Experiment']))
                if (isset(self::$_Options['Experiments'][$_COOKIE['Experiment']]))
                    self::$_Options['Path'][] = Root.'/Labs/'.self::$_Options['Experiments'][$_COOKIE['Experiment']];

            $Call = F::Merge($Call, self::loadOptions('Codeine'));

            self::$_Verbose = self::$_Options['Codeine']['Verbose'];

            set_error_handler ('F::Error');

            register_shutdown_function('F::Shutdown');
F::Log('Codeine started', LOG_IMPORTANT);
            F::Log('Environment: *'.self::$_Environment.'*', LOG_INFO);

            $Call = F::Hook('onBootstrap', $Call);

            return F::Live($Call);
        }

        public static function Merge($First, $Second)
        {
            if ((array) $Second === $Second)
            {
                if ($First !== $Second)
                {
                    if ((array) $First === $First)
                    {
                        foreach ($Second as $Key => &$Value)
                        {
                            if (substr($Key, strlen($Key)-1, 1) == '!')
                                $First[substr($Key, 0, strlen($Key) -1)] = $Value;
                            else
                            {
                                if (isset($First[$Key]) && ((array)$Value === $Value))
                                    $First[$Key] = self::Merge($First[$Key], $Second[$Key]);
                                else
                                    $First[$Key] = $Value;
                            }
                        }

                    }
                    else
                        $First = $Second;
                }
            }

            return $First;
        }

        public static function Diff ($First, $Second)
        {
            if ((array) $First === $First)
                foreach ($First as $Key => $Value)
                {
                    if ($Value !== '*')
                    {
                        if ((array) $Value === $Value)
                        {
                            if (!isset($Second[$Key]))
                                $Diff[$Key] = $Value;
                            elseif (!is_array($Second[$Key]))
                                $Diff[$Key] = $Value;
                            else
                            {
                                $NewDiff = F::Diff($Value, $Second[$Key]);

                                if ($NewDiff !== null)
                                    $Diff[$Key] = $NewDiff;
                            }
                        }
                        elseif (!isset($Second[$Key]) || $Second[$Key] !==  $Value)
                        {
                            $Diff[$Key] = $Value;
                        }
                    }
                }

            return !isset($Diff) ? null : $Diff;
        }

        public static function findFile($Names)
        {
           $Names = (array) $Names;

           foreach ($Names as $Name)
           {
               if (mb_substr($Name,0,1) == '/' && F::file_exists($Name))
                   return $Name;

               foreach (self::$_Options['Path'] as $ic => $Path)
                   if (F::file_exists($Filenames[$ic] = $Path.'/'.$Name))
                       return $Filenames[$ic];
           }

           return null;
        }

        public static function findFiles ($Names)
        {
            $Results = [];

            $Names = (array) $Names;

            foreach ($Names as $Name)
            {
                if (substr($Name,0,1) == '/' && F::file_exists($Name))
                    return $Name;

                foreach (self::$_Options['Path'] as $ic => $Path)
                    if (F::file_exists($Filenames[$ic] = $Path . '/' . $Name))
                        $Results[] = $Filenames[$ic];
            }

            $Results = array_reverse($Results);

            if (empty($Results))
                return null;
            else
                return $Results;
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
                F::Log($Service.' not found', LOG_INFO);
                return false;
            }
        }

        public static function isCall($Call)
        {
            return (((array) $Call === $Call) && isset($Call['Service']));
        }

        public static function hashCall($Call)
        {
            if (self::isCall($Call))
                return sha1(serialize($Call));
            else
                return serialize($Call);
        }

        public static function Run($Service, $Method = null , $Call = [])
        {
            if (($sz = func_num_args())>3)
            {
                for($ic = 3; $ic<$sz; $ic++)
                    if (is_array($Argument = func_get_arg ($ic)))
                        $Call = F::Merge($Call, $Argument);
            }

            $Result = F::Execute($Service, $Method, $Call);

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

/*            if (($OldService == $Service) && ($OldMethod == $Method))
                self::$_Overflow++;
            else
                self::$_Overflow = 0;*/


            $FnOptions = self::loadOptions();

            $Call = self::Merge($FnOptions, $Call);
            $FnOptions = [];

            if ((null === self::getFn(self::$_Method)) && !self::_loadSource(self::$_Service))
                $Result = (is_array($Call) && isset($Call['Fallback']))? $Call['Fallback'] : $Call;
            else
            {
                $F = self::getFn(self::$_Method);

                if (is_callable($F))
                {
                    if (self::$_SR71)
                    {
                        self::Stop($OldService. '.' . $OldMethod);
                        self::Start(self::$_Service . '.' . self::$_Method);
                    }

                    $Result = $F(F::Merge($Call, $FnOptions));

                    if (self::$_SR71)
                    {
                        self::$_Memory = memory_get_usage(true);
                        self::Counter(self::$_Service.'.'.self::$_Method);
                        self::Stop(self::$_Service . '.' . self::$_Method);
                        self::Start($OldService. '.' . $OldMethod);
                    }
                }
                else
                    $Result = isset($Call['Fallback']) ? $Call['Fallback'] : null;

            }

            self::$_Service = $OldService;
            self::$_Method = $OldMethod;

            self::$NC++;

            return $Result;
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
                    $Call, isset($Variable['Call'])? $Variable['Call']: []);

                // FIXME?
            }
            else
            {
                if ((array) $Variable === $Variable)
                    foreach ($Variable as $Key => &$cVariable)
                        $Variable = F::Dot($Variable, $Key, self::Live($cVariable, $Call));
                else
                    if ('$' == substr($Variable, 0, 1))
                        $Variable = F::Dot($Call, substr($Variable, 1));

                return $Variable;
            }
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

                return $Tail;
            }
            else
                return isset($Array[$Key])? $Array[$Key]: null;
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

                                 $Call = F::Run($Hook['Service'],$Hook['Method'], isset($Hook['Call'])? $Hook['Call']: [], $Call, ['On' => $On]);
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
            return F::Log($errno.' '.$errstr.' '.$errfile.'@'.$errline, LOG_CRIT);
        }

        /*
         * Verbosity
         *
         * 128 - Good News
         * 7 - Debug
         * 6 - Notice
         * 5 - Warning
         * 4 - Error
         * 3 - Failure
         * 2 - Critical
         * 1 - Emergency
         * 0 - Apocalypse
         */

        public static function Log ($Message, $Verbose = LOG_INFO, $Target = 'Developer')
        {
            if ($Verbose <= self::$_Verbose or (self::$_Environment == 'Development' && $Verbose > 7))
            {
                if (!is_string($Message))
                    $Message = json_encode($Message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                if (PHP_SAPI == 'cli')
                {
                    switch ($Verbose)
                    {
                        case LOG_ERR:
                            echo $Target." \033[31 ".$Message.PHP_EOL." \033[31m";
                        break;

                        case LOG_WARNING:
                            echo $Target." \033[33 ".$Message.PHP_EOL." \033[33m";
                        break;

                        case LOG_DEBUG:
                        {
                            echo $Target." \033[30 ".$Message.PHP_EOL." \033[30m";
                        }

                        case LOG_USER:
                        {
                            echo $Target." \033[37 ".$Message.PHP_EOL." \033[37m";
                        }

                        default:
                            echo $Target.' '.$Message.PHP_EOL;
                        break;
                    }
                }
                else
                    return self::$_Log[$Target][] = [$Verbose, round(microtime(true) - self::$_Ticks['T']['Codeine.Do'], 3), $Message, self::$_Service];
            }
        }

        public static function Logs()
        {
            return self::$_Log;
        }

        public static function loadOptions($Service = null, $Method = null, $Call = [])
        {
            $Service = ($Service == null)? self::$_Service: $Service;
/*            $Method = ($Method == null)? self::$_Method: $Method;*/

            // Если контракт уже не загружен
            if (!isset(self::$_Options[$Service]))
            {
                $Options = [];

                $ServicePath = strtr($Service, '.', '/');

                $Filenames = [];

                if (self::$_Environment != 'Production')
                    $Filenames[] = 'Options/'.$ServicePath.'.'.self::$_Environment.'.json';

                $Filenames[] = 'Options/'.$ServicePath.'.json';

                if (($Filenames = self::findFiles ($Filenames)) !== null)
                {
                    foreach ($Filenames as $Filename)
                    {
                        $Current = json_decode(file_get_contents($Filename), true);
                        F::Log($Filename, LOG_DEBUG);

                        if ($Filename && !$Current)
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

                            trigger_error('JSON Error: ' . $Filename.':'. $JSONError); //FIXME
                            return null;
                        }

                        if (isset($Current['Mixins']))
                        {
                            foreach($Current['Mixins'] as &$Mixin)
                                $Current = F::Merge(F::loadOptions($Mixin), $Current);
                        }

                        $Options = self::Merge($Options, $Current);
                    }
                }
                else
                    $Options = [];

                self::$_Options[$Service] = $Options;
            }

            return F::Merge(self::$_Options[$Service], $Call);
        }

        public static function Dump($File, $Line, $Call)
        {
            echo '<div class="console"><h5>'.substr($File, strpos($File, 'Drivers')).'@'.$Line.'&nbsp; '.trim(file($File)[$Line-1]).'</h5>';

            var_dump($Call);

            echo '</div>';

            return $Call;
        }

        public static function Set ($Key, $Value)
        {
            return self::$_Storage[$Key] = $Value;
        }

        public static function Get ($Key)
        {
            return (isset(self::$_Storage[$Key]) ? self::$_Storage[$Key]: null);
        }

        public static function file_exists($Filename)
        {
            return
                (isset(self::$_Storage['FE'][$Filename]) ?
                self::$_Storage['FE'][$Filename]: self::$_Storage['FE'][$Filename] = file_exists($Filename));
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
            return self::$_Ticks['T'][$Key] = microtime(true);
        }

        private static function Stop ($Key)
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

        private static function MStart ($Key)
        {
            return self::$_Ticks['M'][$Key] = memory_get_peak_usage(true);
        }

        private static function MStop ($Key)
        {
            return self::$_Counters['M'][$Key] += memory_get_peak_usage(true) - self::$_Ticks['M'][$Key];
        }

        private static function Prism()
        {
            return file_put_contents('/tmp/codeine'.REQID, json_encode(self::$_Calls, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        private static function SR71()
        {
           $Summary['Time'] = array_sum(self::$_Counters['T']);
           $Summary['Calls'] = array_sum(self::$_Counters['C']);

           arsort(self::$_Counters['T']);

           echo '
           <table class="table console">
           <tr>
                <th>Function name</th>
                <th>Time, %</th>
                <th>Calls, %</th>
                <th>Time, ms</th>
                <th>Calls, count</th>
                <th>Time per call, ms</th>
           </tr>
           <tr>
               <th>'.$_SERVER['REQUEST_URI'].'</th>
               <th>100%</th>
               <th>100%</th>
               <th>'.round($Summary['Time']).'</th>
               <th>'.$Summary['Calls'].'</th>
               <th>'.round($Summary['Time']/$Summary['Calls'],2).'</th>
           </tr>';

           foreach (self::$_Counters['T'] as $Key => $Value)
               echo '<tr><td>'.$Key.'</td>'.
                    '<td>'.round(($Value/$Summary['Time'])*100,2).'</td>'.
                    '<td>'.round((self::$_Counters['C'][$Key]/$Summary['Calls'])*100,2).
                    '<td>'.round($Value).'</td>'.
                    '<td>'.self::$_Counters['C'][$Key].'</td>'.
                   '<td>'.round($Value/self::$_Counters['C'][$Key], 2).'</td>'.
                    '</td></tr>';

           echo '</table>';
        }

        public static function setLive($Live)
        {
            return self::$_Live = (bool) $Live;
        }

        public static function getLive()
        {
            return self::$_Live;
        }

        public static function Reload ()
        {
            foreach (self::$_Options as $Service)
                F::loadOptions($Service);

            foreach (self::$_Code as $Service)
                F::_loadSource($Service);

            return true;
        }

        public static function Shutdown()
        {
            // foreach (self::$_Log as $Line)
            //    echo implode ("\t", $Line).PHP_EOL;

            self::Stop(self::$_Service . '.' . self::$_Method);

            $E = error_get_last();

            if (!empty($E))
            {
                if (self::$_Environment == 'Production')
                {
                    header ('HTTP/1.0 500 Internal Server Error');
                    // TODO Real error triggering
                }
                else
                {
                    echo $E['message'];
                    echo '<pre>';
                    print_r(self::$_Log);
                }

            }

            if (self::$_SR71)
            {
                self::$_Memory = memory_get_usage();
                self::SR71();
            }

            if (self::$_Prism)
            {
                self::Prism();
            }

            return false;
        }
    }

    function d()
    {
        if (F::Environment() != 'Production')
            call_user_func_array(['F','Dump'], func_get_args());

        return func_get_arg(2);
    }

    function setFn($Name, $Function)
    {
        return F::setFn($Name, $Function);
    }