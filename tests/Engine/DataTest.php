<?php

    class PositiveDataTest extends PHPUnit_Framework_TestCase
    {
        public function testOpen ()
        {
            $Call = array(
                '_N'       => 'Engine.Data',
                '_F'       => 'Open',
                'Storage'  => 'Primary',
                'Server'   => 'localhost',
                'Database' => 'Main',
                'Driver'   => 'Data.Store.Test'
            );

            $this->assertNotEmpty(F::Run($Call));
        }

        public function testRead ()
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
        }
    }