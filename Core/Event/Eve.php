<?php

class Event
{
    /**
     *  Вызвать событие Event.
     *  Событие будет помещено в очередь обработки.
     *
     * @param int $Priority - приоритет обработки сервером очереди 0 - 127
     * @param string $Signal - идентификатор сигнала
     * @param array $Subjects - субъекты сигнала
     * @param array $Arguments - аргументы
     */
    
    public static function Queue ($Args)
    {
        if (is_string($Args))
            if (($Args2 = json_decode($Args, true)) == null)
                return null;
            else
                $Args = &$Args2;

        $Args['Source'] = (string) Client::$Face;
        $Event = new Object('_Signal');
        $Event->Create($Args);
    }

    public static function Direct ($Args)
    {       
        self::Handle($Args);
        // TODO DELETE
    }

    public static function Handle ($Signal)
    {
        if ($Signal instanceof Object)
            $Args = $Signal->Data();
        elseif (is_array($Signal))
                $Args = &$Signal;
        elseif (is_string($Signal) and (($Args2 = json_decode($Signal))!== null))
            $Args = &$Args2;

        $Handlers = new Collection('_Slot');
        $Handlers->Query('@All');
        $Handlers->Load();

        if (isset($Args['HandledBy']))
            $HandledBy = $Args['HandledBy'];
        else
            $HandledBy = array();

        $Result = false;
        $HNDC=0;

        foreach($Handlers->_Items as $Handler)
        {
            if (!in_array($Handler->Name, $HandledBy))
                {
                    $HSignal = $Handler->Get('Signal', false);
                    $HSource = $Handler->Get('Source', false);

                    // Знаю что пиздец, зато быстро.
                    if (($HSource === null || in_array($Args['Source'][0], $HSource))
                           && ($HSignal === null || in_array($Args['Signal'][0], $HSignal)))
                            {
                                if ($Result = Code::E('Slots','Handle', $Args, $Handler->Get('Code')))
                                {
                                    $Signal->Add('HandledBy', $Handler->Name);
                                    $HNDC++;
                                }
                            }
                }
        }
        $Signal->Save();
        return $HNDC;
    }
}