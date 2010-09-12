<?php
	  function F_TRIPLEDES_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_TRIPLEDES_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_TRIPLEDES_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_TRIPLEDES_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_TRIPLEDES_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_TRIPLEDES_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_TRIPLEDES_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_TRIPLEDES_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_TRIPLEDES_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_TRIPLEDES_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_TRIPLEDES_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_TRIPLEDES_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_TRIPLEDES_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_TRIPLEDES_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_TRIPLEDES_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_TRIPLEDES_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_STREAM)
	    }