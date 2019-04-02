<?php
namespace Clickatell;

use Clickatell\RestV1;
use Clickatell\RestV2;

class Rest
{
    /**
     * @var string
     */
    private $apiToken = '';

    /**
     * @var string
     */
    private $baseUri = '';

    /**
     * Create a new API connection
     *
     * @param string $apiToken The token found on your integration
     */
    public function __construct(string $apiToken, string $baseUri)
    {
        if (stripos($baseUri, 'api.clickatell.com') !== false) {
            // Using version 1
            return RestV1($apiToken);
        } else if (stripos($baseUri, 'platform.clickatell.com') !== false) {
            // Using version 2
            return RestV2($apiToken);
        }
    }
}
