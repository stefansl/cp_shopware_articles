<?php
/**
 * Created by PhpStorm.
 * User: stefansl
 * Date: 04.06.14
 * Time: 14:11
 */

namespace CLICKPRESS;


/**
 * Class ShopwareApiClient
 *
 * @package CLICKPRESS
 */
class ShopwareApiClient extends \Backend
{
    /**
     *
     */
    const METHODE_GET = 'GET';

    /**
     * @var array
     */
    protected $validMethods = array(
        self::METHODE_GET,
    );
    /**
     * @var string
     */
    protected $apiUrl;
    /**
     * @var resource
     */
    protected $cURL;

    /**
     * @param $apiUrl
     * @param $username
     * @param $apiKey
     */
    public function __construct( $apiUrl, $username, $apiKey )
    {
        $this->apiUrl = rtrim( $apiUrl, '/' ) . '/';
        //Initializes the cURL instance
        $this->cURL = curl_init();
        curl_setopt( $this->cURL, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $this->cURL, CURLOPT_FOLLOWLOCATION, false );
        curl_setopt( $this->cURL, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST );
        curl_setopt( $this->cURL, CURLOPT_USERPWD, $username . ':' . $apiKey );
        curl_setopt(
            $this->cURL,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json; charset=utf-8',
            )
        );
    }

    /**
     * @param       $url
     * @param array $params
     *
     * @return mixed|void
     * @throws \Exception
     */
    public function get( $url, $params = array() )
    {
        return $this->call( $url, self::METHODE_GET, array(), $params );
    }

    /**
     * @param        $url
     * @param string $method
     * @param array  $data
     * @param array  $params
     *
     * @return mixed|void
     * @throws \Exception
     */
    public function call( $url, $method = self::METHODE_GET, $data = array(), $params = array() )
    {
        if (!in_array( $method, $this->validMethods )) {
            throw new \Exception( 'Invalid HTTP-Methode: ' . $method );
        }
        $queryString = '';
        if (!empty($params)) {
            $queryString = http_build_query( $params );
        }
        $url        = rtrim( $url, '?' ) . '?';
        $url        = $this->apiUrl . $url . $queryString;
        $dataString = json_encode( $data );
        curl_setopt( $this->cURL, CURLOPT_URL, $url );
        curl_setopt( $this->cURL, CURLOPT_CUSTOMREQUEST, $method );
        curl_setopt( $this->cURL, CURLOPT_POSTFIELDS, $dataString );
        $result   = curl_exec( $this->cURL );
        $httpCode = curl_getinfo( $this->cURL, CURLINFO_HTTP_CODE );

        return $this->prepareResponse( $result, $httpCode );
    }

    /**
     * @param $result
     * @param $httpCode
     *
     * @return mixed|void
     */
    protected function prepareResponse( $result, $httpCode )
    {

        if (null === $decodedResult = json_decode( $result, true )) {
            $jsonErrors = array(
                JSON_ERROR_NONE      => 'An error occured',
                JSON_ERROR_DEPTH     => 'The maximum stack depth has been reached',
                JSON_ERROR_CTRL_CHAR => 'Error with chars, please check your encoding',
                JSON_ERROR_SYNTAX    => 'Syntax error',
            );

            $this->log( 'Error while reading JSON: ' . $jsonErrors[json_last_error()], __METHOD__, TL_ERROR );

            return;
        }

        if (!isset($decodedResult['success'])) {

            $this->log( 'Error while reading JSON: Invalid Response', __METHOD__, TL_ERROR );

            return;
        }

        if (!$decodedResult['success']) {
            $this->log( 'Error while reading JSON: Invalid Response: ' . $decodedResult['message'], __METHOD__, TL_ERROR );

            return;
        }

        return $decodedResult;
    }
}