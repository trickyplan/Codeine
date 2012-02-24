<?php

    class CSSTest extends PHPUnit_Framework_TestCase
    {
        public function testMergeFlat()
        {
            $Decoded = F::Run('Formats.CSS', 'Decode',
            array(
                 'Value' => '.heading-wrap {
	position: relative;
	background-color: var("Color.Accent");
	width: 100%;
}
.heading {
	width: 90%;
	max-width: 1140px;
	margin:0 auto;
}

.heading h2 {
	font-size: 6em !important;
	padding: 15px ;
	color: var("Color.Light");
}
.innertile
{
	margin: 25px 10px 10px 10px;
	padding: 15px;
    line-height: 1.5em;
}

.innertile h3 {
	margin-bottom:15px;
	font-size:2.5em;
}
.innertile h4 {
	margin-bottom:15px;
	font-size:1.5em;
}

p
{
    margin-bottom: 4px;
}

.indevelopment
{
    opacity: 0.4;
}

.indevelopment:hover
{
    opacity: 0.9;
}

.floating_button a {
	float: right;
	font-size: 2em;
	padding: 20px;
}

.avatar
{
    float: left;
    margin: 2px 8px 2px 2px;
}'
            ));
            $Encoded = F::Run('Formats.CSS', 'Encode', array('Value' => $Decoded));

            d(__FILE__, __LINE__, $Encoded);
            d(__FILE__, __LINE__, $Decoded);
        }
    }
