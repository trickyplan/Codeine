<?php
	  function F_RC2_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RC2, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_RC2_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RC2, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_RC2_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RC2, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_RC2_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RC2, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_RC2_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RC2, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_RC2_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RC2, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_RC2_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RC2, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_RC2_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RC2, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_RC2_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RC2, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_RC2_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RC2, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_RC2_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RC2, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_RC2_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RC2, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_RC2_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RC2, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_RC2_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RC2, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_RC2_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RC2, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_RC2_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RC2, $key, $text, MCRYPT_MODE_STREAM)
	    }