#!/usr/bin/php5
<?php

    declare(ticks = 1) ;

    function sigHandler($Signal) // FIXME
    {
      switch($Signal)
      {
        case SIGTERM:
        {
          exit(0);
          break;
        }
        default: {
          // TODO
        }
      }
    }

    pcntl_signal(SIGTERM, "sigHandler");

    $daemon = true;

    include 'Codeine.php';

    F::Bootstrap ();

    $Child  = pcntl_fork ();
    $Children = array();

    if ($Child)
    {
        exit;
    }

    posix_setsid ();

    while ($daemon)
    {
        if (count($Children) < 5)
        {
            $PID = pcntl_fork ();

            if ($PID == -1)
                exit('Cannot fork');
            elseif ($PID)
                $Children[$PID] = true;
            else
            {
                $PID     = getmypid ();
                $Message = F::Run (
                    array(
                         'Receive' => 'Deferred',
                         'Scope'   => 'Deferred'
                    )
                );

                if (F::Run (
                    array(
                         'Data'  => array('Create', 'Deferred'),
                         'ID'    => $Message['ID'],
                         'Scope' => 'Deferred',
                         'Value' => F::Run ($Message['Call'])
                    )
                )
                )
                    echo $Message['ID'];
                exit;
            }
        }
        else
            sleep (5);

        while ($PID = pcntl_waitpid(-1, $Status, WNOHANG)) {
            if ($PID == -1)
            {
                $Children = array();
                break;
            }
            else
                unset($Children[$PID]);
          }
    }

// TODO UNIQ