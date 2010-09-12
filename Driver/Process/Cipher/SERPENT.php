<?php
	  function F_SERPENT_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_SERPENT, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_SERPENT_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_SERPENT, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_SERPENT_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_SERPENT, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_SERPENT_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_SERPENT, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_SERPENT_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_SERPENT, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_SERPENT_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_SERPENT, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_SERPENT_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_SERPENT, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_SERPENT_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_SERPENT, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_SERPENT_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_SERPENT, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_SERPENT_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_SERPENT, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_SERPENT_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_SERPENT, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_SERPENT_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_SERPENT, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_SERPENT_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_SERPENT, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_SERPENT_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_SERPENT, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_SERPENT_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_SERPENT, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_SERPENT_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_SERPENT, $key, $text, MCRYPT_MODE_STREAM)
	    }