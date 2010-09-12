<?php
	  function F_XTEA_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_XTEA, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_XTEA_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_XTEA, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_XTEA_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_XTEA, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_XTEA_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_XTEA, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_XTEA_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_XTEA, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_XTEA_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_XTEA, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_XTEA_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_XTEA, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_XTEA_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_XTEA, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_XTEA_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_XTEA, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_XTEA_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_XTEA, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_XTEA_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_XTEA, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_XTEA_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_XTEA, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_XTEA_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_XTEA, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_XTEA_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_XTEA, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_XTEA_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_XTEA, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_XTEA_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_XTEA, $key, $text, MCRYPT_MODE_STREAM)
	    }