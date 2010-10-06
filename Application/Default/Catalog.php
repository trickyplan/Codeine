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
    View::AddBuffered(
            View::Replace(array('Objects/'.self::$Name.'/'.self::$Name.'_Catalog_'.str_replace(':','_',self::$ID),
                                    'Objects/'.self::$Name.'/'.self::$Name.'_Catalog'),
            array(
                '<scope/>' => self::$Name,
                '<key/>'   => self::$ID,
                '<value/>' => $Key))
        );

View::Flush();