<?php
	  function F_ENIGMA_EncryptCBC ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_ENIGMA, $key, $text, MCRYPT_MODE_CBC)
	    }

	  function F_ENIGMA_DecryptCBC ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_ENIGMA, $key, $text, MCRYPT_MODE_CBC)
	    }
	  function F_ENIGMA_EncryptCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_ENIGMA, $key, $text, MCRYPT_MODE_CFB)
	    }

	  function F_ENIGMA_DecryptCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_ENIGMA, $key, $text, MCRYPT_MODE_CFB)
	    }
	  function F_ENIGMA_EncryptCTR ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_ENIGMA, $key, $text, MCRYPT_MODE_CTR)
	    }

	  function F_ENIGMA_DecryptCTR ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_ENIGMA, $key, $text, MCRYPT_MODE_CTR)
	    }
	  function F_ENIGMA_EncryptECB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_ENIGMA, $key, $text, MCRYPT_MODE_ECB)
	    }

	  function F_ENIGMA_DecryptECB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_ENIGMA, $key, $text, MCRYPT_MODE_ECB)
	    }
	  function F_ENIGMA_EncryptNCFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_ENIGMA, $key, $text, MCRYPT_MODE_NCFB)
	    }

	  function F_ENIGMA_DecryptNCFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_ENIGMA, $key, $text, MCRYPT_MODE_NCFB)
	    }
	  function F_ENIGMA_EncryptNOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_ENIGMA, $key, $text, MCRYPT_MODE_NOFB)
	    }

	  function F_ENIGMA_DecryptNOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_ENIGMA, $key, $text, MCRYPT_MODE_NOFB)
	    }
	  function F_ENIGMA_EncryptOFB ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_ENIGMA, $key, $text, MCRYPT_MODE_OFB)
	    }

	  function F_ENIGMA_DecryptOFB ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_ENIGMA, $key, $text, MCRYPT_MODE_OFB)
	    }
	  function F_ENIGMA_EncryptSTREAM ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_ENIGMA, $key, $text, MCRYPT_MODE_STREAM)
	    }

	  function F_ENIGMA_DecryptSTREAM ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_ENIGMA, $key, $text, MCRYPT_MODE_STREAM)
	    }