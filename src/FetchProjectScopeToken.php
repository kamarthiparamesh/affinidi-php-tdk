<?php

namespace Affinidi;

use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use GuzzleHttp\Client;

class FetchProjectScopeToken
{
    public static function fetchProjectScopedToken($tdkConfig): string
    {
        //Check PST is available in file
        $tokenFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'pst_token.txt';
        //Log::info('PST Path ' . $tokenFilePath);

        if (file_exists($tokenFilePath)) {
            $tokenData = file_get_contents($tokenFilePath);
            if (!self::isTokenExpired($tokenData)) {
                Log::info('Project Scope Token already exists and its valid ' . $tokenData);
                return $tokenData;
            }
        }
        
        //Token not exists or expired, so generating new PST 
        Log::info('Generating PST');

        $userToken = self::getUserAccessToken($tdkConfig);

        Log::info('User Access Token: ' . $userToken);

        $api_gateway_url = $tdkConfig['api_gateway_url'];
        $project_Id = $tdkConfig['project_Id'];

        $projectTokenEndpoint = $api_gateway_url . '/iam/v1/sts/create-project-scoped-token';

        $client = new Client();

        $response = $client->post($projectTokenEndpoint, [
            'json' => [
                'projectId' => $project_Id
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $userToken,
                'Content-Type' => 'application/json',
            ],
            'verify' => false,  // Disable SSL verification
        ]);

        $responseBody = $response->getBody()->getContents();
        $responseData = json_decode($responseBody, true);
        Log::info('Response: ', $responseData);

        if (!isset($responseData['accessToken'])) {
            Log::error('Access token not found in response: ', $responseData);
            $error = new \Error('Access token not found while generating project scope token');
            throw $error;
        }

        Log::info('Access token found in response: ' . $responseData['accessToken']);
        $pst = $responseData['accessToken'];

        file_put_contents($tokenFilePath, $pst);
        return $pst;

    }
    private static function isTokenExpired($token): bool
    {
        list($header, $payload, $signature) = explode('.', $token);

        $payload = json_decode(base64_decode($payload), true);
        if (isset($payload['exp'])) {
            $currentTimestamp = time();
            return $currentTimestamp >= $payload['exp'];
        }

        // If no exp claim, assume expired
        return true;
    }
    private static function getUserAccessToken($tdkConfig)
    {
        $token_endpoint = $tdkConfig['token_endpoint'];
        $private_key = $tdkConfig['private_key'];
        $token_id = $tdkConfig['token_id'];
        $key_id = isset($tdkConfig['key_id']) ? $tdkConfig['key_id'] : $tdkConfig['token_id'];
        $passphrase = isset($tdkConfig['passphrase']) ? $tdkConfig['passphrase'] : null;

        $algorithm = 'RS256';
        $issueTimeS = floor(time());
        $jti = Uuid::uuid4()->toString();
        $payload = [
            'iss' => $token_id,
            'sub' => $token_id,
            'aud' => $token_endpoint,
            'jti' => $jti,
            'iat' => $issueTimeS,
            'exp' => $issueTimeS + 5 * 60
        ];

        $headers = [
            'kid' => $key_id,
        ];
        Log::info('Payload: ' . json_encode($payload));

        $key = openssl_pkey_get_private($private_key, $passphrase);

        $token = JWT::encode($payload, $key, $algorithm, $key_id, $headers);

        Log::info('Token: ' . $token);

        $client = new Client();
        $response = $client->post($token_endpoint, [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
                'scope' => 'openid',
                'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
                'client_assertion' => $token,
                'client_id' => $token_id
            ],
        ]);
        $responseBody = $response->getBody()->getContents();
        $responseData = json_decode($responseBody, true);
        Log::info('Response: ', $responseData);

        if (isset($responseData['access_token'])) {
            return $responseData['access_token'];
        } else {
            Log::error('Access token not found in response: ', $responseData);
            $error = new \Error('Access token not found while generating user access token');
            throw $error;
        }

    }

}
