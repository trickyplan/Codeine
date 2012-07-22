<?php

    /* Codeine
    * @author 8bit
    * @description Media includes support
    * @package Codeine
    * @version 7.5
    */

    self::setFn('Hash', function ($Call)
    {

        $Hash = array ();

        foreach ($Call['IDs'] as $ImageFile)
        {
            list($Asset, $ID) = F::Run('View', 'Asset.Route', array ('Value' => $ImageFile));

            $Hash[] = $ImageFile .F::Run('IO', 'Execute', array (
                'Storage' => 'Image',
                'Scope'   => $Asset.'/images',
                'Execute' => 'Version',
                'Where'   =>
                array (
                    'ID' => $ID
                )
            ));

        }

        return F::Run('Security.Hash', 'Get', array('Value' => implode('', $Hash)));
    });

   // FIXME  Функцию Image.Join можно вытащить целиком в отдельный файл (Сделать универсальной без заточки на спрайты)
    self::setFn('Image.Join', function($Call)
    {

        $UniqueSprite = array();

        $SpriteHandle = array();

        $ImageHandle = array();

        $CSS = '';
        //Идем по картинкам
        foreach($Call['URLs'] as $Key=>$ImageFile)
        {

            $UniqueSprite[$Key] = (string)($Call['XML'][$Key]->Sprite?$Call['XML'][$Key]->Sprite:'MainSprite');

            list($Asset, $ID) = F::Run('View', 'Asset.Route', array('Value' => $ImageFile));

            $ImageSource = F::Run('IO', 'Read', array (
                'Storage' => 'Image',
                'Scope'   => $Asset . '/images',
                'Where'   => $ID
            ));

            if(isset($ImageSource[0]))
            {
                $GImg = new Gmagick(); //readimageblob

                $GImg->readimageblob($ImageSource[0]);

                $Width = (int)($Call['XML'][$Key]->Width?$Call['XML'][$Key]->Width:$GImg->getImageWidth());

                $Height = (int)($Call['XML'][$Key]->Height?$Call['XML'][$Key]->Height:$GImg->getImageHeight());

                $GImg->resizeImage($Width, $Height, null, 1);

                $ImageHandle[(string)$Call['XML'][$Key]->Sprite][] =  array('Gmagick'=>$GImg,'XML'=>$Call['XML'][$Key]);
            }

            //Загрузить картинку сделать из нее источник записать создать
        }

        $UniqueSprite =  array_unique($UniqueSprite);

        foreach($UniqueSprite as $Sprite)
        {
            $SGImg = new Gmagick();
            //Подсчет
            $Hash = F::Run(null, 'Hash', array('IDs' => array($Sprite)));

            $MWidth = 0;

            $MHeight = 0;

            foreach($ImageHandle[$Sprite] as $imgInSprite )
            {
                $MWidth+= $imgInSprite['Gmagick']->getImageWidth();

                if( $MHeight<$imgInSprite['Gmagick']->getImageHeight())
                {

                    $MHeight=$imgInSprite['Gmagick']->getImageHeight();
                }
            }

            if($MWidth==0||$MHeight==0)continue;
            //размер нужно будет просчитать зарание под количество картинок
            $SGImg->newimage( $MWidth,$MHeight,'black');

            $X = 0;

            $FlagDebug = $Call['DebugLayouts'];

            $Call['DebugLayouts'] = null;

            foreach($ImageHandle[$Sprite] as $ImgInSprite )
            {

                list($Asset, $ID) = F::Run('View', 'Asset.Route', array('Value' => (string)$ImgInSprite['XML']->URL));

                $CSS.=F::Run ('View', 'LoadParsed', $Call,
                    array(
                         'Scope' => isset($Call['Scope'])? $Call['Scope']: 'Default',
                         'ID'    => 'UI/HTML/Sprite',
                         'Data'  => array(
                             "Sprite"=>$Sprite,
                             "Image"=>$ID,
                             "Path"=>$Hash,
                             "XPos"=>$X,
                             "Height"=>$ImgInSprite['Gmagick']->getImageHeight(),
                             "Width"=>$ImgInSprite['Gmagick']->getImageWidth(),
                             "OutputFormat"=>$Call['OutputFormat']
                         )
                    ));

                $SGImg = $SGImg->compositeimage($ImgInSprite['Gmagick'],Gmagick::COMPOSITE_COPY,$X,0);

                $X+=$ImgInSprite['Gmagick']->getImageWidth();
            }
            $Call['DebugLayouts'] = $FlagDebug;

            $SGImg->setImageFormat($Call['OutputFormat']);

            $STR = $SGImg->getImageBlob();

            F::Run ('IO', 'Write',
                array(
                    'Storage' => 'Image Cache',
                    'Where'   => $Hash,
                    'Data' =>  $STR
                ));

            $SpriteHandle[] = $SGImg;
        }

        //возврат названий спрайтов
        return array('UniqueSprite'=>$UniqueSprite,'SpriteHandel'=>$SpriteHandle,'css'=>$CSS);
    });
    //Точка входа
    self::setFn ('Process', function ($Call)
    {
        //Поиск xml вставки в буфере

        if (preg_match_all ('@<image>(.*)<\/image>@SsUu', $Call['Output'], $Parsed))
        {

            $UniqueParse =  array_unique($Parsed[0]);

            $Sprite = array();

            $ImgXML =array();

            $URLs = array();

            foreach($UniqueParse as $Key=>$XMLObject)
            {

                $ImgXML[$Key]= simplexml_load_string($XMLObject);

                if(!isset($ImgXML[$Key]->Sprite))$ImgXML[$Key]->Sprite = 'MainSprite';

                $URLs[$Key] = (string)$ImgXML[$Key]->URL;

                $Sprite[(string)($ImgXML[$Key]->Sprite)][] = $URLs[$Key];

            }

            //Массив спрайто в (общем случае один если у всех картинок группа одна была)
            foreach($Sprite as $Key=>$Images){

                if ((isset($Call['Caching']['Enabled'])
                    && $Call['Caching']['Enabled'])
                    && F::Run('IO', 'Execute', array ('Storage' => 'Image Cache',
                        'Execute'  => 'Exist',
                        'Where'    => array ('ID' =>F::Run(null, 'Hash', array('IDs' => array($Key))))))
                )
                {
                    //echo 'From Cache';
                }else{

                    $SpriteGroups = F::Run(null, 'Image.Join', array('XML' => $ImgXML,'URLs'=>$Images));

                    if($Parsed[0][0]&&$SpriteGroups['css'])
                        $Call['Output'] =
                            str_replace($Parsed[0][0], '<style type="text/css-generate">'.$SpriteGroups['css'].'</style>'.$Parsed[0][0], $Call['Output']);



                }
            }

            foreach($ImgXML as $Key=>$val){

                $ImageFile = $URLs[$Key];

                list($Asset, $ID) = F::Run('View', 'Asset.Route', array('Value' => $ImageFile));

                $Call['Output'] =
                    str_replace($UniqueParse[$Key],
                               '<div class="'.((string)$val->Sprite).'-'.$ID.'"></div>',
                               $Call['Output']);
            }

            $Call['Output'] = str_replace($Parsed[0], '', $Call['Output']);
        }

        return $Call;
    });