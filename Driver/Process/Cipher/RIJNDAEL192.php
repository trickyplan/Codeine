<?php
	  function F_RIJNDAEL192_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_192, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_RIJNDAEL192_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_192, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_RIJNDAEL192_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_192, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_RIJNDAEL192_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_192, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_RIJNDAEL192_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_192, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_RIJNDAEL192_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_192, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_RIJNDAEL192_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_192, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_RIJNDAEL192_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_192, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_RIJNDAEL192_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_192, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_RIJNDAEL192_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_192, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_RIJNDAEL192_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_192, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_RIJNDAEL192_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_192, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_RIJNDAEL192_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_192, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_RIJNDAEL192_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_192, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_RIJNDAEL192_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_192, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_RIJNDAEL192_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_192, $key, $text, MCRYPT_MODE_STREAM)
	    }