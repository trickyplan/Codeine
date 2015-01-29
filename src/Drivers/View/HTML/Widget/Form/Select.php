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

         if (isset($Call['Multiple']) && $Call['Multiple'])
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

                 if (isset($Call['Keys as values']) && $Call['Keys as values'])
                    {
                         $lValue = $Value;
                         $Option = $Key;
                    }

                 if (($Value == $Call['Value']) || ($Key == $Call['Value']) || ((is_array($Call['Value']) && in_array ($Value, $Call['Value']))))
                     $Options[] = '<option value="'.$Option.'" selected>'.$lValue.'</option>';
                 else
                     $Options[] = '<option value="'.$Option.'">' . $lValue . '</option>';
             }

         $Call ['Options'] = implode('', $Options);

         return $Call;
     });