<?php

namespace Clickatell;

use Clickatell\ClickatellException;

class ApiBase
{
    /**
     * @see https://www.clickatell.com/developers/api-documentation/rest-api-send-message/
     *
     * @param array $message The message parameters
     *
     * @return array
     */
    public function sendMessage(array $message)
    {
        throw new ClickatellExceptoin('sendMessage not implemented by this class');
    }

    /**
     * This method receives messages after a callback url has been landed on by the provider.
     * The parameters passed back should be handled by the driver.
     *
     * @param array $params The parameters received by the listener.
     *
     */
    public function listen(array $params) {
        throw new ClickatellExceptoin('listen is not implemented by this class');
    }
}

?>
