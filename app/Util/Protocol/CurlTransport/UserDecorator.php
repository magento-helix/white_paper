<?php
/**
 * Created by PhpStorm.
 * User: vgoncharenko
 * Date: 4/4/18
 * Time: 4:10 PM
 */
namespace App\Util\Protocol\CurlTransport;

use App\Util\Protocol\CurlInterface;
use App\Util\Protocol\CurlTransport;

/**
 * Curl transport on Grafana.
 */
class UserDecorator implements CurlInterface
{
    /**
     * Curl transport protocol.
     *
     * @var CurlTransport
     */
    private $transport;

    /**
     * Response data.
     *
     * @var string
     */
    private $response;

    /**
     * System config.
     *
     * @var array
     */
    private $configuration;

    /**
     * @var array
     */
    private $credentials;

    /**
     * @constructor
     */
    public function __construct()
    {
        $this->transport = new CurlTransport();
        $this->credentials = json_decode(file_get_contents(__DIR__ . '/../../../temp/credentials.json'), true);
        $this->configuration = new \App\Config(BP . '/resources/config.json');
        $this->authorize();
    }

    /**
     * Authorize user on api.
     *
     * @throws \Exception
     * @return void
     */
    private function authorize()
    {
        $url = $this->configuration->getData()['grafana']['base_url'] . 'login';
        $data = [
            'email' => "",
            'user' => $this->credentials['user'],
            'password' => $this->credentials['pass'],
        ];
        $this->write($url, $data, CurlInterface::POST);
        $response = $this->read();

        if (!strpos($response, 'Logged in')) {
            throw new \Exception(
                'User cannot be logged in by curl handler!'
            );
        }
    }

    /**
     * Send request to the remote server.
     *
     * @param string $url
     * @param mixed $params
     * @param string $method
     * @param mixed $headers
     * @return void
     * @throws \Exception
     */
    public function write($url, $params = [], $method = CurlInterface::POST, $headers = [])
    {
        $headers[] = 'Content-Type: application/json';
        $this->transport->write($url, json_encode($params), $method, $headers, 0);
    }

    public function read()
    {
        $this->response = $this->transport->read();
        if (strpos($this->response, '"status":"error"')) {
            throw new \Exception($this->response);
        }
        return $this->response;
    }

    /**
     * Add additional option to cURL.
     *
     * @param int $option the CURLOPT_* constants
     * @param mixed $value
     * @return void
     */
    public function addOption($option, $value)
    {
        $this->transport->addOption($option, $value);
    }

    /**
     * Close the connection to the server.
     *
     * @return void
     */
    public function close()
    {
        $this->transport->close();
    }
}
