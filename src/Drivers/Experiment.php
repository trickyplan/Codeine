<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Bar', function ($Call)
    {
        if (isset($_COOKIE['Experiment']))
        {
            if (isset($Call['Experiments'][$_COOKIE['Experiment']]))
                $Call['Output']['Experiment'][] = '<div class="alert">Эксперимент: '.$Call['Experiments'][$_COOKIE['Experiment']].' <a
                href="/experiment/no">Выйти</a></div>';
        }
        else
            if (isset($Call['Page Experiments']))
            {
                $Output = '<div class="alert alert-success">Доступны эксперименты: ';

                foreach ($Call['Page Experiments'] as $Experiment)
                {
                    if (isset($Call['Experiments'][$Experiment]))
                        $Output.=
                            '<a href="/experiment/'.$Experiment.'">'.$Call['Experiments'][$Experiment].'</a>';
                }

                $Call['Output']['Experiment'][] = $Output.'</div>';
            }

        return $Call;
    });

    self::setFn('Set', function ($Call)
    {
        if (isset($Call['Experiments'][$Call['Experiment']]))
        {
            setcookie('Experiment', $Call['Experiment'], 2147483648, '/', null, false, true);
        }
        else
            setcookie('Experiment', null, 0, '/', null, false, true);

        return F::Run('System.Interface.Web', 'Redirect', $Call, ['Location' => '/']);
    });