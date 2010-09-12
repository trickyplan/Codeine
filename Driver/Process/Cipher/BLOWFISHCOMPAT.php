<?php
	  function F_BLOWFISHCOMPAT_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_BLOWFISH_COMPAT, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_BLOWFISHCOMPAT_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_BLOWFISH_COMPAT, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_BLOWFISHCOMPAT_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_BLOWFISH_COMPAT, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_BLOWFISHCOMPAT_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_BLOWFISH_COMPAT, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_BLOWFISHCOMPAT_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_BLOWFISH_COMPAT, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_BLOWFISHCOMPAT_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_BLOWFISH_COMPAT, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_BLOWFISHCOMPAT_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_BLOWFISH_COMPAT, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_BLOWFISHCOMPAT_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_BLOWFISH_COMPAT, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_BLOWFISHCOMPAT_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_BLOWFISH_COMPAT, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_BLOWFISHCOMPAT_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_BLOWFISH_COMPAT, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_BLOWFISHCOMPAT_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_BLOWFISH_COMPAT, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_BLOWFISHCOMPAT_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_BLOWFISH_COMPAT, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_BLOWFISHCOMPAT_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_BLOWFISH_COMPAT, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_BLOWFISHCOMPAT_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_BLOWFISH_COMPAT, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_BLOWFISHCOMPAT_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_BLOWFISH_COMPAT, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_BLOWFISHCOMPAT_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_BLOWFISH_COMPAT, $key, $text, MCRYPT_MODE_STREAM)
	    }