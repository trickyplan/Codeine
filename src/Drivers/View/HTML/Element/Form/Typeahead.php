<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.x
     */

     setFn('Make', function ($Call)
     {
         $Options = array();

         if (isset($Call['Multiple']))
             $Call['Name'] .= '[]';

         if (isset($Call['Localized']) && $Call['Localized'])
                 $Call['Label'] = $Call['Entity'].'.Entity:'.$Call['Node'].'.Label';

         $Call['Options'] = json_encode(array_map(function($Element){return $Element[1];}, $Call['Options']), JSON_UNESCAPED_UNICODE);

         return F::Run ('View', 'Load',
                        array(
                             'Scope' => 'Default',
                             'ID'    => 'UI/Form/'.(isset($Call['Template'])? $Call['Template'] : 'Typeahead'),
                             'Data'  => $Call
                        ));
     });