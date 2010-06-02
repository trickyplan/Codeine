<?php

    function F_Default_Metaphone($Word)
    {
       $Output = '';
       $Word = mb_strtoupper($Word);
       $SizeOfWord = mb_strlen($Word);
       $Metaphone = strtr($Word, array('Б'=>'П',
                                       'О'=>'А',
                                       'Ы'=>'А',
                                       'Я'=>'А',
                                       'Ю'=>'У',
                                       'Е'=>'И',
                                       'Ё'=>'И',
       								   'Й'=>'И',
                                       'Э'=>'И',
                                       'К'=>'Г',
                                       'ТС'=>'Ц',
                                       'В'=>'Ф',
                                       'Ш'=>'С',
                                       'Д'=>'Т',
                                       'З'=>'С',
                                       'Ч'=>'С',
                                       'Щ'=>'С',
                                       'Ь'=>'',
                                       'Ъ'=>''                                       
       ));
       for($i = 0; $i<$SizeOfWord; $i++)
           if (mb_substr($Metaphone,$i,1) != mb_substr($Metaphone,$i+1,1)) $Output.= mb_substr($Metaphone,$i,1);
      return $Output;
   }
   
