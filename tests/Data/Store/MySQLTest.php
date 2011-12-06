<?php

    class PositiveMySQLTest extends PHPUnit_Framework_TestCase
    {
        public function testOpen ()
        {
            $Call = array(
                '_N'       => 'Engine.Data',
                '_F'       => 'Open',
                'Storage'  => 'MySQL',
                // 'Server' => 'localhost',
                'Database' => 'ox',
                'User'     => 'root',
                'Password' => 'phoenix',
                'Driver'   => 'Data.Store.MySQL'
            );

            $Result = F::Run ($Call);
            $this->assertInstanceOf('mysqli', $Result['Link']);

        }

        /*public function testRead ()
        {
            $this->assertEquals ('Pong!',
                F::Run (array(
                    '_N'       => 'Engine.Data',
                    '_F'       => 'Read',
                    'Storage'  => 'Primary',
                    'Handle'   => 'Ping!'
                )));
        }

        public function testWrite ()
        {
            $this->assertEquals('Pong!',
             F::Run (array(
                          '_N'       => 'Engine.Data',
                          '_F'       => 'Write',
                          'Storage'  => 'Primary',
                          'Handle'   => 'Ping!',
                          'Data'     => 'Pong!'
                     )));
        }

        public function testClose ()
        {
            $this->assertTrue (
                F::Run (array(
                             '_N'       => 'Engine.Data',
                             '_F'       => 'Close',
                             'Storage'  => 'Primary'
                        )));
        }*/
    }