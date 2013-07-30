<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][2] as $Ix => $Match)
        {
            $Thumb = json_decode(json_encode(simplexml_load_string(html_entity_decode('<thumb>'.$Match.'</thumb>'))),true);
            // FIXME Абстрагировать этот пиздец

            if (isset($Thumb['Default']))
                $Thumb['Default'] = F::Live($Thumb['Default']);
            else
                $Thumb['Default'] = $Call['Default'];

            if (is_string($Thumb['URL']))
            {
                if (isset($Thumb['Remote']) && !empty($Thumb['Remote']) && preg_match('/^http.*/', $Thumb['Remote']))
                    $Filename = $Thumb['Remote'];
                else
                {
                    $Filename = Root . '/' . $Thumb['URL'];

                    if (!is_file($Filename) or is_dir($Filename))
                    {
                        if (preg_match('/^http.*/', $Thumb['Default']))
                            $Filename = $Thumb['Default'];
                        else
                            $Filename = F::findFile($Thumb['Default']);
                    } // FIXME Конфиг
                }
            }

            //crop and resize the image

            $ThumbURL = $Thumb['Width'].'_'.sha1($Filename) . '.jpg'; // FIXME Абстрагировать

            if (null == F::Run ('IO', 'Execute',
                [
                    'Execute' => 'Exist',
                    'Storage' => 'Image Cache',
                    'Where'   => $ThumbURL
                ]
            ))
            {
                try
                {
                    if (getimagesize($Filename))
                    {
                        $Image = new Gmagick($Filename);
                        $Image->cropThumbnailImage($Thumb['Width'], $Thumb['Width']);
                        $Image->setCompressionQuality(100);

                        F::Run ('IO', 'Write',
                              [
                              'Storage' => 'Image Cache',
                              'Where'   => $ThumbURL,
                              'Data' => $Image->getImageBlob()
                              ]
                          );
                        F::Log('Thumbnail created', LOG_INFO);
                    }
                }
                catch (Exception $e)
                {
// Let it fails
                }
            }

            $Call['Output'] = str_replace($Call['Parsed'][0][$Ix],
                F::Run('View', 'Load',
                    array(
                         'Scope' => $Call['Widget Set'].'/Widgets',
                         'ID' => 'Thumbs/'.(isset($Thumb['Template'])? $Thumb['Template']: 'Normal'),
                         'Data' =>
                            F::Merge($Thumb,
                             array(
                                 'Width' => $Thumb['Width'],
                                 'Height' => $Thumb['Width'],
                                 'URL' => '/cache/images/'.$ThumbURL
                             ))

                    )), $Call['Output']);
        }

        return $Call;
     });