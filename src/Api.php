<?php
namespace Clickatell;

use Clickatell\ClickatellException;
use Clickatell\RestV1;
use Clickatell\RestV2;

class Api
{
    /**
     * Create a new API connection
     *
     * @param string $apiToken The token found on your integration
     */
    public static function load(string $apiToken, string $type = 'REST', $version = '1')
    {
        switch ($type == 'REST') {
            case 'REST':
                if ($version == '1') {
                    return new RestV1($apiToken);
                } else if ($version == '2') {
                    return new RestV2($apiToken);
                }
            break;
        }
    }

    /**
     * Guess the version of the api to use based on the api base url.
     *
     * @param string $baseUri The token found on your integration
     */
    public static function guessVersion(string $baseUri)
    {
        if (stripos($baseUri, '//api.clickatell.com/rest') !== false) {
            // Using version 1
            return '1';
        } else if (stripos($baseUri, '//platform.clickatell.com') !== false) {
            // Using version 2
            return '2';
        } else {
            throw new ClickatellException("Couldn't guess version of rest api, base url not known [$baseUri]");
        }
    }
}
