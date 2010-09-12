<?php
	  function F_DES_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_DES, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_DES_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_DES, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_DES_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_DES, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_DES_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_DES, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_DES_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_DES, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_DES_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_DES, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_DES_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_DES, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_DES_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_DES, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_DES_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_DES, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_DES_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_DES, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_DES_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_DES, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_DES_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_DES, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_DES_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_DES, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_DES_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_DES, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_DES_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_DES, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_DES_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_DES, $key, $text, MCRYPT_MODE_STREAM)
	    }