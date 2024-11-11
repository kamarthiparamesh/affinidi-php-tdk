# Affinidi TDK Playground

This is a sample playground app to test few Affinidi TDK methods

## Setup & Run Playground

1. Composer Install

```
composer install
```

2. Create `.env` file

```
cp .env.example .env
```

3. Generate Personal access token using command line tool more details [here](https://docs.affinidi.com/dev-tools/affinidi-cli/manage-token/#affinidi-token-create-token) and update .env file with details

4. Test the App, which get you list of login configurations for your project

```
php src/index.php
```

5. You can call any Affinidi API method by passing the data
