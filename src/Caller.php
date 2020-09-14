<?php


namespace Armyan\MachineCaller;


class Caller
{
    /**
     * Caller client
     *
     * @var ClientInterface
     */
    protected $client;

    /**
     * Caller constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Call the client's request
     *
     * @param string $endpoint
     * @param bool $post
     * @return array
     */
    public function request(string $endpoint, bool $post = true)
    {
        return $this->client->request($endpoint, $post);
    }
}