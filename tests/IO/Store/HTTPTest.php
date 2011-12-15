<?php

    class PositiveHTTPTest extends PHPUnit_Framework_TestCase
    {
        public function setUp ()
        {
            !extension_loaded('curl') && $this->markTestSkipped('No curl Installed');
        }

        public function testOpen()
        {
            F::Run('Engine.IO', 'Open',
                array(
                     'Storage' => 'HTTP',
                     'Driver'  => 'IO.Store.HTTP'
                ));
        }

        public function testRead ()
        {
            $this->assertNotEmpty(
                F::Run ('Engine.IO', 'Read',
                    array(
                         'Storage' => 'HTTP',
                         'Where' =>
                             array(
                                 'ID' => 'http://localhost/test'
                             )
                    )));
        }

        public function testWrite ()
        {
            $this->assertNotEmpty (
                 F::Run ('Engine.IO', 'Write',
                     array(
                          'Storage' => 'HTTP',
                          'Where'   =>
                          array(
                              'ID' => 'http://localhost/test'
                          ),
                          'Data' => array(
                              'Key' => rand ()
                          )
                     )));
        }

        public function testClose ()
        {
            $this->assertNull(
                F::Run ('Engine.IO', 'Close',
                    array(
                         'Storage' => 'HTTP'
                    )));
        }

        public function testExecute()
        {
            $this->assertTrue(
                F::Run ('Engine.IO', 'Execute',
                    array(
                        'Storage' => 'HTTP'
                    )));
        }
    }
