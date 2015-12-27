<?php
/**
 * Libreria che gestisce cookie e sessioni
 */
class Cookie{

	public static $CONFIG;

	/**
	 * Inizializza la classe
	 */
	public static function ini(){


		// Source: http://stackoverflow.com/questions/22221807/session-cookies-http-secure-flag-how-do-you-set-these
		// **PREVENTING SESSION HIJACKING**
		// Prevents javascript XSS attacks aimed to steal the session ID
		ini_set('session.cookie_httponly', 1);

		// **PREVENTING SESSION FIXATION**
		// Session ID cannot be passed through URLs
		ini_set('session.use_only_cookies', 1);

		// Uses a secure connection (HTTPS) if possible
		// ini_set('session.cookie_secure', 1);

		self::startSession();
	}

	/**
	 * Avvia una sessione
	 */
	public static function startSession(){

		session_start();
	}
			
	/**
	 * Distrugge ed elimina ogni valore della sessione
	 */
	public static function destroySession(){
		session_unset();
		session_destroy();
	}


	/**
	 * "unsetta" una sessione
	 * 
	 * @param string $name	  Il nome della sessione
	 */
	public static function sessionUnset($name){
		unset($_SESSION[$name]);
	}

	/**
	 * Ritorna true se esiste un cookie con quel nome, altrimenti ritorna false.
	 * 
	 * @param $name	 Il nome del cookie
	 * @return bool	 True se esiste, false altrimenti
	 */
	public static function exist($name){
		return isset($_COOKIE[$name]);
	}

	/**
	 * Ritorna vero se non esiste un cookie con quel nome oppure se è vuoto (vedi http://php.net/empty ) o ha valore 0
	 * 
	 * @param $name			 Il nome del cookie
	 * @return bool			 Vero se non esiste, ha valore zero o se è vuoto. False altrimenti.
	 */
	public static function isEmpty($name){
		$cookie = self::getCookie($name);
		return empty($cookie); // In alcune versioni di PHP bisogna usare una variabile altrimenti si ottiene un errore
	}

	/**
	 * Restituisce il valore di una sessione
	 * 
	 * @param string $name	  Il nome della sessione
	 * @param string $default   [opzionale] Il valore che viene ritornato se la sessione non esiste
	 * @return string		   Il valore della sessione
	 */
	public static function getSession($name, $default = ''){
		return (isset($_SESSION[$name]) ? $_SESSION[$name] : $default);
	}
	
	/**
	 * Imposta una sessione (la crea se non esiste, altrimenti la aggiorna)
	 * 
	 * @param string $name	  Il nome della sessione
	 * @param string $value	 Il valore della sessione
	 */
	public static function setSession($name, $value){
		$_SESSION[$name] = $value;
	}
	
	/**
	 * Funzione che restituisce il valore del cookie o della sessione
	 * 
	 * @param string $type	  Sesisone o Cookie
	 * @param string $name	  Nome della sessione o del cookie
	 * @param string $default   [opzionale] Il valore che viene ritornato se il cookie o la sessione non esiste.
	 * @return string		   Il valore del cookie o della sessione
	 */
	public static function get($type, $name, $default){
		switch($type){
			case 'cookie':
				$returnCode = self::getCookie($name, $default);
				break;
			case 'session':
				$returnCode = self::getSession($name, $default);
				break;
		}
		return $returnCode;
	}   
	
	/**
	 * Imposta un cookie o una sessione (aggiornando se già esistenti)
	 * 
	 * @param string $type	  Sessione o Cookie
	 * @param string $name	  Il nome del cookie/sessione
	 * @param string $value	 Il valore del cookie/sessione
	 * @param int $expiry	   [cookie only][opzionale] Fra quanto tempo deve scadere il cookie (default = un giorno)
	 * @param string $path	  [cookie only][opzionale] Il percorso nel quale il cookie sarà valido (default = /)
	 * @param bool $forceSSL	[cookie only][opzionale] Imposta se il cookie è utilizzabile solo durante connessioni sicure (SSL) (default = false, ottiene dal config)
	 * @param bool $httpOnly	[cookie only][opzionale] Se attivo impedisce l'utilizzo di cookie al di fuori delle chiamate http (es. javascript) (default = false, ottiene dal config)
	 * @return bool			 Ritorna false se ci sono stati problemi, true altrimenti
	 */
	public static function set($type, $name, $expiry = 86400, $path = '/', $forceSSL = '', $httpOnly = ''){
		switch($type){
			case 'cookie':
				$returnCode = self::setCookie($name, $type, $expiry, $path, $forceSSL, $httpOnly);
				break;
			case 'session':
				$returnCode = self::setSession($name, $type);
				break;
		}
		return $returnCode;
	} 
	
	/**
	 * Funzione che restituisce il valore del cookie
	 * 
	 * @param string $name	  Nome del cookie
	 * @param string $default   [opzionale] Il valore che viene ritornato se il cookie non esiste.
	 * @return string		   Il valore del cookie
	 */
	public static function getCookie($name, $default = ''){
		return (isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default);
	}

	/**
	 * Imposta un cookie (lo crea se non esiste, altrimenti lo aggiorna)
	 * 
	 * @param string $name	  Il nome del cookie
	 * @param string $value	 Il valore del cookie
	 * @param int $expiry	   [opzionale] Fra quanto tempo deve scadere il cookie (default = un giorno)
	 * @param string $path	  [opzionale] Il percorso nel quale il cookie sarà valido (default = /)
	 * @param bool $forceSSL	[opzionale] Imposta se il cookie è utilizzabile solo durante connessioni sicure (SSL) (default = false, ottiene dal config)
	 * @param bool $httpOnly	[opzionale] Se attivo impedisce l'utilizzo di cookie al di fuori delle chiamate http (es. javascript) (default = false, ottiene dal config)
	 * @return bool			 Ritorna false se ci sono stati problemi, true altrimenti
	 */
	public static function setCookie($name, $value, $expiry = 86400, $path = '/', $forceSSL = '', $httpOnly = ''){
		$returnCode = false;
		if (!headers_sent()){ //Impedisce l'invio dei cookie se sono già stati inviati degli header
			# Se non è stato assegnato alcun valore a $forceSSL, controlla nel config
			if($forceSSL==''){
				$forceSSL = self::$CONFIG['FORCE_SSL'];
			}
			# Se non è stato assegnato alcun valore a $httpOnly, controlla nel config
			if($httpOnly==''){
			   $httpOnly = self::$CONFIG['COOKIES_HTTP_ONLY'];
			}
			# Ottiene il dominio tramite la classe 'information'
			$domain = $_SERVER['SERVER_NAME'] == "localhost" ? NULL : $_SERVER['SERVER_NAME'];
			
			# Calcola la scadenza del cookie
			if ($expiry === -1){
				# Se viene passato come durata il valore '-1' allora imposta un timestamp equivalente alla durata 'per sempre'
				$expiry = 1893456000; // Per sempre = 2030-01-01 00:00:00
			} elseif (is_numeric($expiry)){ 
				# Se il tempo è un numero, lo somma al 'timestamp' attuale per ottenere la durata "definitiva"
				$expiry += time();
			} else { 
				# Se il tempo non è un numero lo converte in numero (es. now o tomorrow)
				$expiry = strtotime($expiry);
			}
			$returnCode = setcookie($name, $value, $expiry, $path, $domain, $forceSSL, $httpOnly);
		}
		return $returnCode;			
	}

	/**
	 * Elimina un cookie
	 *
	 * @param string $name		  Il nome del cookie
	 * @param string $path		  [opzionale] Il percorso nel quale il cookie sarà valido (default = /)
	 * @param bool $removeNow	   Se true rimuove il cookie anche dal global (senza ricaricare la pagina)   
	 * @return bool				 Ritorna false se ci sono stati problemi, true altrimenti
	 */
	public static function removeCookie($name, $path = '/', $removeNow = false){
		$returnCode = false;
		$returnCode = self::setCookie($name, '', time() - 3600, $path);
		if ($removeNow){
			unset($_COOKIE[$name]);
		}
	
		return $returnCode;
	}

	/**
	 * Delete a session
	 * 
	 * @param string $name Name of session
	 */
	public static function removeSession($name){
		unset($_SESSION[$name]);
	}
	
}