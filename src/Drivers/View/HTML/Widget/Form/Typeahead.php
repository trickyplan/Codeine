<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 8.x
     */

     setFn('Make', function ($Call)
     {
         $Options = [];

         if (isset($Call['Multiple']))
             $Call['Name'] .= '[]';

         if (isset($Call['Localized']) && $Call['Localized'])
                 $Call['Label'] = $Call['Entity'].'.Entity:'.$Call['Node'].'.Label';

         $Call['Options'] = j(array_map(function($Element){return $Element[1];}, $Call['Options']), JSON_UNESCAPED_UNICODE);

         return $Call;
     });