<?php

    class PositiveAPCTest extends PHPUnit_Framework_TestCase
    {
        public function setUp ()
        {
            !extension_loaded('apc') && $this->markTestSkipped('No APC Installed');
        }

        public function testOpen()
        {
            F::Run('Engine.Data', 'Open',
                array(
                     'Storage' => 'APC',
                     'URL'     => 'localhost',
                     'Driver'  => 'Data.Store.APC'
                ));
        }

        public function testRead ()
        {
            $this->assertEquals('Pong!',
                F::Run ('Engine.Data', 'Read',
                    array(
                         'Storage' => 'APC',
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
                          'Storage' => 'APC',
                          'Where'   =>
                          array(
                              'ID' => 'Ping'
                          ),
                          'Data' => $Data
                     )));
        }

        public function testClose ()
        {
            $this->assertTrue(
                F::Run ('Engine.Data', 'Close',
                    array(
                         'Storage' => 'APC'
                    )));
        }

        public function testExecute()
        {
            $this->assertTrue(
                F::Run ('Engine.Data', 'Execute',
                    array(
                        'Storage' => 'APC'
                    )));
        }
    }
