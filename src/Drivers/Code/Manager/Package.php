<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Definition', function ($Call)
    {
        $Contract = F::findFile('Options/'.strtr($Call['Value'], '.', '/').'.json');
        $Driver = F::findFile('Drivers/'.strtr($Call['Value'], '.', '/').'.php');

        if ($Contract)
        {
            $Contract = json_decode(file_get_contents($Contract), true);


            if (isset($Contract['Package']))
            {
                $Contract['Package']['Updated'] = filemtime($Driver);
                $Contract['Package']['SHA1'] = sha1_file($Driver);
                $Contract['Package']['Size'] = filesize($Driver);
                return $Contract['Package'];
            }
        }

        return null;
    });

    self::setFn ('Fetch', function ($Call)
    {
        $Packages = json_decode(file_get_contents(Root.'/Data/Foreign.json'), true);

        $Packages = $Packages[$Call['ID']];

        if (isset($Call['Source']))
            $Source = $Call['Source'];
        else
        {
            $Source = array_keys($Packages);
            $Source = $Source[0];
        }

        $Filename = Root.'/Drivers/'.strtr($Call['ID'],'.', '/').'.php';
        $Pathname = pathinfo($Filename);

        if(!is_dir($Pathname['dirname']))
            mkdir($Pathname['dirname'], 0777, true);

        $Source = file_get_contents($Source.'/_N/Code.Manager.Package/_F/Get/ID/'.$Call['ID']);
        file_put_contents($Filename, $Source);

        return $Call;
    });

    self::setFn ('Get', function ($Call)
    {
        $Call['Renderer'] = 'File';
        $Call['Value'] = file_get_contents(F::findFile('Drivers/'.strtr($Call['ID'], '.', '/').'.php'));
        return $Call;
    });