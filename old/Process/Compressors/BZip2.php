<?php

function F_BZip2_Compress($Args)
{
    return bzcompress($Args['Data'],$Args['Ratio']);
}

function F_BZip2_DeCompress($Args)
{
    return bzdecompress($Args['Data']);
}