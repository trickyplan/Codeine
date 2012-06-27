<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    function array_diff_assoc_recursive($array1, $array2)
    {
        // FIXME Codeinize
        foreach($array1 as $key => $value)
        {
            if ($value != '*')
            {
                if(is_array($value))
                {
                      if(!isset($array2[$key]))
                      {
                          $difference[$key] = $value;
                      }
                      elseif(!is_array($array2[$key]))
                      {
                          $difference[$key] = $value;
                      }
                      else
                      {
                          $new_diff = array_diff_assoc_recursive($value, $array2[$key]);
                          if(null !== $new_diff)
                          {
                                $difference[$key] = $new_diff;
                          }
                      }
                  }
                  elseif(!isset($array2[$key]) || $array2[$key] != $value)
                  {
                      $difference[$key] = $value;
                  }
            }
        }
        return !isset($difference) ? null : $difference;
    }

    self::setFn('Check', function ($Call)
    {
        foreach ($Call['Rules'] as $Name => $Rule)
        {
            if (isset($Rule['Debug']))
            {
                d(__FILE__, __LINE__, array_diff_assoc_recursive($Rule['Run'], $Call));
            }

            if ($Rule['Weight'] >= $Call['Weight'])
            {
                if (isset($Rule['Run']) && (array_diff_assoc_recursive($Rule['Run'], $Call) === null))
                {
                    if (!isset($Rule['Expression']) || F::Live($Rule['Expression'], $Call))
                    {
                        F::Log('Rule «'.$Name.'» applied');
                        $Call['Decision'] = $Rule['Decision'];
                        $Call['Weight'] = $Rule['Weight'];
                        $Call['Rule'] = $Rule;
                    }
                }
                else
                {
                    F::Log('Rule «'.$Name.'» tested');
                }

                if (isset($Rule['Run']['Service']) && isset($Rule['Run']['Method']) && isset($Rule['Message']))
                    if (($Call['Service'] == $Rule['Run']['Service']) && ($Call['Method'] == $Rule['Run']['Method']))
                        $Call['Message'] = $Rule['Message'];
            }
            else
                F::Log('Rule «'.$Name.'» skipped ('.$Rule['Weight'].'<'.$Call['Weight'].')');
        }

        F::Log('Final decision:'. $Call['Decision']);
        return $Call;
     });