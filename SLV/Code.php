<?php
/* 
 * Zend Framework Salvation Project
 * Port of Codeine functionality on ugly Zend Architecture
 * Code Mechanism
 * (c) 2010, BreathLess
 */

class SLV_Code
{
    private static $_included;
    private static $_drivers;
    private static $_codeinePath = '/var/lib/Codeine/Driver';

    public static function init ($drivers)
    {
       self::$_drivers = $drivers;
       if (isset($_ENV['Codeine']))
           self::$_codeinePath = $_ENV['Codeine'].'/Driver';

       return true;
    }

    public static function e ($namespace, $function, $operands = null, $drivers = 'default')
    {
        $result = false;

        $namespace = str_replace(':','/',$namespace);

        $f = false;
        $catched = false;

        if (($drivers == 'default' or empty($drivers)))
        {
            if (isset (self::$_drivers[$namespace]))
                $drivers = self::$_drivers[$namespace];
            else
            {
                $namespace2 = explode('/', $namespace);
                $drivers = $namespace2[count($namespace2)-1];
            }
        }

        if (!is_array($drivers))
            $drivers = array($drivers);

        foreach ($drivers as $driver)
        {
            $f = 'F_'.$driver.'_'.$function;

            if (!isset(self::$_included[$namespace][$driver]))
            {
               $driverFiles =
                array(
                        APPLICATION_PATH.'/drivers/'.$namespace.'/'.$driver.'.php',
                        self::$_codeinePath.'/'.$namespace.'/'.$driver.'.php'
                     );

               foreach ($driverFiles as $driverFile)
                   if (file_exists($driverFile))
                   {
                       if ((include_once ($driverFile)) == true)
                       {
                           if (is_callable($f))
                            {
                                $result = $f ($operands);
                                $catched = true;
                            }
                       }
                   }
            }
            else
            {
                $result = $f ($operands);
                $catched = true;
                break;
            }

        }

//        if (!$Catched)
//            Log::Error($namespace.' '.$Function.' not found');

        return $result;
    }

    public static function EC ($namespace, $Function, $Operands, $Driver = 'Default')
    {
        // TODO Cached Execute
    }

    public static function ER ($namespace, $Function, $Operands, $Driver = 'Default')
    {
        // TODO Remote Execute
    }

    public static function ED ($namespace, $Function, $Operands, $Driver = 'Default')
    {
        // TODO Deferred Execute
    }


}
