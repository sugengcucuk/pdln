
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// for testing only
//ini_set('max_execution_time', 300); // 5 minutes

class Spde2 {

	const GRANT_TYPE_PASSWORD = 'password';
    const GRANT_TYPE_CLIENT_CREDENTIAL = 'client_credentials';

    public function __construct() {
        // $this->curlHelper = new CurlHelper();

    }


	function postRequest($url, $params, $refer = "", $timeout = 1000, $header=[])
	{
	    $curlObj = curl_init();
	    $ssl = stripos($url,'https://') === 0 ? true : false;
        // $query = http_build_query($params);

	    $options = [
	        CURLOPT_URL => $url,
	        CURLOPT_RETURNTRANSFER => 1,
	        CURLOPT_POST => 1,
	        CURLOPT_POSTFIELDS => $params,
	        CURLOPT_FOLLOWLOCATION => 1,
	        CURLOPT_AUTOREFERER => 1,
	        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
	        CURLOPT_TIMEOUT => $timeout,
	        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
	        CURLOPT_HTTPHEADER => ['Expect:'],
	        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
	        CURLOPT_REFERER => $refer
	    ];
	    if (!empty($header)) {
	        $options[CURLOPT_HTTPHEADER] = $header;
	    }
	    if ($refer) {
	        $options[CURLOPT_REFERER] = $refer;
	    }
	    if ($ssl) {	    	
	        $options[CURLOPT_SSL_VERIFYHOST] = false;
	        $options[CURLOPT_SSL_VERIFYPEER] = false;
	    }
	    curl_setopt_array($curlObj, $options);
	    $returnData = curl_exec($curlObj);
	    if (curl_errno($curlObj)) {
	        //error message
	        $returnData = curl_error($curlObj);
	    }
	    curl_close($curlObj);
	    return $returnData;
	}


	}
