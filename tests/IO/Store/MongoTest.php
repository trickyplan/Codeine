<?php

    class PositiveMongoTest extends PHPUnit_Framework_TestCase
    {
        public function setUp ()
        {
            !extension_loaded('mongo') && $this->markTestSkipped('No Mongo Installed');
        }

        public function testOpen()
        {
            F::Run('Engine.IO', 'Open',
                array(
                     'Storage' => 'Mongo',
                     'URL'     => 'localhost',
                     'Driver'  => 'IO.Store.Mongo',
                     'Database' => 'OX'
                ));
        }

        public function testCreate ()
        {
            $this->assertTrue (
                 F::Run ('Engine.IO', 'Write',
                     array(
                          'Storage' => 'Mongo',
                          'Scope' => 'Test',
                          'Data' => array(
                              'ID' => 'Ping',
                              'Key' => 'Value'
                          )
                     )));
        }

        public function testRead ()
        {
            $this->assertNotEmpty(
                                 F::Run ('Engine.IO', 'Read',
                                         array(
                                              'Storage' => 'Mongo',
                                              'Scope' => 'Test',
                                              'Where'   =>
                                              array(
                                                  'ID' => 'Ping'
                                              )
                                         )));
        }

        public function testDelete ()
        {
            $this->assertTrue (
                F::Run ('Engine.IO', 'Write',
                        array(
                             'Storage' => 'Mongo',
                             'Scope'   => 'Test',
                             'Where'   =>
                             array(
                                 'ID' => 'Ping'
                             ),
                             'Data'    => null
                        )));
        }

        public function testClose ()
        {
            $this->assertTrue(
                F::Run ('Engine.IO', 'Close',
                    array(
                         'Storage' => 'Mongo'
                    )));
        }

        public function testExecute()
        {
            $this->assertNotEmpty(
                F::Run ('Engine.IO', 'Execute',
                    array(
                        'Storage' => 'Mongo',
                        'Command' => "function() { return true; }"
                    )));
        }
    }
