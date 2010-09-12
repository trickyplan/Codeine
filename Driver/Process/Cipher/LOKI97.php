<?php
	  function F_LOKI97_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_LOKI97, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_LOKI97_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_LOKI97, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_LOKI97_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_LOKI97, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_LOKI97_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_LOKI97, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_LOKI97_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_LOKI97, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_LOKI97_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_LOKI97, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_LOKI97_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_LOKI97, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_LOKI97_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_LOKI97, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_LOKI97_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_LOKI97, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_LOKI97_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_LOKI97, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_LOKI97_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_LOKI97, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_LOKI97_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_LOKI97, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_LOKI97_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_LOKI97, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_LOKI97_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_LOKI97, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_LOKI97_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_LOKI97, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_LOKI97_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_LOKI97, $key, $text, MCRYPT_MODE_STREAM)
	    }