<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Head', function ($Call)
    {
        $Call = F::Hook('TagManager.Google.Head.Before', $Call);

        if (F::Dot($Call, 'TagManager.Google.Enabled'))
        {
            $Code = '';
            $ID = F::Dot($Call, 'TagManager.Google.ID');

            if (F::Dot($Call, 'TagManager.Google.URLs.Disabled') !== null && in_array($Call['HTTP']['URL'],
                    F::Dot($Call, 'TagManager.Google.URLs.Disabled')))
            {
                $Message = 'Google.TagManager *'.$ID.'* Suppressed by *URLs*';
                $Code = '<!-- '.$Message.' -->';
                F::Log($Message, LOG_INFO, 'Marketing');
            }
            else
            {
                if (F::Dot($Call, 'TagManager.Google.Environment.'.F::Environment()) === true)
                {
                    $Code = F::Live(F::Run('View', 'Load', $Call,
                        [
                            'Scope'     => 'View.HTML.Widget.TagManager.Google',
                            'ID'        => 'Head'
                        ]), $Call);

                    $Message = 'Google.TagManager *'.$ID.'* Registered';
                    F::Log($Message, LOG_INFO, 'Marketing');
                }
                else
                {
                    $Message = 'Google.TagManager *'.$ID.'* Suppressed by *Environment*';
                    $Code = '<!-- '.$Message.' -->';
                    F::Log($Message, LOG_INFO, 'Marketing');
                }
            }
        }
        else
        {
            $Message = 'Google.TagManager *'.($ID??'').'* Suppressed by *TagManager.Google.Enabled option*';
            $Code = '<!-- '.$Message.' -->';
            F::Log($Message, LOG_INFO, 'Marketing');
        }

        $Call = F::Hook('TagManager.Google.Head.After', $Call);
        return $Code;
    });

    setFn('Body', function ($Call)
    {
        $Call = F::Hook('TagManager.Google.Head.Before', $Call);

        if (F::Dot($Call, 'TagManager.Google.Enabled'))
        {
            $ID = F::Dot($Call, 'TagManager.Google.ID');
            $Code = '';

            if (F::Dot($Call, 'TagManager.Google.URLs.Disabled') !== null && in_array($Call['HTTP']['URL'],
                    F::Dot($Call, 'TagManager.Google.URLs.Disabled')))
            {
                $Message = 'Google.TagManager *' . $ID . '* Suppressed by *URLs*';
                $Code = '<!-- ' . $Message . ' -->';
                F::Log($Message, LOG_INFO, 'Marketing');
            } else
            {
                if (F::Dot($Call, 'TagManager.Google.Environment.' . F::Environment()) === true)
                {
                    $Code = F::Live(F::Run('View', 'Load', $Call,
                        [
                            'Scope'     => 'View.HTML.Widget.TagManager.Google',
                            'ID'        => 'Body'
                        ]), $Call);

                    $Message = 'Google.TagManager *' . $ID . '* Registered';
                    F::Log($Message, LOG_INFO, 'Marketing');
                } else
                {
                    $Message = 'Google.TagManager *' . $ID . '* Suppressed by *Environment*';
                    $Code = '<!-- ' . $Message . ' -->';
                    F::Log($Message, LOG_INFO, 'Marketing');
                }
            }
        } else
        {
            $Message = 'Google.TagManager *' . ($ID ?? '') . '* Suppressed by *TagManager.Google.Enabled option*';
            $Code = '<!-- ' . $Message . ' -->';
            F::Log($Message, LOG_INFO, 'Marketing');
        }

        $Call = F::Hook('TagManager.Google.Head.After', $Call);
        return $Code;
    });