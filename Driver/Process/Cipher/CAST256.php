<?php
	  function F_CAST256_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_CAST_256, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_CAST256_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_CAST_256, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_CAST256_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_CAST_256, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_CAST256_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_CAST_256, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_CAST256_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_CAST_256, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_CAST256_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_CAST_256, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_CAST256_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_CAST_256, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_CAST256_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_CAST_256, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_CAST256_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_CAST_256, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_CAST256_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_CAST_256, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_CAST256_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_CAST_256, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_CAST256_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_CAST_256, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_CAST256_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_CAST_256, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_CAST256_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_CAST_256, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_CAST256_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_CAST_256, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_CAST256_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_CAST_256, $key, $text, MCRYPT_MODE_STREAM)
	    }