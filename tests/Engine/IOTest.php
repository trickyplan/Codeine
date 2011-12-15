<?php

    class PositiveDataTest extends PHPUnit_Framework_TestCase
    {
        public function testOpen()
        {
            F::Run('Engine.IO', 'Open',
                array(
                     'Storage' => 'Test',
                     'URL'     => 'localhost',
                     'Driver'  => 'IO.Store.Test'
                ));
        }

        public function testRead ()
        {
            $this->assertEquals('Pong!',
                F::Run ('Engine.IO', 'Read',
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
                 F::Run ('Engine.IO', 'Write',
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
                F::Run ('Engine.IO', 'Close',
                    array(
                         'Storage' => 'Test'
                    )));
        }

        public function testExecute()
        {
            $this->assertTrue(
                F::Run ('Engine.IO', 'Execute',
                    array(
                        'Storage' => 'Test'
                    )));
        }
    }
