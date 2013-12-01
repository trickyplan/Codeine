<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.x
     */

     setFn('Make', function ($Call)
     {
         $Options = [];

         if (isset($Call['One']))
         {
             $Call['Options'] = $Call['Options'][0];
             unset($Call['One']);
         }

         if (isset($Call['Multiple']))
             $Call['Name'] .= '[]';

         if ($Call['Options'] !== null)
             foreach ($Call['Options'] as $Key => $Option)
             {
                 if (is_array($Option))
                    list ($Key, $Value) = $Option;
                 else
                    $Value = $Option;

                 if (isset($Call['Localized']) && $Call['Localized'])
                 {
                     if (!isset($Call['Values Locale']))
                         $Call['Values Locale'] = $Call['Entity'].'.Entity:'.$Call['Key'];

                     $lValue = '<l>'.$Call['Values Locale'].'.'.$Value.'</l>';
                 }
                 else
                    $lValue = $Value;

                 if (($Key == $Call['Value'])
                     || ($Value == $Call['Value'])
                     || ((is_array($Call['Value']) && in_array ($Value, $Call['Value']))))
                     $Options[] = '<option value="'.$Option.'" selected>'.$lValue.'</option>';
                 else
                     $Options[] = '<option value="'.$Option.'">' . $lValue . '</option>';
             }

         $Call ['Options'] = implode('', $Options);
         return $Call;
     });