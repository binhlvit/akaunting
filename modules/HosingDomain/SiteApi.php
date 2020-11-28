<?php

namespace Modules\HosingDomain;

use App\Utilities\Info;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class SiteApi
{
    protected $base_uri = '';
    protected $username = '';
    protected $apikey = '';
    public function __construct(){
        $this->base_uri = env('HOSTING_DOMAIN_API_URL');
        $this->username = env('HOSTING_DOMAIN_USERNAME');
        $this->apikey = env('HOSTING_DOMAIN_API_KEY');
    }

    public function siteApiRequest($method, $path, $extra_data = [])
    {
        $client = new Client(['verify' => false, 'base_uri' => $this->base_uri]);

//        $headers['headers'] = [
//            'Authorization' => 'Bearer ' . setting('apps.api_key'),
//            'Accept'        => 'application/json',
//            'Referer'       => app()->runningInConsole() ? config('app.url') : route('dashboard'),
//            'Language'      => language()->getShortCode(),
//            'Information'   => json_encode(Info::all()),
//        ];

        $headers = [];

        $data = array_merge([
            'timeout' => 30,
            'referer' => true,
            'http_errors' => false,
        ], $extra_data);

        $options = array_merge($data, $headers);

        try {
            $response = $client->request($method, $path, $options);
        } catch (ConnectException | Exception | RequestException $e) {
            $response = $e;
        }

        return $response;
    }

    public function getResponse($method, $path, $data = [], $status_code = 200)
    {
        $response = $this->siteApiRequest($method, $path, $data);

        $is_exception = (($response instanceof ConnectException) || ($response instanceof Exception) || ($response instanceof RequestException));

        if (!$response || $is_exception || ($response->getStatusCode() != $status_code)) {
            return false;
        }

        return $response;
    }

    public function getResponseData($method, $path, $data = [], $status_code = 200)
    {
        if (!$response = $this->getResponse($method, $path, $data, $status_code)) {
            return [];
        }

        return $response->getBody()->getContents();
    }

    public function isExistDomain($domain){
        $data['query'] = [
            'cmd' => 'check_whois',
            'username' => $this->username,
            'apikey' => $this->apikey,
            'domain' => $domain
        ];
        return $this->getResponseData('GET', 'interface_test.php', $data);
    }
}
