<?php
	  function F_GOST_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_GOST, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_GOST_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_GOST, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_GOST_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_GOST, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_GOST_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_GOST, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_GOST_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_GOST, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_GOST_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_GOST, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_GOST_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_GOST, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_GOST_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_GOST, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_GOST_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_GOST, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_GOST_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_GOST, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_GOST_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_GOST, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_GOST_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_GOST, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_GOST_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_GOST, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_GOST_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_GOST, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_GOST_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_GOST, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_GOST_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_GOST, $key, $text, MCRYPT_MODE_STREAM)
	    }