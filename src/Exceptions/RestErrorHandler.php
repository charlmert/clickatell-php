<?php

namespace Clickatell\Exceptions;

class RestErrorHandler
{
    /**
     * Excepted HTTP statuses
     * @var string
     */
    const ACCEPTED_CODES = '200, 201, 202';

    /**
     * Excepted message status statuses
     * https://archive.clickatell.com/developers/api-docs/message-status-codes/
     *
     * @var array
     */
    const ERROR_CODES_MESSAGE_STATUS = [
        '001' => [
            'short' => 'Message unknown',
            'long' => 'The message ID is incorrect, not found or reporting is delayed. Note that a message can only be queried up to six days after it has been submitted to our gateway.'
        ],
        '002' => [
            'short' => 'Message queued',
            'long' => 'The message could not be delivered and has been queued for attempted redelivery.'
        ],
        '003' => [
            'short' => 'Delivered to gateway',
            'long' => 'Delivered to the upstream gateway or network (delivered to the recipient).'
        ],
        '004' => [
            'short' => 'Received by recipient',
            'long' => 'Confirmation of receipt on the handset of the recipient.'
        ],
        '005' => [
            'short' => 'Error with message',
            'long' => 'There was an error with the message, probably caused by the content of the message itself.'
        ],
        '006' => [
            'short' => 'User cancelled message delivery',
            'long' => 'The message was terminated by a user (stop message command).'
        ],
        '007' => [
            'short' => 'Error delivering message',
            'long' => 'An error occurred delivering the message to the handset.'
        ],
        '009' => [
            'short' => 'Routing error',
            'long' => 'An error occurred while attempting to route the message.'
        ],
        '010' => [
            'short' => 'Message expired',
            'long' => 'Message expired before we were able to deliver it to the upstream gateway. No charge applies.'
        ],
        '011' => [
            'short' => 'Message scheduled for later delivery',
            'long' => 'Message has been scheduled for delivery at a later time (delayed delivery feature).'
        ],
        '012' => [
            'short' => 'Out of credit',
            'long' => 'The message cannot be delivered due to insufficient credits.'
        ],
        '013' => [
            'short' => 'Clickatell cancelled message delivery',
            'long' => 'The message was terminated by our staff.'
        ],
    ];

    /**
     * Excepted API error statuses
     * https://archive.clickatell.com/developers/api-docs/list-of-error-codes-rest/
     *
     * @var array
     */
    const ERROR_CODES = [
        '001' => [
            'short' => 'Authentication failed',
            'long' => 'Authentication details are incorrect.',
            'solution' => null
        ],
        '007' => [
            'short' => 'IP lockdown violation',
            'long' => 'You have locked down the API instance to a specific IP address but attempted to send from an IP address different to the one you have set.',
            'solution' => "You can edit the setting to include your new server's IP address or remove the IP lockdown completely within Developers' Central's API settings."
        ],
        '100' => [
            'short' => 'Data malformed',
            'long' => 'The JSON/XML data submitted is invalid.',
            'solution' => 'Check that the syntax of your request has been formulated correctly and resubmit.'
        ],
        '101' => [
            'short' => 'Invalid or missing parameters',
            'long' => 'One or more parameters are missing or invalid.',
            'solution' => null
        ],
        '102' => [
            'short' => 'Invalid user data header',
            'long' => 'The format of the user data header is incorrect.',
            'solution' => 'Ensure valid UDH data is being passed to the API.'
        ],
        '105' => [
            'short' => 'Invalid destination address',
            'long' => 'The destination address you are attempting to send to is invalid.',
            'solution' => "Check that the number of the handset which you are attempting to send a message to is valid. The number should be in an international format, without a ' 00' prefix or leading '+' symbol OR begin with a '0' if the default country prefix is enabled on your API."
        ],
        '106' => [
            'short' => 'The sender address that is specified is incorrect.',
            'long' => 'Authentication details are incorrect',
            'solution' => "The address that the message is sent 'from' has been specified incorrectly. If you are using a Sender ID as your source address, ensure that it has been registered within your online Developers' Central account."
        ],
        '108' => [
            'short' => 'Invalid or missing API ID',
            'long' => 'The API ID is either incorrect or has not been included in the API call.',
            'solution' => "Include the correct api product id in your query, you can check the id that is associated with your api by logging into your Developers' Central account."
        ],
        '109' => [
            'short' => 'Missing message ID',
            'long' => 'This may refer to either a client message ID or API message ID – for example, when using the stop message command.',
            'solution' => null
        ],
        '113' => [
            'short' => 'Maximum message parts exceeded',
            'long' => 'The text component of the message is greater than the permitted 160 characters (70 Unicode characters). View the concatenation page for help in resolving this issue.',
            'solution' => "Set concat equal to 1,2,3-N to overcome this by splitting the content across multiple messages. Also view concatenation information (https://archive.clickatell.com/developers/api-docs/concatenation-advanced-message-send/)."
        ],
        '114' => [
            'short' => 'Cannot route message',
            'long' => 'This implies that the gateway is not currently routing messages to this network prefix. Please email support@clickatell.com with the mobile number in question.',
            'solution' => null
        ],
        '116' => [
            'short' => 'Invalid unicode data',
            'long' => 'The format of the unicode data entered is incorrect.',
            'solution' => 'Ensure that the unicode format is correct and resubmit your query.'
        ],
        '120' => [
            'short' => 'clientMessageId contains space(s)',
            'long' => 'Your specified client message ID contains a space. Space characters in client message IDs are not currently supported.',
            'solution' => 'The delivery time must be entered in minutes up to a maximum of 7 days.'
        ],
        '121' => [
            'short' => 'Destination mobile number blocked',
            'long' => 'This number is not allowed to receive messages from us and has been put on our block list.',
            'solution' => null
        ],
        '122' => [
            'short' => 'Destination mobile opted out',
            'long' => 'The user has opted out and is no longer subscribed to your service.',
            'solution' => null
        ],
        '123' => [
            'short' => 'Invalid Sender ID',
            'long' => 'The sender ID is not valid or has not been approved.',
            'solution' => "A sender ID (https://archive.clickatell.com/developers/api-docs/sender-id-advanced-message-send/) needs to be registered and approved before it can be successfully used in message sending."
        ],
        '128' => [
            'short' => 'Number delisted',
            'long' => 'This number has been delisted and cannot receive our messages.',
            'solution' => null
        ],
        '130' => [
            'short' => 'Maximum MT limit exceeded until <UNIX TIMESTAMP>',
            'long' => 'This error is returned when an account has exceeded the maximum number of MT messages which can be sent daily or monthly. You can send messages again on the date indicated by the UNIX TIMESTAMP.',
            'solution' => null
        ],
        '160' => [
            'short' => 'HTTP method is not supported on this resource',
            'long' => 'An unsupported HTTP method has been performed on the resource. Example: HTTP POST on the Coverage resource.',
            'solution' => 'The response MUST include a Content-Type header that contains a valid method for the requested resource.'
        ],
        '161' => [
            'short' => 'Resource does not exist',
            'long' => 'You are attempting to access a REST API resource that does not exist.',
            'solution' => null
        ],
        '165' => [
            'short' => 'Invalid or no version header specified',
            'long' => 'The expected header that specifies version was either not found or is invalid.',
            'solution' => 'Before continuing make sure that the correct version is included in the submitted header. The header to use is X-Version: 1'
        ],
        '166' => [
            'short' => 'Invalid accept header specified',
            'long' => 'The optional header that specifies acceptable content does not contain an allowed value.',
            'solution' => null
        ],
        '167' => [
            'short' => 'Invalid or no content-type specified',
            'long' => 'The expected header that specifies content-type content was either not found or did not contain an allowed value.',
            'solution' => "Before continuing make sure that an allowed value is included in the submitted content-type header. The allowable content-type header values are 'application/json' or application/xml'."
        ],
        '301' => [
            'short' => 'No credit left',
            'long' => 'Insufficient credits',
            'solution' => "Login to your Developers' Central account and purchase additional credits"
        ],
        '901' => [
            'short' => 'Internal error - please retry',
            'long' => 'An error occurred on our platforms.',
            'solution' => 'Please retry submitting the message. This should be exceptionally rare.'
        ]
    ];

    /**
     * Handle CURL response from Clickatell APIs
     *
     * @param string $result   The API response
     * @param int    $httpCode The HTTP status code
     *
     * @throws Exception
     * @return array
     */
    public static function handle($result, $httpCode)
    {
        // Check for non-OK statuses
        $codes = explode(",", static::ACCEPTED_CODES);

        if (json_decode($result, true)) {
            $result = json_decode($result, true);
        } else {
            throw new ClickatellException("JSON Response Not Valid: " . $result);
        }

        if (!in_array($httpCode, $codes)) {
            // Decode JSON if possible, if this can't be decoded...something fatal went wrong
            // and we will just return the entire body as an exception.
            $error = $result['error'];

            throw new ClickatellException(var_export($error, true));
        } else {
            return $result;
        }
    }
}
