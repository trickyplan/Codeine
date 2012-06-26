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
        //var_dump($Call['URLs']);
        $UniqueSprite = array();

        $SpriteHandle = array();

        $ImageHandle = array();

        $CSS = "";
        //Идем по картинкам
        foreach($Call['URLs'] as $Key=>$ImageFile)
        {

            $UniqueSprite[$Key] = (string)$Call['XML'][$Key]->Sprite;

            list($Asset, $ID) = F::Run('View', 'Asset.Route', array('Value' => $ImageFile));

            $ImageSource = F::Run('IO', 'Read', array (
                'Storage' => 'Image',
                'Scope'   => $Asset . '/images',
                'Where'   => $ID
            ));
            //var_dump( $ImageSource);
            if(isset($ImageSource[0]))
            {
                $GImg = new Gmagick(); //readimageblob

                $GImg->readimageblob($ImageSource[0]);

                $Width = (int)($Call['XML'][$Key]->Width?$Call['XML'][$Key]->Width:$GImg->getImageWidth());

                $Height = (int)($Call['XML'][$Key]->Height?$Call['XML'][$Key]->Height:$GImg->getImageHeight());

                //var_dump($Width,$Height);

                $GImg->resizeImage($Width, $Height, null, 1);

                $ImageHandle[(string)$Call['XML'][$Key]->Sprite][] =  array('Gmagick'=>$GImg,'XML'=>$Call['XML'][$Key]);
            }
            //var_dump( $Imagick);
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

            foreach($ImageHandle[$Sprite] as $ImgInSprite )
            {

                list($Asset, $ID) = F::Run('View', 'Asset.Route', array('Value' => (string)$ImgInSprite['XML']->URL));

                $CSS.='.'.$Sprite.'-'.$ID.'{';

                $CSS.='background:url(/images/'.$Hash.'.png) -'.$X.'px 0px;';

                $CSS.='height:'.$ImgInSprite['Gmagick']->getImageHeight().'px;';

                $CSS.='width:'.$ImgInSprite['Gmagick']->getImageWidth().'px;} ';

                $SGImg = $SGImg->compositeimage($ImgInSprite['Gmagick'],Gmagick::COMPOSITE_COPY,$X,0);

                $X+=$ImgInSprite['Gmagick']->getImageWidth();
            }

            $SGImg->setImageFormat('png');

            $STR = $SGImg->getImageBlob();

            F::Run ('IO', 'Write',
                array(
                    'Storage' => 'Image Cache',
                    'Where'   => $Hash,
                    'Data' =>  $STR
                ));

            $SpriteHandle[] = $SGImg;
        }
        //var_dump($css);
       // var_dump($UniqueSprite);
        //возврат названий спрайтов
        return array('UniqueSprite'=>$UniqueSprite,'SpriteHandel'=>$SpriteHandle,'css'=>$CSS);
    });
    //Точка входа
    self::setFn ('Process', function ($Call)
    {
        //var_dump(debug_backtrace());
        //Поиск xml вставки в буфере

        if (preg_match_all ('@<image>(.*)<\/image>@SsUu', $Call['Output'], $Parsed))
        {

            $UniqueParse =  array_unique($Parsed[0]);

            $ImageHash = array();

            $ImgXML =array();

            $URLs = array();

            foreach($UniqueParse as $Key=>$XMLObject)
            {
                   // var_dump($XMLObject);
                    $ImgXML[$Key]= simplexml_load_string($XMLObject);

                    if(!isset($ImgXML[$Key]->Sprite))$ImgXML[$Key]->Sprite = 'MainSprite';

                    $URLs[$Key] = (string)$ImgXML[$Key]->URL;
                    //Временное решение
                    $ImageHash[$Key] = F::Run(null, 'Hash', array('IDs' => array((string)$ImgXML[$Key]->URL)));
            }
            //Массив спрайто в (общем случае один если у всех картинок группа одна была)

            $SpriteGroups = F::Run(null, 'Image.Join', array('XML' => $ImgXML,'URLs'=>$URLs));

            if($Parsed[0][0]&&$SpriteGroups['css']){

                $Call['Output'] =
                    str_replace($Parsed[0][0], '<style>'.$SpriteGroups['css'].'</style>'.$Parsed[0][0], $Call['Output']);

            }

            foreach($ImgXML as $Key=>$val){
                //Если есть в кеше ничего не делать
                $ImageFile = $URLs[$Key];
                list($Asset, $ID) = F::Run('View', 'Asset.Route', array('Value' => $ImageFile));

                /*if ((isset($Call['Caching']['Enabled'])
                    && $Call['Caching']['Enabled'])
                    && F::Run('IO', 'Execute', array ('Storage' => 'IMG Cache',
                        'Execute'  => 'Exist',
                        'Where'    => array ('ID' => $ImageHash[$key])))
                )
                {
                    //die('hash is load');
                }
                else
                {
                    //die('ds');
                    $IMG = array();

                   // foreach ($URLs as $ImageFile)
                    //{
                            //var_dump($ImageFile);
                        //Возврат разбитого значения $Asset каталог и $ID имя файлика затребованного

                        //var_dump($Asset);
                        if ($ImageSource = F::Run('IO', 'Read', array (
                            'Storage' => 'IMG',
                            'Scope'   => $Asset . '/images',
                            'Where'   => $ID
                        )))
                        {
                            //header("Content-type: image/png");
                            //die($ImageSource[0]);
                           //var_dump($ImageSource);
                            F::Log('IMG loaded: '.$ImageFile);
                            $IMG[] = $ImageSource[0];
                        }
                        else
                            trigger_error('No IMG: '.$ImageFile); // FIXNE
                  //  }
                    //Сохраняет под именем хеша файлики
                    F::Run ('IO', 'Write',
                        array(
                            'Storage' => 'IMG Cache',
                            'Where'   => $ImageHash[$key],
                            'Data' =>  $IMG
                        ));

                }*/

                $Call['Output'] =
                    str_replace($UniqueParse[$Key],
                               '<div class="'.((string)$val->Sprite).'-'.$ID.'"></div>',
                               $Call['Output']);
            }

            $Call['Output'] = str_replace($Parsed[0], '', $Call['Output']);
        }

        return $Call;
    });