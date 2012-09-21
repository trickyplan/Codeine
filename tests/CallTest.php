<?php

    include '../src/Call.php';

    class CallTest extends PHPUnit_Framework_TestCase
    {

        public function setUp ()
        {

        }

        public function testAll()
        {
            $Call = new Call(
                [
                    'Value0',
                    'Key1' => 'Value1'
                ]
            );

            var_dump($Call['Key1']);

            $Call['Key2'] = 'Value2';

            var_dump($Call['Key2']);

            $Call['Key3'] = ['Subkey1' => 'Subvalue1'];

            var_dump($Call['Key3']['Subkey1']);

            $Call['Key3']['Subkey1'] = 'Subvalue2';

            var_dump($Call['Key3']['Subkey1']);

            $Call['Key3']['Subkey2'] = 'Subvalue3';

            var_dump($Call);
        }

    }
