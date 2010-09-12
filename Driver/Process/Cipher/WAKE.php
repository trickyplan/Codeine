<?php
	  function F_WAKE_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_WAKE, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_WAKE_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_WAKE, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_WAKE_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_WAKE, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_WAKE_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_WAKE, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_WAKE_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_WAKE, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_WAKE_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_WAKE, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_WAKE_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_WAKE, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_WAKE_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_WAKE, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_WAKE_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_WAKE, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_WAKE_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_WAKE, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_WAKE_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_WAKE, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_WAKE_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_WAKE, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_WAKE_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_WAKE, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_WAKE_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_WAKE, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_WAKE_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_WAKE, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_WAKE_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_WAKE, $key, $text, MCRYPT_MODE_STREAM)
	    }