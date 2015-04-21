<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Readlines =
            [
                'Project.Name' => 'Project Name [a-z0-9]',
                'Project.Title' => 'Project Title',
                'Project.Team.Maintainer.Name' => 'Maintainer\'s name',
                'Project.Team.Maintainer.Mail' => 'Maintainer\'s mail',
                'Project.Description.Short' => 'Short description of project',
                'Project.Description.Full' => 'Full description of project',
                'Project.Hosts.Development' => 'Development hostname',
                'Project.Hosts.Production' => 'Production hostname',
                'Project.Copyright.Name' => 'Copyright holder name',
                'Project.Copyright.Mail' => 'Copyright holder mail',
                'Project.Copyright.Year' => 'Copyright year',
                'Project.Copyright.License' => 'License'
            ];

        foreach($Readlines as $Key => $Prompt)
        {
            $Value = readline($Prompt.': ');

            if (!empty($Value))
                $Call = F::Dot($Call, $Key, trim($Value));
        }

        $Call['Project']['Events']['Started'] = time();

        $Directory = getcwd().'/'.$Call['Project']['Name'];

        if (file_exists($Directory))
        {
            F::Log('Directory '.$Directory.' exists, removing', LOG_WARNING);
            passthru('rm -rf '.$Call['Project']['Name']);
        }

        if (mkdir($Call['Project']['Name']))
            F::Log('Directory '.$Directory.' created', LOG_WARNING);

        passthru('cp -vr '.Codeine.'/skel/* '.$Directory);
        F::Log('Skel copied', LOG_WARNING);

        file_put_contents($Directory.'/src/Options/Project.json',
            j($Call['Project'],
                JSON_PRETTY_PRINT
                | JSON_NUMERIC_CHECK
                | JSON_UNESCAPED_SLASHES
                | JSON_UNESCAPED_UNICODE));

        exec('find '.$Directory, $Lines);

        foreach ($Lines as $File)
        {
            $File = trim($File);

            if (is_file($File))
            {
                $Data = file_get_contents($File);

                if (preg_match_all('@\$([\.\w]+)@', $Data, $Vars))
                {
                    foreach ($Vars[0] as $IX => $Key)
                        if ($NV = F::Dot($Call,$Vars[1][$IX]) !== null)
                            $Data = str_replace($Key, $NV , $Data);
                }

                file_put_contents($File, $Data);
            }
        }

//        exec ('mv '.$Directory.'/etc/nginx/sites-available/development.conf '
//            .$Directory.'/etc/nginx/sites-enabled/'.$Call['Project']['Name'].'-development.conf');

        return $Call;
    });