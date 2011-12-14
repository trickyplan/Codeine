<?php

    class FrontTest extends PHPUnit_Framework_TestCase
    {
        public function testRoot()
        {
            $this->assertArrayHasKey('Output', F::Run('Code.Flow.Front', 'Run', array('Value' => '/')));
        }
    }
