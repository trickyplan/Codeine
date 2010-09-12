<?php
	  function F_CAST128_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_CAST_128, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_CAST128_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_CAST_128, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_CAST128_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_CAST_128, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_CAST128_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_CAST_128, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_CAST128_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_CAST_128, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_CAST128_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_CAST_128, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_CAST128_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_CAST_128, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_CAST128_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_CAST_128, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_CAST128_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_CAST_128, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_CAST128_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_CAST_128, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_CAST128_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_CAST_128, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_CAST128_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_CAST_128, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_CAST128_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_CAST_128, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_CAST128_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_CAST_128, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_CAST128_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_CAST_128, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_CAST128_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_CAST_128, $key, $text, MCRYPT_MODE_STREAM)
	    }