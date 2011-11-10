<?php

    include 'codeine/Core.php';

    F::Bootstrap();

    class StackTest extends PHPUnit_Framework_TestCase
    {
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
