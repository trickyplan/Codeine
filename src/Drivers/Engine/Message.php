<?php

    /* Codeine
     * @author BreathLess
     * @description: Message Engine
     * @package Codeine
     * @version 6.0
     * @date 29.07.11
     * @time 21:19
     */

    self::Fn('Open', function ($Call)
    {
        $Point = F::getOption('Points.'.$Call['To']);

        $Call = F::Merge($Point, $Call);

        return F::Set('Message'.$Point,
            F::Run($Call,
                    array(
                        '_N' => 'Message.Transport.'.$Point['Transport']
                    )
               ));
    });

    self::Fn('Send', function ($Call)
    {
        // FIXME Strategy

        $Point = F::getOption('Points');

        if (null === $Link = F::Get('Message.'.$Point))
            $Link = F::Run($Call, array('_F' => 'Open'));

        $Call = F::Merge($Point, $Call);
        
        return F::Run($Call,
            array(
                '_N' => 'Message.Transport.'.$Point['Transport'],
                'Link' => $Link
            )
        );
    });

    self::Fn('Receive', function ($Call)
    {
        // FIXME Strategy

        $Point = $Call['Points'][$Call['To']];

        if (null === $Link = F::Get('Message.'.$Point))
            $Link = F::Run($Call, array('_F' => 'Open'));

        $Call = F::Merge($Point, $Call);

        return F::Run($Call,
            array(
                '_N' => 'Message.Transport.'.$Point['Transport'],
                'Link' => $Link
            )
        );
    });