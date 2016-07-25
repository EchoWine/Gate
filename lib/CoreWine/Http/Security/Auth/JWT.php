<?php

namespace CoreWine\Http\Security\Auth;

// @todo: custom error types
// use CoreWine\Security\Auth\JWT\(ErrorType)

class JWT {

	/**
	 * @var string Default signing algoritm 
	 */
	const DEFAULT_ALGORITM = 'HS512';

	/**
	 * @var string Default JWT parts 
	 */
	const DEFAULT_PARTS_NUMBER = 3;

	protected static $algorithm = [
		'HS256' 	=> ['hash_hmac', 'SHA256'],
		'HS512' 	=> ['hash_hmac', 'SHA512'],
		'HS384' 	=> ['hash_hmac', 'SHA384'],
		'RS256' 	=> ['openssl', 'SHA256'],
		];

	// payload complaint structure
	protected static $conformity_structure = [
		'created_at' => '',
		'jwt_id' => '',
		'domain' => '',
		'not_before' => '',
		'expiration' => '',
		'data' => [], // actual data
	];


	/**
	 * @var array|string Contains the payload 
	 */
	protected $payload;

	/**
	 * @var array|string Contains the header 
	 */
	protected $header;



	/**
	 * Sets the payload
	 *
	 * @param array|string $payload 		The payload value to be set
	 * @return boolean	 					True if success
	 */
	public static function setPayload($payload) {
		if (! self::isPayloadValid($payload)) {
			throw new Exception("Invalid payload structure.");
		}
		self::$payload = $payload;

		return true;
	}

	/**
	 * Set the JWT header
	 *
	 * @param array|string $header 		The header value to be set
	 * @return boolean 					True if success
	 */
	public static function setHeader($header) {	
		self::$header = $header;

		return true;
	}


	/**
	 * Generates a JWT token
	 *
	 * @param array|string $payload 		The string or array to use as data
	 * @param string $key 					The enconding secret key 
	 * @param string $algorithm 			The enconding algorithm 
	 * @param string $tokenID				The JWT token ID 
	 * @return string 						The JWT 
	 */	
	public static function create($payload, $key, $algorithm = self::DEFAULT_ALGORITM, $tokenID = null) {

		// check payload
		if (! isset($payload) || ($payload === null)) {
			if (! isset(self::$payload) || (self::$payload === null)) {
				throw new \InvalidArgumentException("Invalid payload provided.");
			}	
		}

		if (! isset($key) || ($key === null)) {
			throw new \InvalidArgumentException("Invalid key provided.");
		}

		// check if the header is set
		if (! isset(self::$header) || (self::header === null)) {
			// build the header
			$header = self::buildHeader($tokenID, $algorithm);
		} else {
			$header = self::$header;
		}
		

		// @todo: custom/random payload building

		// encode the header
		$strings = [];
		$json = json_encode($header, JSON_PRETTY_PRINT);
		$strings[] = self::base64URLEncode($json);

		// encode the payload
		$json = json_encode($payload, JSON_PRETTY_PRINT);
		$strings[] = self::base64URLEncode($json);	

		// build the signature
		$signature = self::buildSignature(implode('.', $strings), $key, $algorithm); 
		$strings[] = self::base64URLEncode($signature);


		return implode('.', $strings);
	}


	/**
	 * Resolves a JWT into its parts
	 *
	 * @param string $jwt 			The JWT string
	 * @param string $key 			The encoding key used to create the JWT
	 * @param string $algorithm 	The algorithm used to create the JWT
	 * @return mixed 				The resolved JWT string
	 */
	public static function resolve($jwt, $key = null, $algoritms = self::DEFAULT_ALGORITM) {

		if (! isset($key) || ($key === null)) {
			throw new \InvalidArgumentException("Invalid key provided.");			
		}

		// separate the string in its parts
		$parts = explode('.', $jwt);
		if (count($parts) != self::DEFAULT_PARTS_NUMBER) {
			throw new \InvalidArgumentException("Invalid parts provided.");
		}

		// decode each part
		list($encodedHeader, $encodedPayload, $encodedSignature) = $parts;

		// decode the header
		$header = json_decode(self::base64URLDecode($encodedHeader));
		if ($header === null) {
			throw new Exception("Can't decode the header.");
		}

		// decode the payload
		$payload = json_decode(self::base64URLDecode($encodedPayload));
		if ($payload === null) {
			throw new Exception("Can't decode the payload.");
		}

		// decode the signature
		$signature = self::base64URLDecode($encodedSignature);
		if ($signature === null) {
			throw new Exception("Can't decode the signature.");
		}

		//	check decoded fields validity/conformity
		if (! self::isPayloadValid($payload)) {
			throw new Exception("Invalid payload structure.");
		}

		//@todo: 
		
		
		// 	verify the signature
		// 	get the key
		//...
		$signedString = $encodedHeader . $encodedPayload;
		if (! self::isSignatureValid($signedString, $signature, $key, $header -> algorithm)) {
			throw new Exception("Invalid signature.");
		}


		return $payload;


	}

	/**
	 * Returns a base64 URL encoded string
	 *
	 * @param string $string 		The string to be encoded
	 * @return string 				The encoded string
	 */
	public static function base64URLEncode($string) {
		return str_replace('=', '', base64_encode($string));
		//strtr(str, from, to)
		//str_replace(search, replace, subject)
	}

	/**
	 * Returns a base64 decoded string
	 *
	 * @param string $string 		The string to be decoded
	 * @return string 				The decoded string
	 */
	public function base64URLDecode($string) {
		/*
		$remainder = strlen($string) % 4;

        if ($remainder) {
            $padlen = 4 - $remainder;
            $string .= str_repeat('=', $padlen);
        } */

        return base64_decode($string);
	}

	/**
	 * Builds the JWT header
	 *
	 * @param string $tokenID 		The JWT id
	 * @param string $algorithm 	The encoding algorithm
	 * @return array 				The header
	 */
	private static function buildHeader($tokenID = null, $algorithm) {
		// standard header
		$header = [
			'algorithm' => $algorithm,
			'type' => 'JWT',
			'tokenID' => '',
			// extra custom fields
			];

		// add the token unique identifier
		if (isset($tokenID) && ($tokenID !== null)) {
			$header['tokenID'] = $tokenID;
		}

		// @todo: attach extra header data 

		return $header;
	}

	/**
	 * Builds the signature
	 *
	 * @param string $string 		The string to sign
	 * @param string $key 		 	The key
	 * @param string $algorithm 	The used algorithm
	 * @return string 				The signed string
	 */
	private static function buildSignature($string, $key, $algorithm) {
		// @todo: add support for more algorithms and encoding functions

		list($function, $algorithm) = static::$algorithm[$algorithm];

		switch ($function) {
			case 'hash_hmac':
				return hash_hmac($algorithm, $string, $key);
				break;
			
			case 'openssl':
			default:
				$signature = '';

				if (openssl_sign($string, $signature, $key, $algorithm)) {
					return $signature;
				} else {
					throw new Exception("Unable to sign the string.");	
				}
		}
	}

	/**
	 * Check whather the payload complains with the used structure
	 *
	 * @param array $payload 		The payload to be check
	 * @return boolean 				True if it's does
	 */
	public function isPayloadValid($payload) {
		foreach ($payload as $key => $value) {
			if (! array_key_exists($key, self::$conformity_structure)) {
				return false;	
			}	
		}

		return true;
	}

	/**
	 * Determinates whather the signature applied to $signedString is valid according to $signature 
	 *
	 * @param string $signedString 		The signed string to verify
	 * @param string $signature 		The signature used for $signedString
	 * @param string $key 				The secret key
	 * @param string $algorithm 		The encoding algorithm
	 * @return boolean 					True if success
	 */		
	private function isSignatureValid($signedString, $signature = null, $key = null, $algorithm = null) {
		// usual checks
		// generate a signed string with the key and algorithm provided
		// compare it to $signedString

		//list($function, $algorithm)
		return true;
	}
}
