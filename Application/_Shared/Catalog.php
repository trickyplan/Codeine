<?php
/*
 * Каталог
 *
 * /web/Application/Catalog/Key
 */

if (isset(self::$In['Manual']))
    $Variants = self::$In['Manual'];
else
    $Variants = self::$Collection->VariantsOf(self::$ID);

foreach ($Variants as $Key)
    Page::AddBuffered(
            Page::Replace(array('Objects/'.self::$Name.'/'.self::$Name.'_Catalog_'.str_replace(':','_',self::$ID),
                                    'Objects/'.self::$Name.'/'.self::$Name.'_Catalog'),
            array(
                '<scope/>' => self::$Name,
                '<key/>'   => self::$ID,
                '<value/>' => $Key))
        );

Page::Flush();