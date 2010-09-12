<?php
	  function F_TWOFISH_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_TWOFISH, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_TWOFISH_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_TWOFISH, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_TWOFISH_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_TWOFISH, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_TWOFISH_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_TWOFISH, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_TWOFISH_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_TWOFISH, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_TWOFISH_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_TWOFISH, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_TWOFISH_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_TWOFISH, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_TWOFISH_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_TWOFISH, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_TWOFISH_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_TWOFISH, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_TWOFISH_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_TWOFISH, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_TWOFISH_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_TWOFISH, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_TWOFISH_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_TWOFISH, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_TWOFISH_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_TWOFISH, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_TWOFISH_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_TWOFISH, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_TWOFISH_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_TWOFISH, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_TWOFISH_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_TWOFISH, $key, $text, MCRYPT_MODE_STREAM)
	    }