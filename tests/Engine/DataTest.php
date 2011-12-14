<?php

    class PositiveDataTest extends PHPUnit_Framework_TestCase
    {
        public function testOpen()
        {
            F::Run('Engine.Data', 'Open',
                array(
                     'Storage' => 'Test',
                     'URL'     => 'localhost',
                     'Driver'  => 'Data.Store.Test'
                ));
        }

        public function testRead ()
        {
            $this->assertEquals('Pong!',
                F::Run ('Engine.Data', 'Read',
                    array(
                         'Storage' => 'Test',
                         'Where' =>
                             array(
                                 'ID' => 'Ping'
                             )
                    )));
        }

        public function testWrite ()
        {
            $Data = array(
                'Key' => rand()
            );

            $this->assertEquals ($Data,
                 F::Run ('Engine.Data', 'Write',
                     array(
                          'Storage' => 'Test',
                          'Where'   =>
                          array(
                              'ID' => 'Ping'
                          ),
                          'Data' => $Data,
                          'TTL' => 60
                     )));
        }

        public function testClose ()
        {
            $this->assertTrue(
                F::Run ('Engine.Data', 'Close',
                    array(
                         'Storage' => 'Test'
                    )));
        }

        public function testExecute()
        {
            $this->assertTrue(
                F::Run ('Engine.Data', 'Execute',
                    array(
                        'Storage' => 'Test'
                    )));
        }
    }
