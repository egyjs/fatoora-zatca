<?php

namespace Egyjs\FatooraZatca\Actions;

use Egyjs\FatooraZatca\Helpers\ConfigHelper;
use Exception;
use Google\Cloud\Core\Exception\BadRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class PostRequestAction
{
    /**
     * handle sending the request to zatca portal.
     *
     * @param  string   $route
     * @param  array    $data
     * @param  array    $headers
     * @param  string   $USERPWD
     * @return array
     */
    public function handle(string $route, array $data, array $headers, string $USERPWD): array
    {
        $portal = ConfigHelper::portal();


        $client = new Client();
        try {
            $response = $client->request('POST', $portal . $route, [
                'headers' => $headers,
                'auth' => [$USERPWD, ''],
                'json' => $data,
                'verify' => false,
            ]);
        }
        catch (ClientException $exception) {
            $response = $exception->getResponse();
            $body = $response->getBody()->getContents();
            $url = $exception->getRequest()->getUri();

            // cURL equivalent
            $curlCommand = "curl -X POST -H 'Content-Type: application/json'";

            foreach ($headers as $key=>  $value) {
                $curlCommand .= " -H '$key:$value'";
            }

            $curlCommand .= " -d '".json_encode($data)."' --insecure '$url'";

            dd($curlCommand,$body);
        }

        $httpcode = $response->getStatusCode();

        $response = json_decode($response->getBody(), true);
        return (new HandleResponseAction)->handle($httpcode, $response);
    }
}
