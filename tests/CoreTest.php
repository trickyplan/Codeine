<?php

    class CoreTest extends PHPUnit_Framework_TestCase
    {
        public function testFLoaded()
        {
            $this->assertTrue(class_exists('F'));
        }

        public function testSimpleRun()
        {
            $this->assertEquals(true, F::Run(
               array(
                    '_N' => 'Test.Core',
                    '_F' => 'SimpleRun'
               )
            ));
        }
    }
