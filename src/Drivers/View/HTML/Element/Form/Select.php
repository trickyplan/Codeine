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

         if (isset($Call['Options']))
            $Call['Options'] = F::Live($Call['Options']);
         else
            $Call['Options'] = array();

         $Call['Value'] = F::Live($Call['Value']);

         if (isset($Call['Multiple']))
             $Call['Name'] .= '[]';

         if (isset($Call['Localized']) && $Call['Localized'])
                 $Call['Label'] = $Call['Entity'].'.Entity:'.$Call['Node'].'.Label';

         foreach ($Call['Options'] as $Key => $Value)
         {
             if (isset($Call['Localized']) && $Call['Localized'])
                 $lValue = '<l>'.$Call['Entity'].'.Entity:'.$Call['Node'].'.'.$Value.'</l>';
             else
                 $lValue = $Value;

             if(in_array($Value, (array) $Call['Value']) or in_array($Key, (array) $Call['Value']))
                 $Options[] = '<option value="'.$Key.'" selected>'.$lValue.'</option>';
             else
                 $Options[] = '<option value="' . $Key . '">' . $lValue . '</option>';
         }

         $Call['Options'] = implode('', $Options);

         return F::Run ('View', 'LoadParsed',
                        array(
                             'Scope' => 'Default',
                             'ID'    => 'UI/Form/'.(isset($Call['Template'])? $Call['Template'] : 'Select'),
                             'Data'  => $Call
                        ));
     });