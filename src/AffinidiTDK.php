<?php

namespace Affinidi;

use GuzzleHttp\Client;

class AffinidiTDK
{
    private $tdkConfig = null;

    public function __construct(array $config)
    {
        $requiredKeys = ['token_endpoint', 'project_Id', 'api_gateway_url', 'private_key', 'token_id'];

        $this->checkConfig($config, $requiredKeys);

        $this->tdkConfig = $config;
    }
    public function InvokeAPI($apiMethod, $data, $type = "POST", $auth = true)
    {
        //Preparing full API Url 
        $apiUrl = $this->tdkConfig['api_gateway_url'] . $apiMethod;
        Log::info('Invoking Api Url: ' . $apiUrl);

        $headers = [
            'Content-Type' => 'application/json'
        ];

        if ($auth == true) {
            //Getting Project scope token
            $pst = FetchProjectScopeToken::fetchProjectScopedToken($this->tdkConfig);
            $headers['Authorization'] = 'Bearer ' . $pst;
        }

        $client = new Client();
        //Calling API by passing PST with data
        if ($type == "POST") {
            $response = $client->post($apiUrl, [
                'json' => $data,
                'headers' => $headers,
            ]);
        } else {
            $response = $client->get($apiUrl, [
                'query' => $data,
                'headers' => $headers,
            ]);
        }

        $responseBody = $response->getBody()->getContents();
        $responseJson = json_decode($responseBody, true);
        Log::info('Response: ', $responseJson);
        return $responseJson;
    }

    private function checkConfig(array $config, array $requiredKeys)
    {
        foreach ($requiredKeys as $key) {
            if (empty($config[$key])) {
                throw new \Error("Config $key is missing");
            }
        }
    }
}
