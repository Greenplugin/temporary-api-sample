<?php
declare(strict_types=1);

namespace Api;

class DefaultDataProvider implements DataProviderInterface
{

    private $host;
    private $user;
    private $password;

    /**
     * @param $host
     * @param $user
     * @param $password
     */
    public function __construct(string $host, string $user = null, string $password = null)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @param string $path
     * @return string
     * @throws DataProviderException
     */
    public function get(string $path): string
    {
        // Get request from server

        return 'someResponse';
    }
}