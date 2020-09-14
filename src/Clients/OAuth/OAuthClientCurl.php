<?php

namespace Armyan\MachineCaller\Clients\OAuth;

use Armyan\MachineCaller\ClientInterface;

class OAuthClientCurl implements ClientInterface
{
    /**
     * PostFields for cUrl options
     *
     * @var string[]
     */
    protected $postFields;

    /**
     * cUrl initiated instance
     *
     * @var false|resource
     */
    protected $curl;

    /**
     * Stores the raw result,
     * after making the call
     *
     * @var
     */
    protected $result;

    /**
     * Stores the http status code,
     * after making the call
     * @var
     */
    protected $status;

    /**
     * OAuthClientCurl constructor.
     *
     * @param string $client_id
     * @param string $client_secret
     * @param string $grant_type
     * @param string $scopes
     */
    public function __construct(string $client_id, string $client_secret, string $grant_type, string $scopes = '')
    {
        $this->postFields = [
            'client_id' 		=> $client_id,
            'client_secret' 	=> $client_secret,
            'grant_type' 		=> $grant_type,
            'scopes' 		    => $scopes
        ];

        $this->curl = curl_init();
    }

    /**
     * Make the cUrl request by given endpoint and method
     *
     * @param string $endpoint
     * @param bool $post
     * @return array
     */
    public function request(string $endpoint, bool $post = true): array
    {
        $this->setOptions($endpoint, $post);

        $this->result = curl_exec($this->curl);;
        $this->status = curl_getinfo($this->curl,CURLINFO_HTTP_CODE);
        curl_close($this->curl);

        return $this->getResult();
    }

    /**
     * Set the options and fields for making the request
     *
     * @param string $endpoint
     * @param bool $post
     */
    protected function setOptions(string $endpoint, bool $post = true) : void
    {
        if (!$post) {
            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->postFields);
        } else {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($this->postFields));
        }

        curl_setopt($this->curl, CURLOPT_URL, $endpoint);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    }

    /**
     * Get the result from the endpoint
     *
     * @return array
     */
    protected function getResult() : array
    {
        if($jsonData = json_decode($this->result, true)) {
            return $jsonData;
        }

        return ['error' => true, 'error_message' => 'Connection failed'];
    }

    /**
     * Get the http status code after the request
     *
     * @return mixed
     */
    public function getHttpStatus()
    {
        return $this->status;
    }

    /**
     * Add postFields for request
     *
     * @param array $fields
     */
    public function addPostFields(array $fields) : void
    {
        foreach ($fields as $key=>$value) {
            $this->postFields[$key] = $value;
        }
    }
}