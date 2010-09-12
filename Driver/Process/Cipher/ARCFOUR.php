<?php
	  function F_ARCFOUR_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_ARCFOUR_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_ARCFOUR_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_ARCFOUR_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_ARCFOUR_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_ARCFOUR_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_ARCFOUR_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_ARCFOUR_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_ARCFOUR_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_ARCFOUR_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_ARCFOUR_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_ARCFOUR_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_ARCFOUR_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_ARCFOUR_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_ARCFOUR_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_ARCFOUR_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_STREAM)
	    }