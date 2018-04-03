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
        
        $Call['Options'] = F::Live($Call['Options'], $Call);
        
        if (F::Dot($Call, 'Multiple'))
            $Call['Name'] .= '[]';
        
        if ($Call['Options'] === null)
            ;
        else
        {
            if (isset($Call['Nullable']) && $Call['Nullable'])
                $Call['Options'][] = [null, null];
            
            foreach ($Call['Options'] as $Key => $Option)
            {
                if (is_array($Option))
                {
                    $Key = array_pop($Option);
                    $Value = array_pop($Option);
                }
                else
                    $Value = $Option;
                
                switch (F::Dot($Call, 'Label Mode'))
                {
                    case 'Localized Key':
                        if (isset($Call['Values Locale']))
                            ;
                        else
                            $Call['Values Locale'] = $Call['Entity'].'.Entity:'.$Call['Key'];
                        
                        $Label = '<l>'.$Call['Values Locale'].'.'.$Value.'</l>';
                        break;
                    
                    case 'Key':
                        $Label = $Key;
                    break;
                    
                    default:
                        $Label = $Value;
                    break;
                }
                
                
                switch (F::Dot($Call, 'Key Mode'))
                {
                    case 'Value':
                        $Key = $Value;
                    break;
                    
                    default:
                        ;
                    break;
                }
                
                if (in_array($Key, (array) $Call['Value']))
                    $Options[] = '<option value="'.$Key.'" selected>'.$Label.'</option>';
                else
                    $Options[] = '<option value="'.$Key.'">' . $Label . '</option>';
            }
        }
        
        $Call['Value'] = implode('', $Options);
        
        return F::Run('View.HTML.Widget.Base', 'Make',
            $Call,
            [
                'Tag' => 'select'
            ]);
    });