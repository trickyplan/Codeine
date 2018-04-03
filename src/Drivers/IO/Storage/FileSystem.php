<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: 
     * @package Codeine
     * @version 8.x
     * @date 13.08.11
     * @time 22:37
     */

    setFn ('Open', function ($Call)
    {
        if (isset($Call['IO']['FileSystem']['Append Host']) && $Call['IO']['FileSystem']['Append Host'])
            $Call['Directory'].= DS.$Call['HTTP']['Host'];

        return $Call['Directory'];
    });

    setFn ('Read', function ($Call)
    {
        $Call = F::Hook('beforeFileSystemOperation', $Call);
            $Call = F::Hook('beforeFileSystemRead', $Call);

                if (isset($Call['Where']))
                    $Call = F::Apply(null, 'Read.File', $Call);
                else
                    $Call = F::Apply(null, 'Read.Directory', $Call);

            $Call = F::Hook('afterFileSystemRead', $Call);
        $Call = F::Hook('afterFileSystemOperation', $Call);

        return $Call['Result'];
    });

    setFn('Read.File', function ($Call)
    {
        foreach ($Call['Where']['ID'] as &$ID)
            $ID = $Call['Link'].DS.$ID;

         $Filenames = F::findFiles($Call['Where']['ID']);

        if ($Filenames !== null)
        {
            $Filenames = array_reverse($Filenames);
            $Call['Result'] = [];

            foreach ($Filenames as $Filename)
            {
                $Call['Result'][] = file_get_contents($Filename);
                F::Log('File *'.$Filename.' loaded ', LOG_DEBUG, 'Administrator');
            }
        }
        else
        {
            F::Log('Not found file: *'.$Call['Where']['ID'][0].'*', LOG_NOTICE, 'Administrator');
            $Call['Result'] = null;
        }

        return $Call;
    });

    setFn('Read.Directory', function ($Call)
    {
        $Directory = new RecursiveDirectoryIterator(Root.'/'.$Call['Path']);
            $Iterator  = new RecursiveIteratorIterator($Directory);
            $Regex     = new RegexIterator($Iterator, '/'.$Call['Prefix'].'(.+)'.$Call['Postfix'].'$/i', RecursiveRegexIterator::GET_MATCH);

            $DirSz = strlen(Root.'/'.$Call['Path']);

            $Call['Result'] = [];

            foreach($Regex as $File)
            {
                $Pathinfo = pathinfo($File[0]);

                if (($Pathinfo['filename'] != '') && ($Pathinfo['filename'] != '.'))
                {
                    $Call['Path'] = substr($Pathinfo['dirname'], $DirSz);

                    $Call['Result'][$Pathinfo['filename']] = file_get_contents($File[0]);
                }
            }

        return $Call;
    });

    setFn ('Write', function ($Call)
    {
        $Call['Result'] = [];

        if (mb_substr($Call['Link'], 0, 1) === '/')
            ;
        else
            $Call['Link'] = Root.DS.$Call['Link'];

        $Call = F::Hook('beforeFileSystemOperation', $Call);

                if (isset($Call['Data']) && ($Call['Data'] != 'null') && ($Call['Data'] != null))
                {
                    foreach ($Call['Where']['ID'] as $Call['Filename'])
                    {
                        $Call['Filename'] = $Call['Link'].DS.$Call['Filename'];

                        $Call = F::Hook('beforeFileSystemWrite', $Call);

                        if (file_put_contents ($Call['Filename'], $Call['Data']) === false)
                        {
                            F::Log('Write to *'.$Call['Storage'].'* failed', LOG_ERR, 'Administrator');
                            $Call['Result'][] = $Call['Data'];
                            
                            if (is_writable($Call['Filename']))
                                ;
                            else
                                F::Log('File *'.$Call['Filename'].'* is not writable', LOG_ERR, 'Administrator');
                        }
                        else
                        {
                            F::Log('File *'.$Call['Filename'].' writed', LOG_INFO, 'Administrator');
                            $Call['Result'][] = null;
                        }

                        $Call = F::Hook('afterFileSystemWrite', $Call);
                    }
                }
                else
                    foreach ($Call['Where']['ID'] as $Call['Filename'])
                        if (file_exists($Call['Link'].DS.$Call['Filename']))
                            $Call['Result'][] = unlink ($Call['Link'].DS.$Call['Filename']);

        $Call = F::Hook('afterFileSystemOperation', $Call);

        return $Call['Result'];
    });


    setFn ('Close', function ($Call)
    {
        return true;
    });

    setFn ('Version', function ($Call)
    {
        $Call = F::Hook('beforeFileSystemOperation', $Call);

        if (empty($Call['Where']['ID']))
            $Call['Result'] = false;
        else
        {
            foreach ($Call['Where']['ID'] as $ID)
            {
                $Filename = F::findFile($Call['Link'].DS.$ID);
                if (F::file_exists ($Filename))
                    return filemtime($Filename);
            }
        }

        return null;
    });

    setFn ('Filename', function ($Call)
    {
        $Call = F::Hook('beforeFileSystemOperation', $Call);
        return DS.array_pop($Call['Where']['ID']);
    });

    setFn ('Exist', function ($Call)
    {
        $Call = F::Hook('beforeFileSystemOperation', $Call);

            if (empty($Call['Where']['ID']))
                $Call['Result'] = false;
            else
            {
                foreach ($Call['Where']['ID'] as $ID)
                {
                    $Filename = F::findFile($Call['Link'].DS.$ID);
                    $Call['Result'] = F::file_exists ($Filename);
                }
            }

        $Call = F::Hook('afterFileSystemOperation', $Call);

        return $Call['Result'];
    });

    setFn('Status', function ($Call)
    {
        $Call = F::Hook('beforeFileSystemOperation', $Call);

        $ic = 0;
        $Directory = new RecursiveDirectoryIterator(Root.'/'.$Call['Path']);
        $Iterator  = new RecursiveIteratorIterator($Directory);
        $Regex     = new RegexIterator($Iterator, '/'.$Call['Prefix'].'(.+)'.$Call['Postfix'].'$/i', RecursiveRegexIterator::GET_MATCH);

        foreach($Regex as $File)
            $ic++;

        $Call = F::Hook('afterFileSystemOperation', $Call);

        return [['Files',  $ic]];
    });

    setFn('Size', function ($Call)
    {
        $Call = F::Hook('beforeFileSystemOperation', $Call);

            chdir(Root);
            $Output = shell_exec('du --max-depth=0 -h '.$Call['Directory']);

            list($Size, ) = explode ("\t",$Output);

        $Call = F::Hook('afterFileSystemOperation', $Call);

        return $Size;
    });