# Affinidi Trust Development Kit (Affinidi TDK)

The Affinidi Trust Development Kit (Affinidi TDK) is a modern interface that allows you to easily manage and integrate [Affinidi Elements](https://www.affinidi.com/product/affinidi-elements) and [Frameworks](https://www.affinidi.com/developer#lota-framework) into your application. It minimises dependencies and enables developers seamless entry into the [Affinidi Trust Network (ATN)](https://www.affinidi.com/get-started).

## How do I use Affinidi TDK?


- Generate Personal access token using command line tool more details [here](https://docs.affinidi.com/dev-tools/affinidi-cli/manage-token/#affinidi-token-create-token) and update .env file with details

```
VAULT_URL="https://vault.affinidi.com"
API_GATEWAY_URL="https://apse1.api.affinidi.io"
TOKEN_ENDPOINT="https://apse1.auth.developer.affinidi.io/auth/oauth2/token"
PROJECT_ID=""
KEY_ID=""
TOKEN_ID=""
PASSPHRASE="" # Optional. Required if private key is encrypted
PRIVATE_KEY=""
```

- Install the PHP TDK Package in your application using below command 
```
composer require affinidi/affinidi-php-tdk
```

- Initiate the Affinidi TDK with config

```
use Affinidi\AffinidiTDK;

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
```

- Invoke any GET/POST API, for instance

Get all Login configuration list

```
$listConfigs = $tdk->InvokeAPI('/vpa/v1/login/configurations', [], "GET");
echo "List of Configs" . print_r($listConfigs, true);
```

Issuance start API

```
$credentials_request =
    [
        [
            "credentialTypeId" => "SchemaOne",
            "credentialData" => [
                "first_name"=> "FirstName",
                "last_name"=> "LastName",
                "dob"=> "01.01.1970",
            ]
        ]
    ];

$data = $tdk->InvokeAPI('/cis/v1/' . $_ENV['PROJECT_ID'] . '/issuance/start', [
    'data' => $credentials_request,
    'claimMode' => "TX_CODE"
]);

echo "Issuance start response" . print_r($data, true);
```

Initiate Iota redirect data request

```
$data = $tdk->InvokeAPI('/ais/v1/initiate-data-sharing-request', [
    "configurationId" => "0d7acfbb-dcea-4b40-879c-f49fe918ac61", //Iota configuration Id
    "mode" => "redirect",
    "queryId" => "95e639d3-e851-4548-a7c2-be1c2e1b6da1", //Iota Query ID
    "correlationId" => "abc12334-dcea-4b40-879c-f49fe918ac61", // Your app unique id
    "nonce" => "test-nonce1", // nonce id
    "redirectUri" => 'http://localhost:8010/iota',
]);

echo "Issuance start response" . print_r($data, true);
```

## Support & feedback

If you face any issues or have suggestions, please don't hesitate to contact us using [this link](https://share.hsforms.com/1i-4HKZRXSsmENzXtPdIG4g8oa2v).

### Reporting technical issues

If you have a technical issue with the Affinidi TDK's codebase, you can also create an issue directly in GitHub.

1. Ensure the bug was not already reported by searching on GitHub under
   [Issues](https://github.com/affinidi/affinidi-php-tdk/issues).

2. If you're unable to find an open issue addressing the problem,
   [open a new one](https://github.com/affinidi/affinidi-php-tdk/issues/new).
   Be sure to include a **title and clear description**, as much relevant information as possible,
   and a **code sample** or an **executable test case** demonstrating the expected behaviour that is not occurring.

## Contributing

Want to contribute?

Head over to our [CONTRIBUTING](CONTRIBUTING.md) guidelines.

## FAQ

### What can I develop?

You are only limited by your imagination! Affinidi TDK is a toolbox with which you can build software applications for personal or commercial use.

### Is there anything I should not develop?

We only provide the tools - how you use them is largely up to you. We have no control over what you develop with our tools - but please use our tools responsibly!

We hope that you will not develop anything that contravenes any applicable laws or regulations. Your projects should also not infringe on Affinidi's or any third party's intellectual property (for instance, misusing other parties' data, code, logos, etc).

### What responsibilities do I have to my end-users?

Please ensure that you have in place your terms and conditions, privacy policies, and other safeguards to ensure that the projects you build are secure for your end users.

If you are processing personal data, please protect the privacy and other legal rights of your end-users and store their personal or sensitive information securely.

Some of our components would also require you to incorporate our end-user notices into your terms and conditions.

### Is Affinidi TDK free for use?

Affinidi TDK itself is free, so come onboard and experiment with our tools and see what you can build! We may bill for certain components in the future, but we will inform you beforehand.

### Is there any limit or cap to my usage of the Affinidi TDK?

We may from time to time impose limits on your use of the Affinidi TDK, such as limiting the number of API requests that you may make in a given duration. This is to ensure the smooth operation of the Affinidi TDK so that you and all our other users can have a pleasant experience as we continue to scale and improve the Affinidi TDK.

### Do I need to provide you with anything?

From time to time, we may request certain information from you to ensure that you are complying with the [Terms and Conditions](https://www.affinidi.com/terms-conditions).

### Can I share my developer's account with others?

When you create a developer's account with us, we will issue you your private login credentials. Please do not share this with anyone else, as you would be responsible for activities that happen under your account. If you have interested friends, ask them to sign up – let's build together!

### Telemetry

Affinidi collects usage data to improve our products and services. For information on what data we collect and how we use your data, please refer to our [Privacy Notice](https://www.affinidi.com/privacy-notice).

_Disclaimer:
Please note that this FAQ is provided for informational purposes only and is not to be considered a legal document. For the legal terms and conditions governing your use of the Affinidi Services, please refer to our [Terms and Conditions](https://www.affinidi.com/terms-conditions)._
