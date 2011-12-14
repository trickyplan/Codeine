<?php

    class PositiveXCacheTest extends PHPUnit_Framework_TestCase
    {
        public function setUp ()
        {
            !extension_loaded('xcache') && $this->markTestSkipped('No xCache Installed');
        }

        public function testOpen()
        {
            $this->assertTrue(F::Run('Engine.Data', 'Open',
                array(
                     'Storage' => 'XCache',
                     'URL'     => 'localhost',
                     'Driver'  => 'Data.Store.XCache'
                )));
        }

        public function testWrite ()
        {
            $Data = 'Pong!';

            $this->assertTrue (
                F::Run ('Engine.Data', 'Write',
                     array(
                          'Storage' => 'XCache',
                          'Where'   =>
                          array(
                              'ID' => 'Ping!'
                          ),
                          'Data'    => $Data
                     )));
        }

        public function testRead ()
        {
            $this->assertEquals('Pong!',
                F::Run ('Engine.Data', 'Read',
                    array(
                         'Storage' => 'XCache',
                         'Where' =>
                             array(
                                 'ID' => 'Ping!'
                             )
                    )));
        }

        public function testClose ()
        {
            $this->assertTrue(
                F::Run ('Engine.Data', 'Close',
                    array(
                         'Storage' => 'XCache'
                    )));
        }

        public function testExecute()
        {
            $this->assertTrue(
                F::Run ('Engine.Data', 'Execute',
                    array(
                        'Storage' => 'XCache'
                    )));
        }
    }
