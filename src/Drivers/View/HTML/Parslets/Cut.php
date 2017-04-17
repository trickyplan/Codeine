<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Cut Parslet
     * @package Codeine
     * @version 8.x
     */

    setFn ('Parse', function ($Call)
    {
        $Replaces = [];
        
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            if (F::Dot($Call ,'View.HTML.Parslet.Cut.StripTags.Enabled') or F::Dot($Call['Parsed'], 'Options.'.$IX.'.strip'))
                $Cut = strip_tags($Match, F::Dot($Call ,'View.HTML.Parslet.Cut.StripTags.Allowed'));

            if (F::Dot($Call['Parsed'], 'Options.'.$IX.'.chars'))
                $Cut = F::Run('Text.Cut', 'Do',
                    [
                        'Cut'   => 'Chars',
                        'Value' => $Match,
                        'Chars' => F::Dot($Call['Parsed'], 'Options.'.$IX.'.chars')
                    ]);
            elseif (F::Dot($Call['Parsed'], 'Options.'.$IX.'.words'))
                $Cut = F::Run('Text.Cut', 'Do',
                    [
                        'Cut'   => 'Words',
                        'Value' => $Match,
                        'Words' => F::Dot($Call['Parsed'], 'Options.'.$IX.'.words')
                    ]);
            elseif (F::Dot($Call['Parsed'], 'Options.'.$IX.'.sentences'))
                $Cut = F::Run('Text.Cut', 'Do',
                    [
                        'Cut'   => 'Sentences',
                        'Value' => $Match,
                        'Sentences' => F::Dot($Call['Parsed'], 'Options.'.$IX.'.sentences')
                    ]);

            if (F::Dot($Call['Parsed'], 'Options.'.$IX.'.hellip'))
                $Hellip = F::Dot($Call['Parsed'], 'Options.'.$IX.'.hellip');
            else
                $Hellip = F::Dot($Call ,'View.HTML.Parslet.Cut.Hellip');

            if (F::Dot($Call['Parsed'], 'Options.'.$IX.'.more'))
                $Hellip = '<a href="'.F::Dot($Call['Parsed'], 'Options.'.$IX.'.more').'" class="hellip">'.$Hellip.'</a>';

            if (strlen($Cut) < strlen($Match))
                $Cut.= strtr($Hellip, ['\n' => '<br/>']); // nl2br is unusable here

            $Replaces[$IX] = $Cut;
        }

        return $Replaces;
    });