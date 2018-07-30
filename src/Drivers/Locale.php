<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Get', function ($Call)
    {
        if (is_array($Call['Message']))
            ;
        else
            $Call['Message'] = (array) $Call['Message'];
        
        $Localized = false;
        $Translation = null;
        foreach ($Call['Message'] as $Message)
        {
            if (strpos($Message, ':') !== false)
                list($Locale, $Token) = explode(':', $Message);
            else
            {
                $Locale = 'Locale';
                $Token = $Message;
            }
            
            $LocaleParts = explode('.', $Locale);
            $ID = array_pop($LocaleParts);
            $Asset = implode('.', $LocaleParts);
            $Asset = strtr($Asset, '.', '/');
            
            $LocaleFile = F::Run('IO', 'Read',
            [
                  'Storage' => 'Locale',
                  'Scope'   => $Asset.'/Locale/'.$Call['Locale'],
                  'Where'   => $ID
            ]);
            
            if (empty($LocaleFile))
                ;
            else
            {
                $LocaleStorage = [];
                $LocaleFile = array_reverse($LocaleFile);
                foreach ($LocaleFile as $cLocaleFile)
                    $LocaleStorage = F::Merge($LocaleStorage, $cLocaleFile);
                
                if (($Translation = F::Dot($LocaleStorage, $Token)) === null)
                    continue;
                else
                {
                    $Localized = true;
                    break;
                }
            }
        }
       
        if ($Localized)
        {
            $Translation = F::Variable($Translation, $Call); // TODO Analyze Performance Impact
        }
        else
        {
            F::Log('Unresolved locale *'.$Message.'*'
                .PHP_EOL
                .'Locale is *'.$Call['Locale'].'*.'
                .PHP_EOL
                .'Asset is *'.$Asset.'*.'
                .PHP_EOL
                .'ID is *'.$ID.'*.'
                .PHP_EOL
                .'Token is *'.$Token.'*', LOG_NOTICE);
        }

        return $Translation;
    });