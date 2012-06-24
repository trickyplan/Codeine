<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Do', function ($Call)
    {
        $Host = 'http://'.$_SERVER['HTTP_HOST'];

        $Data['/'] = F::Run(null, 'Get', array('URL' => $Host.'/'));

        foreach ($Data['/']['Links'] as $Link)
        {
            $Data[$Link] = F::Run(null, 'Get', array('URL' => $Host.$Link));
            foreach ($Data[$Link]['Links'] as $Link2)
                if (!isset($Data[$Link2]))
                    $Data[$Link2] = F::Run(null, 'Get', array('URL' => $Host.$Link2));
        }

        foreach ($Data as $URL => $Page)
            $Tabular[] = array($URL, $Page['Title'], $Page['Metas']['description'], $Page['Metas']['keywords']);

        $Call['Output']['Content'][]
            = array(
                'Type' => 'Table',
                'Value' => $Tabular
            );

        return $Call;
    });

    self::setFn('Get', function ($Call)
    {
        if (!isset($Call['URL']))
            $Call['URL'] = '/';

        $Page = F::Run('IO', 'Read', array(
                                   'Storage' => 'Web',
                                   'Where' => $Call['URL']
                              ));

        $DOM = new DOMDocument('1.0', 'UTF-8');
        $DOM->loadHTML($Page);

        $Links = $DOM->getElementsByTagName('a');
        $Metas =  $DOM->getElementsByTagName('meta');

        $Data['Title'] = $DOM->getElementsByTagName('title')->item(0)->nodeValue;

        for($a = 0; $a<$Metas->length; $a++)
        {
            if ($Metas->item($a)->attributes->getNamedItem('name') !== null)
                $Data['Metas'][$Metas->item($a)->attributes->getNamedItem('name')->nodeValue]
                    = $Metas->item($a)->attributes->getNamedItem('content')->nodeValue;
            else
                $Data['Metas'][$Metas->item($a)->attributes->getNamedItem('http-equiv')->nodeValue]
                    = $Metas->item($a)->attributes->getNamedItem('content')->nodeValue;
        }

        for($a = 0; $a<$Links->length; $a++)
            if ($Links->item($a)->attributes->getNamedItem('rel')->nodeValue != 'nofollow')
                if ($Links->item($a)->attributes->getNamedItem('href')->nodeValue[0] != '#')
                    $Data['Links'][] = $Links->item($a)->attributes->getNamedItem('href')->nodeValue;

        $Data['Links'] = array_unique($Data['Links']);

        return $Data;
    });