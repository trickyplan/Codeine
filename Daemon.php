<?php

    include 'Codeine.php';

    F::Bootstrap();

    while (true)
    {
        $Message = F::Run(
                            array(
                                'Receive' => 'Deferred',
                                'Scope' => 'Deferred'
                            )
                        );

        if (F::Run(
                array(
                    'Data' => array('Create', 'Deferred'),
                    'ID' => $Message['ID'],
                    'Scope' => 'Deferred',
                    'Value' => F::Run($Message['Call'])
                )
            ))
            echo $Message['ID'];

        sleep(5);
    }