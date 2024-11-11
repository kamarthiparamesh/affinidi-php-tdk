<?php

require_once 'vendor/autoload.php';

use Affinidi\AffinidiTDK;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$tdk = new AffinidiTDK([
    'api_gateway_url' => $_ENV['API_GATEWAY_URL'],
    'token_endpoint' => $_ENV['TOKEN_ENDPOINT'],
    'project_Id' => $_ENV['PROJECT_ID'],
    'private_key' => $_ENV['PRIVATE_KEY'],
    'token_id' => $_ENV['TOKEN_ID'],
    'passphrase' => $_ENV['PASSPHRASE'],
    'key_id' => $_ENV['KEY_ID'],
    'vault_url' => $_ENV['VAULT_URL'],
]);


// // Get Login Configurations List
$listConfigs = $tdk->InvokeAPI('/vpa/v1/login/configurations', [], "GET");
echo "List of Configs" . print_r($listConfigs, true);
exit();


// // Issuance start API
// $credentials_request =
//     [
//         [
//             "credentialTypeId" => "AnyTcourseCertificateV1R0",
//             "credentialData" => [
//                 "courseID" => "EMP-IT-AUTOMATION-2939302",
//                 "course" => [
//                     "name" => "IT Automation with Python",
//                     "type" => "Professional Certificate",
//                     "url" => "",
//                     "courseDuration" => "45 Days"
//                 ],
//                 "learner" => [
//                     "name" => "",
//                     "email" => "grajesh.c@affinidi.com",
//                     "phone" => ""
//                 ],
//                 "achievement" => [
//                     "score" => "100",
//                     "grade" => "A"
//                 ],
//                 "courseMode" => "online",
//                 "completionDate" => "08/09/2024"
//             ]
//         ]
//     ];

// $data = $tdk->InvokeAPI('/cis/v1/' . $_ENV['PROJECT_ID'] . '/issuance/start', [
//     'data' => $credentials_request,
//     'claimMode' => "TX_CODE"
// ]);

// echo "Issuance start response" . print_r($data, true);
// exit();


//Initiate Iota redirect data request
// $data = $tdk->InvokeAPI('/ais/v1/initiate-data-sharing-request', [
//     "configurationId" => "0d7acfbb-dcea-4b40-879c-f49fe918ac61", //Iota configuration Id
//     "mode" => "redirect",
//     "queryId" => "95e639d3-e851-4548-a7c2-be1c2e1b6da1", //Iota Query ID
//     "correlationId" => "abc12334-dcea-4b40-879c-f49fe918ac61", // Your app unique id
//     "nonce" => "test-nonce1", // nonce id
//     "redirectUri" => 'http://localhost:8010/iota',
// ]);

// echo "Issuance start response" . print_r($data, true);
// exit();