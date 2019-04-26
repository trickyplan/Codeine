<?php

    class mailDomainSigner
    {

        ///////////////////////
        // PRIVATE VARIABLES //
        ///////////////////////
        private $pkid = null;
        private $s;
        private $d;

        //////////////////////
        // AGENT PROPERTIES //
        //////////////////////
        private $__app_name         = "PHP mailDomainSigner";
        private $__app_ver          = "0.1-20110129";
        private $__app_url          = "http://code.google.com/p/php-mail-domain-signer/";

        /**
         * Constructor
         * @param string $private_key Raw Private Key to Sign the mail
         * @param string $d The domain name of the signing domain
         * @param string $s The selector used to form the query for the public key
         * @author Ahmad Amarullah
         */
        public function __construct($private_key, $d, $s)
        {
            // Get a private key
            $this->pkid = openssl_pkey_get_private($private_key);

            // Save Domain and Selector
            $this->d    = $d;
            $this->s    = $s;
        }

        ///////////////////////
        // PRIVATE FUNCTIONS //
        ///////////////////////

        /**
         * The nofws ("No Folding Whitespace") Canonicalization Algorithm
         * Function implementation according to RFC4870
         *
         * @link http://tools.ietf.org/html/rfc4870#page-19
         * @param array $raw_headers Array of Mail Headers
         * @param string $raw_body Raw Mail body
         * @return string nofws Canonicalizated data
         * @access public
         * @author Ahmad Amarullah
         */
        public function nofws($raw_headers, $raw_body)
        {
            // nofws-ed headers
            $headers = array();

            // Loop the raw_headers
            foreach ($raw_headers as $header) {
                // Replace all Folding Whitespace
                $headers[] = preg_replace('/[\r\t\n ]++/', '', $header);
            }

            // Join headers with LF then Add it into data
            $data = implode("\n", $headers) . "\n";

            // Loop Body Lines
            foreach (explode("\n", "\n" . str_replace("\r", "", $raw_body)) as $line) {
                    // Replace all Folding Whitespace from current line
                    // then Add it into data
                    $data .= preg_replace('/[\t\n ]++/', '', $line) . "\n";
                }

            // Remove Trailing empty lines then split it with LF
            $data = explode("\n", rtrim($data, "\n"));

            // Join array of data with CRLF and Append CRLF 
            // to the resulting line
            $data = implode("\r\n", $data) . "\r\n";

            // Return Canonicalizated Data
            return $data;
        }

        /**
         * The "relaxed" Header Canonicalization Algorithm
         * Function implementation according to RFC4871
         *
         * Originally taken from RelaxedHeaderCanonicalization
         * function in PHP-DKIM by Eric Vyncke
         *
         * @link http://tools.ietf.org/html/rfc4871#page-14
         * @link http://php-dkim.sourceforge.net/
         *
         * @param string $s Header String to Canonicalization
         * @return string Relaxed Header Canonicalizated data
         * @access public
         * @author Eric Vyncke
         */
        public function headRelaxCanon($s)
        {
            // Replace CR,LF and spaces into single SP
            $s = preg_replace("/\r\n\s+/", " ", $s);

            // Explode Header Line
            $lines = explode("\r\n", $s);

            // Loop the lines
            foreach ($lines as $key => $line) {
                // Split the key and value
                list($heading, $value) = explode(":", $line, 2);

                // Lowercase heading key
                $heading = strtolower($heading);

                // Compress useless spaces
                $value = preg_replace("/\s+/", " ", $value);

                // Don't forget to remove WSP around the value
                $lines[$key] = $heading . ":" . trim($value);
            }

            // Implode it again
            $s = implode("\r\n", $lines);

            // Return Canonicalizated Headers
            return $s;
        }

        /**
         * The "relaxed" Body Canonicalization Algorithm
         * Function implementation according to RFC4871
         *
         * @link http://tools.ietf.org/html/rfc4871#page-15
         *
         * @param string $body Body String to Canonicalization
         * @return string Relaxed Body Canonicalizated data
         * @access public
         * @author Ahmad Amarullah
         */
        public function bodyRelaxCanon($body)
        {
            // Return CRLF for empty body
            if ($body == '') {
                return "\r\n";
            }

            // Replace all CRLF to LF
            $body = str_replace("\r\n", "\n", $body);

            // Replace LF to CRLF
            $body = str_replace("\n", "\r\n", $body);

            // Ignores all whitespace at the end of lines    
            $body = rtrim($body, "\r\n");

            // Canonicalizated String Variable
            $canon_body = '';

            // Split the body into lines
            foreach (explode("\r\n", $body) as $line) {
                // Reduces all sequences of White Space within a line
                // to a single SP character
                $canon_body .= rtrim(preg_replace('/[\t\n ]++/', ' ', $line)) . "\r\n";
            }

            // Return the Canonicalizated Body
            return $canon_body;
        }


        //////////////////////
        // PUBLIC FUNCTIONS //
        //////////////////////

        /**
         * DKIM-Signature Header Creator Function 
         * implementation according to RFC4871
         *
         * Originally code inspired by AddDKIM
         * function in PHP-DKIM by Eric Vyncke
         * And rewrite it for better result
         *
         * The function use relaxed/relaxed canonicalization alghoritm
         * for better verifing validation
         *
         * different from original PHP-DKIM that used relaxed/simple
         * canonicalization alghoritm
         *
         * Doesn't include z, i and q tag for smaller data because
         * it doesn't really needed
         *
         * @link http://tools.ietf.org/html/rfc4871
         * @link http://php-dkim.sourceforge.net/
         *
         * @param string $h Signed header fields, A colon-separated list of header field names that identify the header fields presented to the signing algorithm
         * @param array $_h Array of headers in same order with $h (Signed header fields)
         * @param string $body Raw Email Body String
         * @return string DKIM-Signature Header String
         * @access public
         * @author Ahmad Amarullah
         */
        public function getDKIM($h, $_h, $body)
        {

            // Relax Canonicalization for Body
            $_b = $this->bodyRelaxCanon($body);

            // Canonicalizated Body Length [tag:l]
            $_l = strlen($_b);

            // Signature Timestamp [tag:t]
            $_t = time();

            // Hash of the canonicalized body [tag:bh]
            $_bh = base64_encode(sha1($_b, true));
            #^--for ver < PHP5.3 # $_bh= base64_encode(pack("H*",sha1($_b)));

            // Creating DKIM-Signature
            $_dkim = "DKIM-Signature: " .
                "v=1; " .                  // DKIM Version
                "a=rsa-sha1; " .           // The algorithm used to generate the signature "rsa-sha1"
                "s={$this->s}; " .         // The selector subdividing the namespace for the "d=" (domain) tag
                "d={$this->d}; " .         // The domain of the signing entity
                "l={$_l}; " .              // Canonicalizated Body length count
                "t={$_t}; " .              // Signature Timestamp
                "c=relaxed/relaxed; " .    // Message (Headers/Body) Canonicalization "relaxed/relaxed"
                "h={$h}; " .               // Signed header fields
                "bh={$_bh};\r\n\t" .       // The hash of the canonicalized body part of the message
                "b=";                     // The signature data (Empty because we will calculate it later)
            // Wrap DKIM Header
            $_dkim = wordwrap($_dkim, 76, "\r\n\t");

            // Canonicalization Header Data
            $_unsigned  = $this->headRelaxCanon(implode("\r\n", $_h) . "\r\n{$_dkim}");

            // Sign Canonicalization Header Data with Private Key
            openssl_sign($_unsigned, $_signed, $this->pkid, OPENSSL_ALGO_SHA1);

            // Base64 encoded signed data
            // Chunk Split it
            // Then Append it $_dkim
            $_dkim   .= chunk_split(base64_encode($_signed), 76, "\r\n\t");

            // Return trimmed $_dkim
            return trim($_dkim);
        }

        /**
         * DomainKey-Signature Header Creator Function 
         * implementation according to RFC4870
         *
         * The function use nofws canonicalization alghoritm
         * for better verifing validation
         *
         * NOTE: the $h and $_h arguments must be in right order
         *       if to header location upper the from header
         *       it should ordered like "to:from", don't randomize
         *       the order for better validating result.
         *
         * NOTE: if your DNS TXT contained g=*, remove it
         *
         * @link http://tools.ietf.org/html/rfc4870
         *
         * @param string $h Signed header fields, A colon-separated list of header field names that identify the header fields presented to the signing algorithm
         * @param array $_h Array of headers in same order with $h (Signed header fields)
         * @param string $body Raw Email Body String
         * @return string DomainKey-Signature Header String
         * @access public
         * @author Ahmad Amarullah
         */
        public function getDomainKey($h, $_h, $body)
        {
            // If $h = empty, dont add h tag into DomainKey-Signature
            $hval = '';
            if ($h)
                $hval = "h={$h}; ";

            // Creating DomainKey-Signature
            $_dk = "DomainKey-Signature: " .
                "a=rsa-sha1; " .           // The algorithm used to generate the signature "rsa-sha1"
                "c=nofws; " .              // Canonicalization Alghoritm "nofws"
                "d={$this->d}; " .         // The domain of the signing entity
                "s={$this->s}; " .         // The selector subdividing the namespace for the "d=" (domain) tag
                "{$hval}";                // If Exists - Signed header fields
            // nofws Canonicalization for headers and body data
            $_unsigned  = $this->nofws($_h, $body);

            // Sign nofws Canonicalizated Data with Private Key
            openssl_sign($_unsigned, $_signed, $this->pkid, OPENSSL_ALGO_SHA1);

            // Base64 encoded signed data
            // Chunk Split it
            $b = chunk_split(base64_encode($_signed), 76, "\r\n\t");

            // Append sign data into b tag in $_dk
            $_dk .= "b={$b}";

            // Return Wrapped and trimmed $_dk
            return trim(wordwrap($_dk, 76, "\r\n\t"));
        }

        /**
         * Auto Sign RAW Mail Data with DKIM-Signature
         * and DomailKey-Signature
         *
         * It Support auto positioning Signed header fields
         *
         * @param string $mail_data Raw Mail Data to be signed
         * @param string $suggested_h Suggested Signed Header Fields, separated by colon ":"
         *                                      Default: string("from:to:subject")
         * @param bool $create_dkim If true, it will generate DKIM-Signature for $mail_data
         *                                      Default: boolean(true)
         * @param bool $create_domainkey If true, it will generate DomailKey-Signature for $mail_data
         *                                      Default: boolean(true)
         * @param integer $out_sign_header_only If true or 1, it will only return signature headers as String
         *                                      If 2, it will only return signature headers as Array
         *                                      If false or 0, it will return signature headers with original mail data as String
         *                                      Default: boolean(false)
         * @return mixed Signature Headers with/without original data as String/Array depended on $out_sign_header_only parameter
         * @access public
         * @author Ahmad Amarullah
         */
        public function sign(
            $mail_data,                                 // Raw Mail Data
            $suggested_h = "from:to:subject",           // Suggested Signed Header Fields
            $create_dkim = true,                        // Create DKIM-Signature Header
            $create_domainkey = true,                   // Create DomainKey-Signature Header
            $out_sign_header_only = false               // Return Signature Header Only without original data
        ) {

            if (!$suggested_h) $suggested_h = "from:to:subject"; // Default Suggested Signed Header Fields

            // Remove all space and Lowercase Suggested Signed header fields then split it into array
            $_h = explode(":", strtolower(preg_replace('/[\r\t\n ]++/', '', $suggested_h)));

            // Split Raw Mail data into $raw_headers and $body
            list($raw_headers, $body) = explode("\r\n\r\n", $mail_data, 2);

            // Explode $raw_header into $header_list
            $header_list = preg_split("/\r\n(?![\t ])/", $raw_headers);

            // Empty Header Array
            $headers = array();

            // Loop $header_list
            foreach ($header_list as $header) {
                // Find Header Key for Array Key
                list($key) = explode(':', $header, 2);

                // Trim and Lowercase It
                $key = strtolower(trim($key));

                // If header with current key was exists
                // Change it into array
                if (isset($headers[$key])) {
                    // If header not yet array set as Array
                    if (!is_array($headers[$key]))
                        $headers[$key] = array($headers[$key]);

                    // Add Current Header as next element
                    $headers[$key][] = $header;
                }

                // If header with current key not exists
                // Insert header as string
                else {
                    $headers[$key] = $header;
                }
            }

            // Now, lets find accepted Suggested Signed header fields
            // and reorder it to match headers position

            $accepted_h = array();          // For Accepted Signed header fields
            $accepted_headers = array();    // For Accepted Header

            // Loop the Headers Array
            foreach ($headers as $key => $val) {
                // Check if $val wasn't array
                // We don't want to include multiple headers as Signed header fields
                if (!is_array($val)) {
                    // Check if this header exists in Suggested Signed header fields
                    if (in_array($key, $_h)) {
                        // If Exists, add it into accepted headers and accepted header fields
                        $accepted_h[] = $key;
                        $accepted_headers[] = $val;
                    }
                }
            }

            // If it doesn't contain any $accepted_h
            // return false, because we don't have enough data
            // for signing email
            if (count($accepted_h) == 0)
                return false;

            // Create $_hdata for Signed header fields
            // by imploding it with colon
            $_hdata = implode(":", $accepted_h);

            // New Headers Variable
            $_nh = array("x-domain-signer" => "X-Domain-Signer: {$this->__app_name} {$this->__app_ver} <$this->__app_url>");

            // Create DKIM First
            if ($create_dkim)
                $_nh['dkim-signature'] = $this->getDKIM($_hdata, $accepted_headers, $body);

            // Now Create Domain-Signature
            if ($create_domainkey)
                $_nh['domainKey-signature'] = $this->getDomainKey($_hdata, $accepted_headers, $body);

            // Implode $_nh with \r\n
            $to_be_appended_headers = implode("\r\n", $_nh);

            // Return Immediately if
            // * $out_sign_header_only=true (as headers string)
            // * $out_sign_header_only=2    (as headers array)
            if ($out_sign_header_only === 2)
                return $_nh;
            elseif ($out_sign_header_only)
                return "{$to_be_appended_headers}\r\n";

            // Return signed headers with original data
            return "{$to_be_appended_headers}\r\n{$mail_data}";
        }
    }

    class mail_signature {
        /**
         * https://github.com/louisameline/php-mail-signature
         */

        private $private_key;
        private $domain;
        private $selector;
        private $options;
        private $canonicalized_headers_relaxed;
        
        public function __construct($private_key, $passphrase, $domain, $selector, $options = array()){
            
            // prepare the resource
            $this -> private_key = openssl_get_privatekey($private_key, $passphrase);
            $this -> domain = $domain;
            $this -> selector = $selector;
            
            /*
            * This function will not let you ask for the simple header canonicalization because
            * it would require more code, it would not be more secure and mails would yet be
            * more likely to be rejected : no point in that
            */
            $default_options = array(
                'use_dkim' => true,
                // disabled by default, see why at the top of this file
                'use_domainKeys' => false,
                /*
                * Allowed user, defaults is "@<MAIL_DKIM_DOMAIN>", meaning anybody in the
                * MAIL_DKIM_DOMAIN domain. Ex: 'admin@mydomain.tld'. You'll never have to use
                * this unless you do not control the "From" value in the e-mails you send.
                */
                'identity' => null,
                // "relaxed" is recommended over "simple" for better chances of success
                'dkim_body_canonicalization' => 'relaxed',
                // "nofws" is recommended over "simple" for better chances of success
                'dk_canonicalization' => 'nofws',
                /*
                * The default list of headers types you want to base the signature on. The
                * types here (in the default options) are to be put in lower case, but the
                * types in $options can have capital letters. If one or more of the headers
                * specified are not found in the $headers given to the function, they will
                * just not be used.
                * If you supply a new list, it will replace the default one
                */
                'signed_headers' => array(
                    'mime-version',
                    'from',
                    'to',
                    'subject',
                    'reply-to'
                )
            );
            
            if(isset($options['signed_headers'])){
            
                // lower case fields
                foreach($options['signed_headers'] as $key => $value){
                    $options['signed_headers'][$key] = strtolower($value);
                }
                
                // delete the default fields if a custom list is provided, not merge
                $default_options['signed_headers'] = array();
            }
            
            $this->options = array_replace_recursive($default_options, $options);
        }
        
        /**
         * This function returns an array of relaxed canonicalized headers (lowercases the
         * header type and cleans the new lines/spaces according to the RFC requirements).
         * only headers required for signature (specified by $options) will be returned
         * the result is an array of the type : array(headerType => fullHeader [, ...]),
         * e.g. array('mime-version' => 'mime-version:1.0')
         */
        private function _dkim_canonicalize_headers_relaxed($sHeaders){
            
            $aHeaders = array();
            
            // a header value which is spread over several lines must be 1-lined
            $sHeaders = preg_replace("/\n\s+/", " ", $sHeaders);
            
            $lines = explode("\r\n", $sHeaders);
            
            foreach($lines as $key => $line){
                
                // delete multiple WSP
                $line = preg_replace("/\s+/", ' ', $line);
                
                if(!empty($line)){
                
                    // header type to lowercase and delete WSP which are not part of the
                    // header value
                    $line = explode(':', $line, 2);
                    
                    $header_type = trim(strtolower($line[0]));
                    $header_value = trim($line[1]);
                    
                    if(		in_array($header_type, $this -> options['signed_headers'])
                        or	$header_type == 'dkim-signature'
                    ){
                        
                        $aHeaders[$header_type] = $header_type.':'.$header_value;
                    }
                }
            }
            
            return $aHeaders;
        }
        
        /**
         * Apply RFC 4871 requirements before body signature. Do not modify
         */
        private function _dkim_canonicalize_body_simple($body){
            
            /*
            * Unlike other libraries, we do not convert all \n in the body to \r\n here
            * because the RFC does not specify to do it here. However it should be done
            * anyway since MTA may modify them and we recommend you do this on the mail
            * body before calling this DKIM class - or signature could fail.
            */
            
            // remove multiple trailing CRLF
            while(mb_substr($body, mb_strlen($body, 'UTF-8')-4, 4, 'UTF-8') == "\r\n\r\n"){
                $body = mb_substr($body, 0, mb_strlen($body, 'UTF-8')-2, 'UTF-8');
            }
            
            // must end with CRLF anyway
            // if(mb_substr($body, mb_strlen($body, 'UTF-8')-2, 2, 'UTF-8') != "\r\n"){
            //     $body .= "\r\n";
            // }
            
            return $body;
        }
        
        /**
         * Apply RFC 4871 requirements before body signature. Do not modify
        */
        private function _dkim_canonicalize_body_relaxed($body){
            $body = preg_replace('/(?<!\r)\n/', "\r\n", $body);
            $lines = explode("\r\n", $body);
            
            foreach($lines as $key => $value){
                
                // ignore WSP at the end of lines
                $value = rtrim($value);
                
                // ignore multiple WSP inside the line
                $lines[$key] = preg_replace('/\s+/', ' ', $value);
            }
            
            $body = implode("\r\n", $lines);
            
            // ignore empty lines at the end
            $body = $this -> _dkim_canonicalize_body_simple($body);
            
            return $body;
        }
        
        /**
         * Apply RFC 4870 requirements before body signature. Do not modify
         */
        private function _dk_canonicalize_simple($body, $sHeaders){
            
            /*
            * Note : the RFC assumes all lines end with CRLF, and we assume you already
            * took care of that before calling the class
            */
            
            // keep only headers wich are in the signature headers
            $aHeaders = explode("\r\n", $sHeaders);
            foreach($aHeaders as $key => $line){
                
                if(!empty($aHeaders)){
                
                    // make sure this line is the line of a new header and not the
                    // continuation of another one
                    $c = substr($line, 0, 1);
                    $is_signed_header = true;
                    
                    // new header
                    if(!in_array($c, array("\r", "\n", "\t", ' '))){
                    
                        $h = explode(':', $line);
                        $header_type = strtolower(trim($h[0]));
                        
                        // keep only signature headers
                        if(in_array($header_type, $this -> options['signed_headers'])){
                            $is_signed_header = true;
                        }
                        else {
                            unset($aHeaders[$key]);
                            $is_signed_header = false;
                        }
                    }
                    // continuated header
                    else {
                        // do not keep if it belongs to an unwanted header
                        if($is_signed_header == false){
                            unset($aHeaders[$key]);
                        }
                    }
                }
                else {
                    unset($aHeaders[$key]);
                }
            }
            $sHeaders = implode("\r\n", $aHeaders);
            
            $mail = $sHeaders."\r\n\r\n".$body."\r\n";
            
            // remove all trailing CRLF
            while(mb_substr($body, mb_strlen($mail, 'UTF-8')-4, 4, 'UTF-8') == "\r\n\r\n"){
                $mail = mb_substr($mail, 0, mb_strlen($mail, 'UTF-8')-2, 'UTF-8');
            }
            
            return $mail;
        }
        
        /**
         * Apply RFC 4870 requirements before body signature. Do not modify
        */
        private function _dk_canonicalize_nofws($body, $sHeaders){
            
            // HEADERS
            // a header value which is spread over several lines must be 1-lined
            $sHeaders = preg_replace("/\r\n\s+/", " ", $sHeaders);
            
            $aHeaders = explode("\r\n", $sHeaders);
            
            foreach($aHeaders as $key => $line){
                
                if(!empty($line)){
                
                    $h = explode(':', $line);
                    $header_type = strtolower(trim($h[0]));
                    
                    // keep only signature headers
                    if(in_array($header_type, $this -> options['signed_headers'])){
                        
                        // delete all WSP in each line
                        $aHeaders[$key] = preg_replace("/\s/", '', $line);
                    }
                    else {
                        unset($aHeaders[$key]);
                    }
                }
                else {
                    unset($aHeaders[$key]);
                }
            }
            $sHeaders = implode("\r\n", $aHeaders);
            
            // BODY
            // delete all WSP in each body line
            $body_lines = explode("\r\n", $body);
            
            foreach($body_lines as $key => $line){
                $body_lines[$key] = preg_replace("/\s/", '', $line);
            }
            
            $body = rtrim(implode("\r\n", $body_lines))."\r\n";
            
            return $sHeaders."\r\n\r\n".$body;
        }
        
        /**
         * The function will return no DKIM header (no signature) if there is a failure,
         * so the mail will still be sent in the default unsigned way
         * it is highly recommended that all linefeeds in the $body and $headers you submit
         * are in the CRLF (\r\n) format !! Otherwise signature may fail with some MTAs
         */
        private function _get_dkim_header($body){
            
            $body =
                ($this -> options['dkim_body_canonicalization'] == 'simple') ?
                $this -> _dkim_canonicalize_body_simple($body) :
                $this -> _dkim_canonicalize_body_relaxed($body);
            
            $bh = rtrim(chunk_split(base64_encode(hash('sha256', $body, true)), 64, "\r\n\t"));
            $i_part =
                ($this -> options['identity'] == null) ?
                '' :
                ' i='.$this -> options['identity'].';'."\r\n\t";
            
            $dkim_header =
                    'v=1;'."\r\n\t".
                    'a=rsa-sha256;'."\r\n\t".
                    'q=dns/txt;'."\r\n\t".
                    's='.$this -> selector.';'."\r\n\t".
                    't='.time().';'."\r\n\t".
                    'c=relaxed/'.$this -> options['dkim_body_canonicalization'].';'."\r\n\t".
                    'h='.implode(':', array_keys($this -> canonicalized_headers_relaxed)).';'."\r\n\t".
                    'd='.$this -> domain.';'."\r\n\t".
                    $i_part.
                    'bh='.$bh.';'."\r\n\t".
                    'b=';
            
            // now for the signature we need the canonicalized version of the $dkim_header
            // we've just made
            $canonicalized_dkim_header = $this -> _dkim_canonicalize_headers_relaxed('dkim-signature: '.$dkim_header);
            
            // we sign the canonicalized signature headers
            $to_be_signed = implode("\r\n", $this -> canonicalized_headers_relaxed)."\r\n".$canonicalized_dkim_header['dkim-signature'];
            
            // $signature is sent by reference in this function
            $signature = '';
            if(openssl_sign($to_be_signed, $signature, $this -> private_key, OPENSSL_ALGO_SHA256)){
                $dkim_header .= rtrim(chunk_split(base64_encode($signature), 64, "\r\n\t"))."\r\n";
            }
            else {
                trigger_error(sprintf('Could not sign e-mail with DKIM : %s', $to_be_signed), E_USER_WARNING);
                $dkim_header = '';
            }
            
            return $dkim_header;
        }
        
        private function _get_dk_header($body, $sHeaders){
        
            // Creating DomainKey-Signature
            $domainkeys_header =
                'DomainKey-Signature: '.
                    'a=rsa-sha1; '."\r\n\t".
                    'c='.$this -> options['dk_canonicalization'].'; '."\r\n\t".
                    'd='.$this -> domain.'; '."\r\n\t".
                    's='.$this -> selector.'; '."\r\n\t".
                    'h='.implode(':', array_keys($this -> canonicalized_headers_relaxed)).'; '."\r\n\t".
                    'b=';
            
            // we signed the canonicalized signature headers + the canonicalized body
            $to_be_signed =
                ($this-> options['dk_canonicalization'] == 'simple') ?
                $this -> _dk_canonicalize_simple($body, $sHeaders) :
                $this -> _dk_canonicalize_nofws($body, $sHeaders);
            
            $signature = '';
            if(openssl_sign($to_be_signed, $signature, $this -> private_key, OPENSSL_ALGO_SHA256)){
                
                $domainkeys_header .= rtrim(chunk_split(base64_encode($signature), 64, "\r\n\t"))."\r\n";
            }
            else {
                $domainkeys_header = '';
            }
            
            return $domainkeys_header;
        }
        
        /**
         * You may leave $to and $subject empty if the corresponding headers are already
         * in $headers
         */
        public function get_signed_headers($to, $subject, $body, $headers){
            
            $signed_headers = '';
            $headers = preg_replace('/(?<!\r)\n/', "\r\n", $headers);
            
            if(!empty($to) or !empty($subject)){
                
                /*
                * To and Subject are not supposed to be present in $headers if you
                * use the php mail() function, because it takes care of that itself in
                * parameters for security reasons, so we reconstruct them here for the
                * signature only
                */
                $headers .=
                    (mb_substr($headers, mb_strlen($headers, 'UTF-8')-2, 2, 'UTF-8') == "\r\n") ?
                    '' :
                    "\r\n";
                
                if(!empty($to)) $headers .= 'To: '.$to."\r\n";
                if(!empty($subject)) $headers .= 'Subject: '.$subject."\r\n";
            }
            
            // get the clean version of headers used for signature
            $this -> canonicalized_headers_relaxed = $this -> _dkim_canonicalize_headers_relaxed($headers);
            
            if(!empty($this -> canonicalized_headers_relaxed)){
                
                // Domain Keys must be the first header, it is an RFC (stupid) requirement
                if($this -> options['use_domainKeys'] == true){
                    $signed_headers .= $this -> _get_dk_header($body, $headers);
                }
                
                if($this -> options['use_dkim'] == true){
                    $signed_headers .= $this -> _get_dkim_header($body);
                }
            }
            else {
                trigger_error('No headers found to sign the e-mail with !', E_USER_WARNING);
            }
            
            return $signed_headers;
        }
    }

    setFn('Do', function ($Call) {
        // QP the Body
        $Call['Data'] = quoted_printable_encode($Call['Data']);

        $Call['Headers']['Date'] = date('r');
        $Call['Headers']['Message-ID'] = "<".sha1(microtime(true))."@".F::Dot($Call, 'DKIM.Domain');

        $PrivateKey = openssl_get_privatekey(F::Dot($Call, 'DKIM.Private Key'), F::Dot($Call, 'DKIM.Passphrase'));

        // Create mailDomainSigner Object
        $mds = new mailDomainSigner($PrivateKey, F::Dot($Call, 'DKIM.Domain'), F::Dot($Call, 'DKIM.Selector'));

        // Create DKIM-Signature Header
        $dkim_sign = $mds->getDKIM(
            "from:to:subject:mime-version:date:message-id:content-type:content-transfer-encoding",
            $Call['Headers'],
            $Call['Data']
        );

        // Create DomainKey-Signature Header
        $domainkey_sign = $mds->getDomainKey(
            "from:to:subject:mime-version:date:message-id:content-type:content-transfer-encoding",
            $Call['Headers'],
            $Call['Data']
        );

        // Create Email Data, First Headers was DKIM and DomainKey
        list($k, $v) = explode(': ', $dkim_sign);
        $Keys[$k] = $v;
        list($k, $v) = explode(': ', $domainkey_sign);
        $Keys[$k] = $v;
        $Call['Headers'] = $Keys + $Call['Headers'];

        return $Call;
    });