<?php
namespace Clickatell;

/**
 * Clickatell rest api v1 forked from https://github.com/clickatell/clickatell-php
 * Made for use with laravel-sms: https://github.com/leadthread/laravel-sms
 * Docs: https://archive.clickatell.com/developers/api-docs/rest-overview-of-api-features/
 */

use \Clickatell\Exeptions\ClickatellException;
use \Clickatell\Exceptions\RestErrorHandler;

class RestV1 extends ApiBase
{
    /**
     * API base URL
     * @var string
     */
    const API_URL = 'https://api.clickatell.com/rest';

    /**
     * API base URL
     * @var string
     */
    const API_VERSION = '1';

    /**
     * @var string
     */
    const HTTP_GET = 'GET';

    /**
     * @var string
     */
    const HTTP_POST = 'POST';

    /**
     * The CURL agent identifier
     * @var string
     */
    const AGENT = 'ClickatellV1.0';

    /**
     * @var string
     */
    private $apiToken = '';

    /**
     * Create a new API connection
     *
     * @param string $apiToken The token found on your integration
     */
    public function __construct($apiToken)
    {
        $this->apiToken = $apiToken;
    }

    /**
     * Handle CURL response from Clickatell APIs
     *
     * @param string $result   The API response
     * @param int    $httpCode The HTTP status code
     *
     * @throws Exception
     * @return array
     */
    protected function handle($result, $httpCode)
    {
        return RestErrorHandler::handle($result, $httpCode);
    }

    /**
     * Abstract CURL usage.
     *
     * @param string $uri     The endpoint
     * @param array $data    Array of parameters
     * @param string $customRequest The custom request if any like 'DELETE', 'PUT'
     *
     * @return Decoder
     */
    protected function curl($uri, $data, $customRequest = null)
    {
        // Force data object to array
        $data = $data ? (array) $data : $data;

        $headers = [
            'X-Version: ' . static::API_VERSION,
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: bearer ' . $this->apiToken
        ];

        // This is the clickatell endpoint. It doesn't really change so
        // it's safe for us to "hardcode" it here.
        $endpoint = static::API_URL . "/" . $uri;

        $curlInfo = curl_version();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, static::AGENT . ' curl/' . $curlInfo['version'] . ' PHP/' . phpversion());

        // Specify the raw post data
        if ($data && $customRequest == null) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } else if ($data && $customRequest != null) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $customRequest);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return $this->handle($result, $httpCode);
    }

    /**
     * @see https://www.clickatell.com/developers/api-documentation/rest-api-send-message/
     *
     * @param array $message The message parameters
     *
     * @return array
     */
    public function sendMessage(array $message)
    {
        $response = $this->curl('message', $message);
        return $response['data']['message'];
    }

    /**
     * @see https://www.clickatell.com/developers/api-documentation/rest-api-send-message/
     *
     * @param array $message The message parameters
     *
     * @return array
     */
    public function getMessageStatus(string $uuid)
    {
        $response = $this->curl('message/' . $uuid, []);
        return $response;
    }

    /**
     * @see https://www.clickatell.com/developers/api-documentation/rest-api-send-message/
     *
     * @return array
     */
    public function getAccountBalance()
    {
        $response = $this->curl('account/balance/', []);
        return $response;
    }

    /**
     * Stop a future dated message from being delivered.
     * @see https://archive.clickatell.com/developers/api-docs/stop-message-rest/
     *
     * @param array $message The message parameters
     *
     * @return array
     */
    public function stopMessage(string $uuid)
    {
        $response = $this->curl('message/' . $uuid, [], 'DELETE');
        return $response;
    }

    /**
     * @see https://www.clickatell.com/developers/api-documentation/rest-api-status-callback/
     *
     * @param callable $callback The function to trigger with desired parameters
     * @param string   $file     The stream or file name, default to standard input
     *
     * @return void
     */
    public static function parseStatusCallback($callback, $file = STDIN)
    {
        $body = file_get_contents($file);

        $body = json_decode($body, true);
        $keys = [
            'apiKey',
            'messageId',
            'requestId',
            'clientMessageId',
            'to',
            'from',
            'status',
            'statusDescription',
            'timestamp'
        ];

        if (!array_diff($keys, array_keys($body))) {
            $callback($body);
        }

        return;
    }

    /**
     * @see https://www.clickatell.com/developers/api-documentation/rest-api-reply-callback/
     *
     * @param callable $callback The function to trigger with desired parameters
     * @param string   $file     The stream or file name, default to standard input
     *
     * @return void
     */
    public static function parseReplyCallback($callback, $file = STDIN)
    {
        $body = file_get_contents($file);

        $body = json_decode($body, true);
        $keys = [
            'integrationId',
            'messageId',
            'replyMessageId',
            'apiKey',
            'fromNumber',
            'toNumber',
            'timestamp',
            'text',
            'charset',
            'udh',
            'network',
            'keyword'
        ];

        if (!array_diff($keys, array_keys($body))) {
            $callback($body);
        }

        return;
    }

    /**
     * This method receives messages after a callback url has been landed on by the provider.
     * The parameters passed back should be handled by the driver.
     *
     * @param array $params The parameters received by the listener.
     *
     */
    public function listen(array $params) {

    }
}
