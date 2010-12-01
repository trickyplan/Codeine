<?php

    $Timeout = 2000; // Максимальное время выполнения, миллисекунды.
   
    self::$Collection->Query('@All'); // Которые не были обработаны
    self::$Collection->Sort('Priority', 'DESC');
    self::$Collection->Load();

    Profiler::Go('Event Queue Server');

    // Обрабатываем по очереди

    $IC = 0;
    $HNDC = 0;

    if (self::$Collection->Length > 0)
        {
            foreach (self::$Collection->_Items as $Item)
                {
                    if (Profiler::Lap('Event Queue Server') < $Timeout)
                    {
                        if (($NN = Event::Handle($Item))>0)
                        {
                            $HNDC+= $NN;
                            $IC++;
                        }
                    }
                    else
                        break;
                }
            View::Add('Всего событий: '.self::$Collection->Length.'<br/> Обработано событий: '.$IC.'<br/>  Сработало слотов: '.$HNDC.'');
        }
    else
        View::Add('В очереди нет событий');