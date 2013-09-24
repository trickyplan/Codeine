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
             $Call['Options'] = $Call['Options'][0];

         if (isset($Call['Multiple']))
             $Call['Name'] .= '[]';

         if ((!isset($Call['Required']) or !$Call['Required']) && !isset($Call['Options'][0]))
             $Call['Options'][0] = 'No';

         if ($Call['Options'] !== null)
             foreach ($Call['Options'] as $Key => $Option)
             {
                 if (is_array($Option))
                    list ($Key, $Value) = $Option;
                 else
                    $Value = $Option;

                 if (isset($Call['Localized']) && $Call['Localized'])
                    $lValue = '<l>'.$Call['Entity'].'.Entity:'.$Call['Key'].'.'.$Value.'</l>';
                 else
                    $lValue = $Value;

                 if ($Key == $Call['Value'] || $Value == $Call['Value'] || (is_array($Call['Value']) && in_array($Key, $Call['Value'])))
                     $Options[] = '<option value="'.$Key.'" selected>'.$lValue.'</option>';
                 else
                     $Options[] = '<option value="' . $Key . '">' . $lValue . '</option>';
             }

         $Call ['Options'] = implode('', $Options);
         return $Call;
     });