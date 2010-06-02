<?php

function F_LZF_Compress($Args)
{
    return lzf_compress($Args['Data']);
}

function F_LZF_DeCompress($Args)
{
    return lzf_decompress ($Args['Data']);
}


?>