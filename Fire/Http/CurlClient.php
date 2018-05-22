<?php

/**
 * Twilio API helper library.
 *
 * @category  Services
 * @package   Services_Twilio
 * @author    Neuman Vong <neuman@twilio.com>
 * @license   http://creativecommons.org/licenses/MIT/ MIT
 * @link      http://pear.php.net/package/Services_Twilio
 */

namespace Fire\Http;

use Fire\Business\Exceptions\EnvironmentException;

class CurlClient implements Client {
    const DEFAULT_TIMEOUT = 60;
    protected $curlOptions = array();
    protected $debugHttp;

    public function __construct(array $options = array()) {
        $this->curlOptions = $options;
	$this->debugHttp = getenv('DEBUG_HTTP_TRAFFIC') === 'true';
    }

    public function request($method, $url, $params = array(), $data = array(),
                            $headers = array(), $authorizationToken = null,
                            $timeout = null) {
        $options = $this->options($method, $url, $params, $data, $headers,
                                  $authorizationToken, $timeout);

        try {
            if (!$curl = curl_init()) {
                throw new EnvironmentException('Unable to initialize cURL');
            }

            if (!curl_setopt_array($curl, $options)) {
                throw new EnvironmentException(curl_error($curl));
            }

            if (!$response = curl_exec($curl)) {
                throw new EnvironmentException(curl_error($curl));
            }

            $parts = explode("\r\n\r\n", $response, 3);
            list($head, $body) = ($parts[0] == 'HTTP/1.1 100 Continue')
                               ? array($parts[1], $parts[2])
                               : array($parts[0], $parts[1]);

            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            $responseHeaders = array();
            $headerLines = explode("\r\n", $head);
            array_shift($headerLines);
            foreach ($headerLines as $line) {
                list($key, $value) = explode(':', $line, 2);
                $responseHeaders[$key] = $value;
            }

            curl_close($curl);

            if (isset($buffer) && is_resource($buffer)) {
                fclose($buffer);
            }

            return new Response($statusCode, $body, $responseHeaders);
        } catch (\ErrorException $e) {
            if (isset($curl) && is_resource($curl)) {
                curl_close($curl);
            }

            if (isset($buffer) && is_resource($buffer)) {
                fclose($buffer);
            }

            throw $e;
        }
    }

    public function options($method, $url, $params = array(), $data = array(),
                            $headers = array(), $authorizationToken = null,
                            $timeout = null) {

        $timeout = is_null($timeout)
            ? self::DEFAULT_TIMEOUT
            : $timeout;

        $options = $this->curlOptions + array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_INFILESIZE => -1,
            CURLOPT_HTTPHEADER => array(),
            CURLOPT_TIMEOUT => $timeout,
	    CURLOPT_VERBOSE => $this->debugHttp,
        );

        foreach ($headers as $key => $value) {
            $options[CURLOPT_HTTPHEADER][] = "$key: $value";
        }
        $options[CURLOPT_HTTPHEADER][] = "Content-Type: application/json";

        if ($authorizationToken) {
            $options[CURLOPT_HTTPHEADER][] = "Authorization: $authorizationToken";
        }

        $body = $this->buildQuery($params);
        if ($body) {
            $options[CURLOPT_URL] .= '?' . $body;
        }

        switch (strtolower(trim($method))) {
            case 'get':
                $options[CURLOPT_HTTPGET] = true;
                break;
            case 'post':
                $options[CURLOPT_POST] = true;
                #$options[CURLOPT_POSTFIELDS] = $this->buildQuery($data);
                $options[CURLOPT_POSTFIELDS] = json_encode($data);


                break;
            case 'put':
                $options[CURLOPT_PUT] = true;
                if ($data) {
                    if ($buffer = fopen('php://memory', 'w+')) {
                        $dataString = $this->buildQuery($data);
                        fwrite($buffer, $dataString);
                        fseek($buffer, 0);
                        $options[CURLOPT_INFILE] = $buffer;
                        $options[CURLOPT_INFILESIZE] = strlen($dataString);
                    } else {
                        throw new EnvironmentException('Unable to open a temporary file');
                    }
                }
                break;
            case 'head':
                $options[CURLOPT_NOBODY] = true;
                break;
            default:
                $options[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
        }

        return $options;
    }

    public function buildQuery($params) {
        $parts = array();

        $params = $params ?: array();

        foreach ($params as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $parts[] = urlencode((string)$key) . '=' . urlencode((string)$item);
                }
            } else {
                $parts[] = urlencode((string)$key) . '=' . urlencode((string)$value);
            }
        }

        return implode('&', $parts);
    }
}
