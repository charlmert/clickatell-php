<?php
namespace Clickatell;

use Clickatell\ClickatellException;
use Clickatell\RestV1;
use Clickatell\RestV2;

class Rest
{
    /**
     * Create a new API connection
     *
     * @param string $apiToken The token found on your integration
     */
    public static function load(string $apiToken, string $baseUri)
    {
        if (stripos($baseUri, 'api.clickatell.com') !== false) {
            // Using version 1
            return new RestV1($apiToken);
        } else if (stripos($baseUri, 'platform.clickatell.com') !== false) {
            // Using version 2
            return new RestV2($apiToken);
        } else {
            throw new ClickatellException("Couldn't guess version of rest api, please use RestV1 or RestV2 explicitly.");
        }
    }
}
