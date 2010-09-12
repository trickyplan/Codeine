<?php
	  function F_SAFERPLUS_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_SAFERPLUS, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_SAFERPLUS_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_SAFERPLUS, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_SAFERPLUS_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_SAFERPLUS, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_SAFERPLUS_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_SAFERPLUS, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_SAFERPLUS_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_SAFERPLUS, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_SAFERPLUS_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_SAFERPLUS, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_SAFERPLUS_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_SAFERPLUS, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_SAFERPLUS_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_SAFERPLUS, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_SAFERPLUS_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_SAFERPLUS, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_SAFERPLUS_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_SAFERPLUS, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_SAFERPLUS_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_SAFERPLUS, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_SAFERPLUS_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_SAFERPLUS, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_SAFERPLUS_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_SAFERPLUS, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_SAFERPLUS_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_SAFERPLUS, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_SAFERPLUS_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_SAFERPLUS, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_SAFERPLUS_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_SAFERPLUS, $key, $text, MCRYPT_MODE_STREAM)
	    }