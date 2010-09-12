<?php
	  function F_RIJNDAEL256_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_RIJNDAEL256_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_RIJNDAEL256_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_RIJNDAEL256_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_RIJNDAEL256_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_RIJNDAEL256_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_RIJNDAEL256_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_RIJNDAEL256_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_RIJNDAEL256_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_RIJNDAEL256_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_RIJNDAEL256_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_RIJNDAEL256_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_RIJNDAEL256_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_RIJNDAEL256_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_RIJNDAEL256_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_RIJNDAEL256_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_STREAM)
	    }