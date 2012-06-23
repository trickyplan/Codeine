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
          //  var_dump($ID);
            $Hash[] = $ImageFile .F::Run('IO', 'Execute', array (
                'Storage' => 'IMG',
                'Scope'   => $Asset.'/images',
                'Execute' => 'Version',
                'Where'   =>
                array (
                    'ID' => $ID
                )
            ));

        }
        //var_dump(F::Run('Security.Hash', 'Get', array('Value' => implode('', $Hash))));
        return F::Run('Security.Hash', 'Get', array('Value' => implode('', $Hash)));
    });

    //Склеить имаги
    self::setFn('Image.Join', function($Call){
        //die('ds');
        $UniqueSprite = array();
        $SpriteHandel = array();
        $ImageHandel = array();
        $css = "";
        //Идем по картинкам
        foreach($Call['URLs'] as $key=>$ImageFile){

            $UniqueSprite[$key] = (string)$Call['XML'][$key]->Sprite;

            list($Asset, $ID) = F::Run('View', 'Asset.Route', array('Value' => $ImageFile));

            $ImageSource = F::Run('IO', 'Read', array (
                'Storage' => 'IMG',
                'Scope'   => $Asset . '/images',
                'Where'   => $ID
            ));
            $ImageSource = $ImageSource[0];
            //var_dump( $ImageSource);
            if($ImageSource){
                $GImg = new Gmagick(); //readimageblob
                $GImg->readimageblob( $ImageSource);
                $ImageHandel[(string)$Call['XML'][$key]->Sprite][] =  array('Gmagick'=>$GImg,'XML'=>$Call['XML'][$key]);
            }
            //var_dump( $Imagick);
            //Загрузить картинку сделать из нее источник записать создать
        }
        $UniqueSprite =  array_unique($UniqueSprite);

        foreach($UniqueSprite as $sprite){
            $SGImg = new Gmagick();
            //Подсчет
            $hash = F::Run(null, 'Hash', array('IDs' => array($sprite)));

            $mwidth = 0; $mheight=0;
            foreach($ImageHandel[$sprite] as $imgInSprite ){
                $mwidth+= $imgInSprite['Gmagick']->getImageWidth();
                if( $mheight<$imgInSprite['Gmagick']->getImageHeight()){
                    $mheight=$imgInSprite['Gmagick']->getImageHeight();
                }
            }
            if(!$mwidth&&!$mheight)break;
            //размер нужно будет просчитать зарание под количество картинок
            $SGImg->newimage( $mwidth,$mheight,'black');

            $x = 0;
            foreach($ImageHandel[$sprite] as $imgInSprite ){
               list($Asset, $ID) = F::Run('View', 'Asset.Route', array('Value' => (string)$imgInSprite['XML']->URL));
               $css.='.'.$sprite.'-'.$ID.'{';
               $css.='background:url(/images/'.$hash.'.png) -'.$x.'px 0px;';
               $css.='height:'.$imgInSprite['Gmagick']->getImageHeight().'px;';
               $css.='width:'.$imgInSprite['Gmagick']->getImageWidth().'px;} ';

               $SGImg = $SGImg->compositeimage($imgInSprite['Gmagick'],Gmagick::COMPOSITE_COPY,$x,0);

               $x+=$imgInSprite['Gmagick']->getImageWidth();
            }
            //var_Dump($css);
            $SGImg->setImageFormat('png');
            $str = $SGImg->getImageBlob();

            F::Run ('IO', 'Write',
                array(
                    'Storage' => 'IMG Cache',
                    'Where'   => $hash,
                    'Data' =>  $str
                ));
            $SpriteHandel[] = $SGImg;
        }
        //var_dump($css);
       // var_dump($UniqueSprite);
        //возврат названий спрайтов
        return array('UniqueSprite'=>$UniqueSprite,'SpriteHandel'=>$SpriteHandel,'css'=>$css);
    });
    //Точка входа
    self::setFn ('Process', function ($Call)
    {
        //Поиск xml вставки в буфере
        if (preg_match_all ('@<image>(.*)<\/image>@SsUu', $Call['Output'], $Parsed))
        {


            $ImageHash = array();
            $ImgXML =array();
            $URLs = array();
            foreach($Parsed[0] as $key=>$XMLObject){
                   // var_dump($XMLObject);
                    $ImgXML[$key]= simplexml_load_string($XMLObject);
                    $URLs[$key] = (string)$ImgXML[$key]->URL;
                    //Временное решение
                    $ImageHash[$key] = F::Run(null, 'Hash', array('IDs' => array((string)$ImgXML[$key]->URL)));
            }
            //Массив спрайто в (общем случае один если у всех картинок группа одна была)


            $SpriteGroups = F::Run(null, 'Image.Join', array('XML' => $ImgXML,'URLs'=>$URLs));

            if($Parsed[0][0]&&$SpriteGroups['css']){

                $Call['Output'] =
                    str_replace($Parsed[0][0], '<style>'.$SpriteGroups['css'].'</style>'.$Parsed[0][0], $Call['Output']);

            }

            //foreach($SpriteGroups['UniqueSprite'] as $sprite){
            // $ImageHash = F::Run(null, 'Hash', array('IDs' => array($sprite)));
            //}

            foreach($ImgXML as $key=>$val){
                //Если есть в кеше ничего не делать
                $ImageFile = $URLs[$key];
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


                if (strpos($Call['Output'], '<place_img>IMG:'.$ID.'</place_img>') === false)
                    trigger_error('Place for IMG missed');

                $Call['Output'] =
                    str_replace('<place_img>IMG:'.$ID.'</place_img>',
                               '<div class="'.((string)$val->Sprite).'-'.$ID.'"></div>',
                               $Call['Output']);
            }

            $Call['Output'] = str_replace($Parsed[0], '', $Call['Output']);
        }
        else
            $Call['Output'] = str_replace('<place_img>IMG</place_img>', '', $Call['Output']);

        return $Call;
    });