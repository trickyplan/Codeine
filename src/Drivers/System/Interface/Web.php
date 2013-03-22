<?php

    /* Codeine
     * @author BreathLess
     * @description Web Interface 
     * @package Codeine
     * @version 7.1
     */

    setFn ('Run', function ($Call)
    {
        $Call['IP'] = function ($Call) {return F::Run('System.Interface.Web.IP', 'Get', $Call);};
        $Call['UA'] = function ($Call) {return F::Run('System.Interface.Web.UA', 'Get', $Call);};
        $Call['Language'] = function ($Call) {return F::Run('System.Interface.Web', 'DetectUALanguage', $Call);};

        $Call = F::Hook('beforeInterfaceRun', $Call);

        if (!isset($Call['Skip Run']))
        {
             if (isset($_FILES['Data']))
            {
                foreach ($_FILES['Data'] as $Key => $Value)
                    foreach ($Value as $IX => $cValue)
                        foreach ($cValue as $C2 => $V2)
                            $_REQUEST['Data'][$IX][$C2][$Key] = $V2;
                // FUCK!
            }

            $Call['Request'] = $_REQUEST;

            foreach ($Call['Request'] as $Key => $Value)
            {
                unset($Call['Request'][$Key]);
                $Call['Request'] = F::Dot($Call['Request'], strtr($Key, '_','.'), $Value);
            }

            $Call['Cookie'] = $_COOKIE;

            $Call = F::Run(null, 'Protocol', $Call);

            $Call['Run'] = urldecode($_SERVER['REQUEST_URI']);
            $Call['URI'] = urldecode($_SERVER['REQUEST_URI']);
            $Call['URL'] = parse_url($Call['URI'], PHP_URL_PATH);
            $Call['URL Query'] = parse_url($Call['URI'], PHP_URL_QUERY);

            $Call = F::Run($Call['Service'], $Call['Method'], $Call);
        }

        if (isset($Call['Headers']))
            foreach ($Call['Headers'] as $Key => $Value)
                header ($Key . ' ' . $Value);

        $Call = F::Live ($Call['Interface']['Output'], $Call);

        $Call = F::Hook('afterInterfaceRun', $Call);


        return $Call;
    });

    setFn('Output', function ($Call)
    {
        if (is_string($Call['Output']))
            echo $Call['Output'];
        else
            print_r($Call['Output']);

        return $Call;
    });

    setFn ('User.Agent', function ($Call)
    {

    });

    setFn('User.Time', function ($Call)
    {
        $IP = F::Run('System.Interface.Web.IP', 'Get', $Call);

        return F::Run('System.Timezone.PHPGeoIP', 'CountryAndRegion',
            array (
                  'Country' => F::Run('System.GeoIP.PHPGeoIP', 'Country', array ('Value' => $IP)),
                  'Region' => F::Run('System.GeoIP.PHPGeoIP', 'Region', array ('Value' => $IP))
            ));
    });

    setFn ('DetectUALanguage', function ($Call)
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            preg_match_all ('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $Parsed);

            $Languages = array_combine ($Parsed[1], $Parsed[4]);

            foreach ($Languages as $Language => $Q)
                if ($Q === '') $Languages[$Language] = 1;

            arsort ($Languages, SORT_NUMERIC);

            foreach ($Languages as $Language => $Quality)
            {
                if (isset($Call['Languages']['Map'][$Language]))
                    return $Call['Languages']['Map'][$Language];
            }
        }

        return $Call['Languages']['Default'];
    });

    setFn('Redirect', function ($Call)
    {
        $URL = $Call['Location'];

        if (preg_match_all('@\$([\.\w]+)@', $URL, $Vars))
        {
            foreach ($Vars[0] as $IX => $Key)
                $URL = str_replace($Key, F::Dot($Call,$Vars[1][$IX]) , $URL);
        }

        $Call['Headers']['HTTP/1.1'] = ' 301 Moved Permanently';
        $Call['Headers']['Location:'] = $URL;

        return $Call;
    });

    setFn('Protocol', function ($Call)
    {
        if ((isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])) or
                (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
            {
                $Call['Proto'] = 'https://';
                $Call['Host'] = 'https://'.$_SERVER['HTTP_HOST'];
            }
            else
            {
                $Call['Proto'] = 'http://';
                $Call['Host'] = 'http://'.$_SERVER['HTTP_HOST'];
            }

        return $Call;
    });