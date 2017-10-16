<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Common;


use Phalcon\Mvc\Url;

class BaseUrl extends Url
{
    /**
     * @param array $query
     * @param array $exception
     * @return array
     */
    public function currentQuery($query = [], $exception = [])
    {
        $exception[] = '_url';
        $exception[] = 'submit';

        foreach ($exception as $unset_key) {
            unset($query[$unset_key]);
        }

        return $query;
    }

    /**
     * @param null $args
     * @param null $local
     * @param null $baseUri
     * @return string
     */
    public function currentUrl($args = null, $local = null, $baseUri = null)
    {
        $router = $this->getDI()->getRouter();
        $uri = $router->getRewriteUri();
        return $this->get($uri, $args, $local, $baseUri);
    }
}