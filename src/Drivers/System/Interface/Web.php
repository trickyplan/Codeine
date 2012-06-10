<?php

    /* Codeine
     * @author BreathLess
     * @description Web Interface 
     * @package Codeine
     * @version 7.1
     */

    self::setFn ('Run', function ($Call)
    {
        $Call = F::Hook('beforeRun', $Call);

        if (!isset($Call['SkipRun']))
        {
            if (isset($_FILES))
                $Call['Request'] = F::Merge($_POST, $_FILES);
            else
                $Call['Request'] = $_POST;

            $Call['Run'] = urldecode($_SERVER['REQUEST_URI']);

            $Call = F::Run($Call['Service'], $Call['Method'], $Call);

            if (isset($Call['Headers']))
                foreach ($Call['Headers'] as $Key => $Value)
                    header ($Key . ' ' . $Value);

            F::Log(F::Speed());

            echo $Call['Output'];
        }

        $Call = F::Hook('afterRun', $Call);

        return $Call;
    });

    self::setFn ('User.Agent', function ($Call)
    {

    });

    self::setFn('User.Geo', function ($Call)
    {
        return F::Run('System.GeoIP.PHPGeoIP', 'Country',
            array(
                    'Value' => F::Run('System.Interface.Web.IP', 'Get', $Call
                 )));
    });

    self::setFn('User.Time', function ($Call)
    {
        $IP = F::Run('System.Interface.Web.IP', 'Get', $Call);

        return F::Run('System.Timezone.PHPGeoIP', 'CountryAndRegion',
            array (
                  'Country' => d(__FILE__, __LINE__, F::Run('System.GeoIP.PHPGeoIP', 'Country', array ('Value' => $IP))),
                  'Region' => d(__FILE__, __LINE__, F::Run('System.GeoIP.PHPGeoIP', 'Region', array ('Value' => $IP)))
            ));
    });

    self::setFn ('DetectUALanguage', function ($Call)
    {
        preg_match_all ('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $Parsed);

        $Languages = array_combine ($Parsed[1], $Parsed[4]);

        foreach ($Languages as $Language => $Q)
            if ($Q === '') $Languages[$Language] = 1;

        arsort ($Languages, SORT_NUMERIC);

        foreach ($Languages as $Language => $Quality)
        {
            if (isset($Call['Language']['Map'][$Language]))
                return $Call['Language']['Map'][$Language];
        }

        return $Call['Language']['Default'];
    });

    self::setFn('Redirect', function ($Call)
    {
        $URL = $Call['Location'];

        if (preg_match_all('/\$(\S+)/', $URL, $Vars))
            foreach ($Vars[0] as $IX => $Key)
                $URL = str_replace($Key, F::Dot($Call,$Vars[1][$IX]) , $URL);

        $Call['Headers']['Location:'] = $URL;

        return $Call;
    });