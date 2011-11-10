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

        public function testMerge()
        {
            $First = array(
                'A' => 'B',
                'C',
                'D' =>
                    array('E' => 'F',
                    'G')
            );

            $Second = array(
                            'A' =>
                                array('B' => 'C'),
                            'E',
                            'D' => array
                            (
                                'E' => 'G',
                                'G' => 'H'
                            )
                        );

            $Result = array(
                'A' => array('B' => 'C'),
                'E',
                'D' => array(
                    'G',
                    'E' => 'G',
                    'G' => 'H'
                )
            );

            $this->assertEquals(F::Merge($First, $Second), $Result);
        }


        public function testMergeFlat()
        {
            $First = array('A', 'B', 'C');

            $Second = array('D', 'E', 'F');

            $Result = array('D', 'E', 'F');

            $this->assertEquals(F::Merge($First, $Second), $Result);
        }
    }
