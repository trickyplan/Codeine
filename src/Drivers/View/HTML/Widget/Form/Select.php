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

         d(__FILE__, __LINE__, $Call['Options']);

         if (isset($Call['One']))
             $Call['Options'] = $Call['Options'][0];

         if (isset($Call['Multiple']))
             $Call['Name'] .= '[]';

         if (isset($Call['Localized']) && $Call['Localized'])
             $Call['Label'] = $Call['Entity'].'.Entity:'.$Call['Node'].'.Label';

         foreach ($Call['Options'] as $Key => $Option)
         {
             if (is_array($Option))
                list ($Key, $Value) = $Option;
             else
                $Value = $Option;

             if (isset($Call['Localized']) && $Call['Localized'])
                 $lValue = '<l>'.$Call['Entity'].'.Entity:'.$Call['Node'].'.'.$Value.'</l>';
             else
                 $lValue = $Value;

             if(in_array($Value, (array) $Call['Value']) or in_array($Key, (array) $Call['Value']))
                 $Options[] = '<option value="'.$Key.'" selected>'.$lValue.'</option>';
             else
                 $Options[] = '<option value="' . $Key . '">' . $lValue . '</option>';
         }

         $Call ['Options'] = implode('', $Options);

         return $Call;
     });