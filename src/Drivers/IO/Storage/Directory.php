<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.x
     * @date 13.08.11
     * @time 22:37
     */

    setFn ('Open', function ($Call)
    {
        return $Call['Directory'];
    });

    setFn ('Read', function ($Call)
    {
        if (!isset($Call['Scope']))
            $Call['Scope'] = 'Default';
        else
            if (is_array($Call['Scope']))
                $Call['Scope'] = implode(DS, $Call['Scope']);

        $Postfix = isset($Call['Suffix']) ? $Call['Suffix'] : '';
        $Prefix = isset($Call['Prefix']) ? $Call['Prefix'] : '';
        $Path = $Call['Link'].'/'.$Call['Scope'].'/';

        if (isset($Call['Where']))
        {
            $Call['Where']['ID'] = (array) $Call['Where']['ID'];

            foreach ($Call['Where']['ID'] as &$ID)
            {
                if (isset($Call['Directory Hashing']['Enabled']) && $Call['Directory Hashing']['Enabled'])
                {
                    $Hash = sha1($ID);
                    for ($IX = 0; $IX < $Call['Directory Hashing']['Levels']; $IX++)
                        $Prefix .= mb_substr($Hash, $IX, $Call['Directory Hashing']['Size']).DS;
                }

                $ID = $Path.$Prefix.$ID.$Postfix;
            }

            $Filenames = F::findFiles($Call['Where']['ID']);

            if ($Filenames !== null)
            {
                $Filenames = array_reverse($Filenames);
                $Result = [];

                foreach ($Filenames as $Filename)
                {
                    $Result[] = file_get_contents($Filename);
                    F::Log('File *'.$Filename.' loaded ', LOG_DEBUG, 'Administrator');
                }

                return $Result;
            }
            else
            {
                F::Log('Not found file: *'.$Call['Where']['ID'][0].'*', $Call['Verbosity']['Not found'], 'Administrator');
                return null;
            }
        }
        else
        {
            $Directory = new RecursiveDirectoryIterator(Root.'/'.$Path);
            $Iterator  = new RecursiveIteratorIterator($Directory);
            $Regex     = new RegexIterator($Iterator, '/'.$Prefix.'(.+)'.$Postfix.'$/i', RecursiveRegexIterator::GET_MATCH);

            $DirSz = strlen(Root.'/'.$Path);

            $Data = [];

            foreach($Regex as $File)
            {
                $Pathinfo = pathinfo($File[0]);

                if (($Pathinfo['filename'] != '') && ($Pathinfo['filename'] != '.'))
                {
                    $Path = substr($Pathinfo['dirname'], $DirSz);

                    $Data[$Pathinfo['filename']] = file_get_contents($File[0]);
                }
            }

            return $Data;
        }

    });

    setFn ('Write', function ($Call)
    {
        if (!isset($Call['Scope']))
            $Call['Scope'] = 'Default';
        else
            if (is_array($Call['Scope']))
                $Call['Scope'] = implode(DS, $Call['Scope']);

        if (mb_substr($Call['Link'], 0, 1) == '/')
            $DirName = $Call['Link'] . '/' . $Call['Scope'] . '/';
        else
            $DirName = Root.'/'.$Call['Link'] . '/' . $Call['Scope'] . '/';

        $Postfix   = isset($Call['Suffix']) ? $Call['Suffix'] : '';
        $Prefix   = isset($Call['Prefix']) ? $Call['Prefix'] : '';

        $ID = isset($Call['Where']['ID'])? $Call['Where']['ID']: $Call['ID'];

        if (isset($Call['Directory Hashing']['Enabled']) && $Call['Directory Hashing']['Enabled'])
        {
            $Hash = sha1($ID);
            for ($IX = 0; $IX < $Call['Directory Hashing']['Levels']; $IX++)
                $Prefix .= mb_substr($Hash, $IX, $Call['Directory Hashing']['Size']).DS;
        }

        $Filename = $DirName.$Prefix.$ID.$Postfix;

        $DirName = dirname($Filename);

        if (!is_dir($DirName))
        {
            if (is_writable($DirName))
            {
                if (mkdir($DirName, 0777, true))
                    F::Log('Directory '.$DirName.' created', LOG_INFO, 'Administrator');
                else
                    F::Log('Directory '.$DirName.' cannot created', LOG_ERR, 'Administrator');
            }
            else
                F::Log('Directory '.$DirName.' not writeable', LOG_ERR, 'Administrator');
        }

        if (isset($Call['Data']) && ($Call['Data'] != 'null') && ($Call['Data'] != null))
        {
            if (file_put_contents ($Filename, $Call['Data']) === false)
            {
                F::Log('Write to *'.$Call['Storage'].'* failed', LOG_ERR, 'Administrator');
                return null;
            }
            else
                return $Call['Data'];
        }
        else
        {
            if (F::file_exists($Filename))
                return unlink ($Filename);
            else
                return null;
        }
    });

    setFn ('Close', function ($Call)
    {
        return true;
    });

    setFn ('Version', function ($Call)
    {
        if (!isset($Call['Scope']))
            $Call['Scope'] = 'Default';
        else
            if (is_array($Call['Scope']))
                $Call['Scope'] = implode(DS, $Call['Scope']);

        $Postfix   = isset($Call['Suffix']) ? $Call['Suffix'] : '';
        $Prefix   = isset($Call['Prefix']) ? $Call['Prefix'] : '';

        $Filename = F::findFile ($Call['Link'] .'/'. $Call['Scope'] . '/' . $Prefix . $Call['Where']['ID'] . $Postfix);

        if (F::file_exists ($Filename))
            return filemtime($Filename);
        else
            return null;
    });

    setFn ('Exist', function ($Call)
    {
        if (!isset($Call['Scope']))
            $Call['Scope'] = 'Default';
        else
            if (is_array($Call['Scope']))
                $Call['Scope'] = implode(DS, $Call['Scope']);

        $Postfix  = isset($Call['Suffix']) ? $Call['Suffix'] : '';
        $Prefix   = isset($Call['Prefix']) ? $Call['Prefix'] : '';

        if (!empty($Call['Where']['ID']))
        {
            $Filename = F::findFile ($Call['Link'] . '/' . $Call['Scope'] . '/' . $Prefix . $Call['Where']['ID'] . $Postfix);
            return F::file_exists ($Filename);
        }
        else
            return false;

    });

    setFn('Status', function ($Call)
    {
        if (!isset($Call['Scope']))
            $Call['Scope'] = 'Default';
        else
            if (is_array($Call['Scope']))
                $Call['Scope'] = implode(DS, $Call['Scope']);

        $Postfix = isset($Call['Suffix']) ? $Call['Suffix'] : '';
        $Prefix = isset($Call['Prefix']) ? $Call['Prefix'] : '';
        $Path = $Call['Link'].'/'.$Call['Scope'].'/';

        $ic = 0;
        $Directory = new RecursiveDirectoryIterator(Root.'/'.$Path);
        $Iterator  = new RecursiveIteratorIterator($Directory);
        $Regex     = new RegexIterator($Iterator, '/'.$Prefix.'(.+)'.$Postfix.'$/i', RecursiveRegexIterator::GET_MATCH);

        foreach($Regex as $File)
            $ic++;

        return [['Files',  $ic]];
    });

    setFn('Size', function ($Call)
    {
        chdir(Root);
        $Output = shell_exec('du --max-depth=0 -h '.$Call['Directory']);

        list($Size, ) = explode ("\t",$Output);

        return $Size;
    });