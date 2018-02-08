<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// for testing only
//ini_set('max_execution_time', 300); // 5 minutes

class Esign {

//    const ENDPOINT_AUTH = 'https://114.31.246.75/oauth/token';
//    const ENDPOINT_REGISTRATION = 'https://114.31.246.75/api/v1/user/registrasi';
//    const ENDPOINT_SIGN_DOC = 'https://114.31.246.75/api/v1/sign';
//    const ENDPOINT_DOWNLOAD_DOC = 'https://114.31.246.75/api/v1/sign/download/';
    const GRANT_TYPE_PASSWORD = 'password';
    const GRANT_TYPE_CLIENT_CREDENTIAL = 'client_credentials';

    private $endpointAuth;
    private $endpointUserAuth;
    private $endpointRegistration;
    private $endpointSignDoc;
    private $endpointDownloadDoc;
    private $curlHelper;

    public function __construct(array $config) {
        $this->curlHelper = new CurlHelper();
        
        $this->endpointAuth = $config['urlAuth'];
        $this->endpointUserAuth = $config['urlUserAuth'];
        $this->endpointRegistration = $config['urlRegistration'];
        $this->endpointSignDoc = $config['urlSignDoc'];
        $this->endpointDownloadDoc = $config['urlDownloadDoc'];
    }

    public function GetAccessToken(AuthRequestEntity $entity) {
        $queryString = [
            'client_id' => $entity->client_id,
            'client_secret' => $entity->client_secret,
            'grant_type' => $entity->grant_type
        ];

        $response = $this->curlHelper
                ->Url($this->endpointAuth)
                ->QueryString($queryString)
                ->Post('');

        return $response;
    }

    public function UserRegistration(UserEntity $userEntity, $accessToken, $delimiter) {
        $postData = $userEntity->ktp;
        unset($userEntity->ktp);
        //var_dump($postData);

        $queryString = json_decode(json_encode($userEntity), true);

        $isJson = false;

        return $this->curlHelper
                        ->AuthHeader($accessToken)
                        ->Header('Content-Type', 'multipart/form-data; boundary=' . $delimiter)
                        ->QueryString($queryString)
                        ->Url($this->endpointRegistration)
                        ->Post($postData, $isJson);
    }

    public function GetUserAccessToken(AuthRequestEntity $entity) {
        $queryString = json_decode(json_encode($entity), true);

        return $this->curlHelper
                        ->Url($this->endpointUserAuth)
                        ->QueryString($queryString)
                        ->Post('');
    }

    public function SignDocument(SignRequestEntity $entity, $accessToken, $delimiter) {
        $postData = $entity->file;
        unset($entity->file);
        //var_dump($postData);

        $queryString = json_decode(json_encode($entity), true);

        $isJson = false;

        return $this->curlHelper
                        ->AuthHeader($accessToken)
                        ->Header('Content-Type', 'multipart/form-data; boundary=' . $delimiter)
                        ->QueryString($queryString)
                        ->Url($this->endpointSignDoc)
                        ->Post($postData, $isJson);
    }

    public function DownloadSignedDoc($idSigned, $accessToken) {
        return $this->curlHelper
                        ->Reset()
                        ->AuthHeader($accessToken)
                        ->Url($this->endpointDownloadDoc . $idSigned)
                        ->Get();
    }

}

class UserEntity extends BaseEntity {

    public $nik;
    public $nama;
    public $nip;
    public $email;
    public $jabatan;
    public $nomor_telepon;
    public $unit_kerja;
    public $instansi;
    public $kota;
    public $provinsi;
    public $ktp; // contain binary file

}

class AuthRequestEntity extends BaseEntity {

    public $client_id;
    public $client_secret;
    public $grant_type;
    public $username;
    public $password;

}

class AuthResponseEntity extends BaseEntity {

    public $access_token;
    public $token_type;
    public $expires_in;
    public $scope;

}

class SignRequestEntity extends BaseEntity {

    public $passphrase;
    public $properties;
    public $link;
    public $file;

}

class BaseEntity {

    protected $_data;

    public function __construct(array $properties = array()) {
        $this->_data = $properties;
    }

    // magic methods!
    public function __set($property, $value) {
        return $this->_data[$property] = $value;
    }

    public function __get($property) {
        return array_key_exists($property, $this->_data) ? $this->_data[$property] : null
        ;
    }

}

class CurlHelper {

    private $curl;
    private $url;
    private $headerList = [];
    private $queryString = [];
    private $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36';
    private $httpStatusCode = '200';

    public function __construct() {
        $this->curl = curl_init();
        //curl_reset($this->curl);

        $this->Ua($this->userAgent);
        $this->headerList['Authorization'] = 'Bearer ';

        curl_setopt_array($this->curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_ENCODING => "",
                //CURLINFO_HEADER_OUT => true // for debug request headers
                //CURLOPT_CAINFO, 'C:/xampp/htdocs/OSDLUKELAS2DEVEL.pem'
        ));
    }

    public function Reset() {
        $this->headerList = [];
        $this->queryString = [];
        return $this;
    }

    public function AuthHeader($token) {
        $this->headerList[] = 'Authorization: Bearer ' . $token;
        $this->headers($this->headerList);
        return $this;
    }

    public function Option($optionName, $optionValue) {
        curl_setopt($this->curl, $optionName, $optionValue);
        return $this;
    }

    public function Url($url) {
        $this->url = $url;
        curl_setopt($this->curl, CURLOPT_URL, $url);
        return $this;
    }

    public function Query($key, $value) {
        $this->queryString[$key] = $value;

        return $this;
    }

    public function QueryString(array $queries) {
        $this->queryString = $queries;

        return $this;
    }

    public function Ua($userAgent) {
        curl_setopt($this->curl, CURLOPT_USERAGENT, $userAgent);
        return $this;
    }

    public function Headers(array $headers) {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        //print_r($headers);
        return $this;
    }

    public function Header($httpHeaderName, $httpHeaderValue) {
        $this->headerList[] = $httpHeaderName . ':' . $httpHeaderValue;
        $this->headers($this->headerList);
        return $this;
    }

    public function Post($data, $isJson = true) {
        curl_setopt_array($this->curl, array(
            CURLOPT_POSTFIELDS => $isJson ? json_encode($data) : $data,
            CURLOPT_POST => 1,
            CURLOPT_CUSTOMREQUEST => "POST"
        ));
        return $this->exec();
    }

    public function Get() {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "GET");
        return $this->exec();
    }

    public function Exec() {
        if (count($this->queryString) != 0) {
            $this->Url($this->url . '?' . http_build_query($this->queryString));
        }

        $response = curl_exec($this->curl);
        $err = curl_error($this->curl);
        $this->httpStatusCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        //print_r($this->httpStatusCode);
        //$info = curl_getinfo($this->curl);
        //print_r($info['request_header']);
        //curl_close($this->curl);
        if ($err) {
            //echo "cURL Error #:" . $err;
        }

        return $response;
    }

    public function StatusCode() {
        return $this->httpStatusCode;
    }

    public static function BuildDataFiles($boundary, $fields, $files) {
        $data = '';
        $eol = "\r\n";

        $delimiter = '-------------' . $boundary;

        foreach ($fields as $name => $content) {
            $data .= "--" . $delimiter . $eol
                    . 'Content-Disposition: form-data; name="' . $name . "\"" . $eol . $eol
                    . $content . $eol;
        }

        foreach ($files as $name => $content) {
            $data .= "--" . $delimiter . $eol
                    . 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $name . '"' . $eol
                    //. 'Content-Type: image/png'.$eol
                    . 'Content-Transfer-Encoding: binary' . $eol
            ;

            $data .= $eol;
            $data .= $content . $eol;
        }
        $data .= "--" . $delimiter . "--" . $eol;

        return $data;
    }

    public function __destruct() {
        try {
            if ($this->curl != null) {
                curl_close($this->curl);
                unset($this->curl);
            }
        } catch (Exception $e) {
            $this->curl = null;
        }
    }

}

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// INTEGRATION TESTING
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$integrationTestNumber = 0;

if ($integrationTestNumber > 0) {


    $clientId = '76fsd-djpb';
    $clientSecret = '9bc6jn41gl';

    $response = [
        'GET_ACCESS_TOKEN' => [],
        'USER_REGISTRATION' => [],
        'GET_USER_ACCESS_TOKEN' => [],
        'SIGN_DOCUMENT' => [],
        'DOWNLOAD_SIGNED_DOC' => []
    ];

    $esign = new Esign();
    header('Content-Type: application/json');

    if ($integrationTestNumber == 1) {
        // Get access token
        $authEntity = new AuthRequestEntity();
        $authEntity->client_id = $clientId;
        $authEntity->client_secret = $clientSecret;
        $authEntity->grant_type = Esign::GRANT_TYPE_CLIENT_CREDENTIAL;
        $authResponse = json_decode($esign->GetAccessToken($authEntity), true);
        $response['GET_ACCESS_TOKEN'] = $authResponse;
        $accessToken = $authResponse['access_token'];

        // User registration
        $namaDepan = ['Aaron', 'Abagnale', 'Abbey', 'Abel', 'Abelson', 'Abourezk', 'Bentsen', 'Berger', 'Berry', 'Bevel'];
        $namaBelakang = ['Hank', 'Frank', 'Edward', 'Reuben', 'Hal', 'James', 'Lloyd', 'Ric', 'Wendell', 'Ken'];

        $userEntity = new UserEntity();
        $userEntity->nik = date('mdHiis');
        $userEntity->nama = $namaDepan[mt_rand(0, count($namaDepan) - 1)] . ' ' . $namaBelakang[mt_rand(0, count($namaBelakang) - 1)];
        $userEntity->nip = date('mdHis');
        $userEntity->email = 'fredhopelane@gmail.com';
        $userEntity->jabatan = 'Staff';
        $userEntity->nomor_telepon = '021500888';
        $userEntity->unit_kerja = 'DEPUTI 3';
        $userEntity->instansi = 'LEMSANEG';
        $userEntity->kota = 'Jakarta';
        $userEntity->provinsi = 'DKI Jakarta';

        $file = 'C:/xampp/htdocs/logo.png';
        $boundary = uniqid();
        $delimiter = '-------------' . $boundary;
        $files['ktp'] = file_get_contents($file);
        $post_data = CurlHelper::BuildDataFiles($boundary, array(), $files);

        $userEntity->ktp = $post_data;
        $userResponse = $esign->UserRegistration($userEntity, $accessToken, $delimiter);
        $response['USER_REGISTRATION'] = $userResponse;
    }

    if ($integrationTestNumber == 2) {
        // Get user access token
        $authEntity = new AuthRequestEntity();
        $authEntity->client_id = $clientId;
        $authEntity->client_secret = $clientSecret;
        $authEntity->grant_type = Esign::GRANT_TYPE_PASSWORD;
        $authEntity->username = '777999';
        $authEntity->password = 'e6b0c4f334a6410499823ba1987fe8fd';
        $authResponse = $esign->GetUserAccessToken($authEntity);
        $response['GET_USER_ACCESS_TOKEN'] = $authResponse;
    }

    if ($integrationTestNumber == 3) {
        // Get user access token
        $authEntity = new AuthRequestEntity();
        $authEntity->client_id = $clientId;
        $authEntity->client_secret = $clientSecret;
        $authEntity->grant_type = Esign::GRANT_TYPE_PASSWORD;
        $authEntity->username = '777999';
        $authEntity->password = 'e6b0c4f334a6410499823ba1987fe8fd';
        $authResponse = json_decode($esign->GetUserAccessToken($authEntity), true);
        $response['GET_USER_ACCESS_TOKEN'] = $authResponse;
        $accessToken = $authResponse['access_token'];

        sleep(2); // wait for 2 seconds
        // Sign document
        $signEntity = new SignRequestEntity();
        $signEntity->passphrase = '130686'; // PIN
        $signEntity->properties = 'default';
        $signEntity->link = 'https://kemensetneg.go.id';

        // Modify this to your local environment
        $file = 'C:/xampp/htdocs/sp_pdln_6.pdf';

        $boundary = uniqid();
        $delimiter = '-------------' . $boundary;
        $files['file'] = file_get_contents($file);
        $postData = CurlHelper::BuildDataFiles($boundary, array(), $files);

        $signEntity->file = $postData;
        $signResponse = json_decode($esign->SignDocument($signEntity, $accessToken, $delimiter), true);
        $response['SIGN_DOCUMENT'] = $signResponse;
        $idSigned = $signResponse['id_signed'];

        sleep(5); // wait for 5 seconds
        // Download signed document, document in binary format
        $downloadResponse = $esign->DownloadSignedDoc($idSigned, $accessToken);

        // Modify this to your local environment
        $filename = 'C:/xampp/htdocs/signed_doc_' . $idSigned . '.pdf';

        $response['DOWNLOAD_SIGNED_DOC'] = 'check file ' . $filename;

        //var_dump($downloadResponse);

        if (strlen($downloadResponse) != 0) {
            file_put_contents($filename, $downloadResponse);
        }
    }

    echo json_encode(array_filter($response));
}