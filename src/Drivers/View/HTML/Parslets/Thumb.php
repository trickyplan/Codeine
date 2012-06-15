<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][2] as $Ix => $Match)
        {
            $Thumb = json_decode(json_encode(simplexml_load_string('<thumb>'.$Match.'</thumb>')),true); // FIXME Абстрагировать этот пиздец

            if (preg_match('/^http/', $Thumb['URL']))
                $Filename = $Thumb['URL'];
            else
            {
                $Filename = Root . '/' . $Thumb['URL'];

                if(!is_file($Filename) or is_dir($Filename))
                    $Filename = F::findFile((isset($Thumb['Default'])? $Thumb['Default']: 'Assets/Default/img/no.jpg')); // FIXME Конфиг
            }

            //crop and resize the image

            $ThumbURL = '/thumbs/'.$Thumb['Width'].'_'.sha1($Filename) . '.jpg'; // FIXME Абстрагировать

            if (!file_exists(Root.'/Public'.$ThumbURL))
            {
                F::Log('Thumbnail created');

                if (getimagesize($Filename))
                {
                    $Image = new Gmagick($Filename);
                    $Image->cropThumbnailImage($Thumb['Width'], $Thumb['Width']);
                    $Image->writeImage(Root.'/Public'.$ThumbURL);
                }
            }

            $Call['Output'] = str_replace($Call['Parsed'][0][$Ix],
                F::Run('View', 'LoadParsed',
                    array(
                         'Scope' => 'Default',
                         'ID' => 'UI/Thumbs/'.(isset($Thumb['Template'])? $Thumb['Template']: 'Normal'),
                         'Data' =>
                            F::Merge($Thumb,
                             array(
                                 'Width' => $Thumb['Width'],
                                 'Height' => $Thumb['Width'],
                                 'URL' => $ThumbURL
                             ))

                    )), $Call['Output']);
        }

        return $Call;
     });