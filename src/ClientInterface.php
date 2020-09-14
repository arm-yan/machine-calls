<?php


namespace Armyan\MachineCaller;

interface ClientInterface
{
    /**
     * @param string $endpoint
     * @param bool $post
     * @return array
     */
    public function request(string $endpoint, bool $post) : array;
}