<?php
	  function F_BLOWFISH_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_BLOWFISH_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_BLOWFISH_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_BLOWFISH_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_BLOWFISH_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_BLOWFISH_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_BLOWFISH_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_BLOWFISH_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_BLOWFISH_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_BLOWFISH_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_BLOWFISH_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_BLOWFISH_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_BLOWFISH_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_BLOWFISH_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_BLOWFISH_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_BLOWFISH_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_STREAM)
	    }