# Worldwide Recipes API backend demo for IPC Berlin

- This is an API backend using [Laravel](https://laravel.com/docs/11.x/).
- From laravel it uses:
    - [Sail](https://laravel.com/docs/11.x/sail#main-content)
    - [Sanctum](https://laravel.com/docs/11.x/sanctum#main-content)
    - [Scout](https://laravel.com/docs/11.x/scout#main-content)
    - [Tinker](https://laravel.com/docs/11.x/artisan#tinker)

## Requirements

- Install [Docker desktop](https://www.docker.com/products/docker-desktop/)
- Install [Postman](https://www.postman.com/downloads/)
- IDEs
    - Install [cursor](https://www.cursor.com/)
        - Create an account with cursor
        - Suggested extensions:
            - [ESLint](https://marketplace.visualstudio.com/items?itemName=dbaeumer.vscode-eslint)
            - [Prettier](https://marketplace.visualstudio.com/items?itemName=esbenp.prettier-vscode)
            - [Tailwind CSS IntelliSense](https://marketplace.visualstudio.com/items?itemName=bradlc.vscode-tailwindcss)
            - [SonarQube](https://marketplace.visualstudio.com/items?itemName=SonarSource.sonarlint-vscode)
    - Install [PHPStorm](https://www.jetbrains.com/phpstorm/download/)
        - Suggested plugins:
            - [GitHub copilot](https://plugins.jetbrains.com/plugin/17718-github-copilot)
            - [.env files](https://plugins.jetbrains.com/plugin/9525--env-files)
            - [SonarQube](https://plugins.jetbrains.com/plugin/7973-sonarqube-for-ide)
    - Alternatively, install [Visual Studio Code](https://code.visualstudio.com/Download)
        - Suggested extensions:
            - [GitHub Copilot](https://marketplace.visualstudio.com/items/?itemName=GitHub.copilot)
            - [GitHub Copilot Chat](https://marketplace.visualstudio.com/items/?itemName=GitHub.copilot-chat)
            - [Laravel](https://marketplace.visualstudio.com/items/?itemName=laravel.vscode-laravel)
- Have a terminal
- Clone repositories
    - [API backend](https://github.com/cvarela0702/ipc-laravel-backend)
    - [Customer frontend](https://github.com/cvarela0702/ipc-nextjs-frontend)
- Have git installed locally
- Have a GitHub account
    - with GitHub copilot enabled
- Have an [OpenAI key](https://auth.openai.com/log-in)
- [Node 18.18](https://nodejs.org/en) or later
- DB access
    - Credentials:
        - user: laravel
        - password: password
    - with client
        - Mac / Linux
            - [MyCli](https://www.mycli.net/)
            - [Sequel Pro](https://sequelpro.com/)
        - Windows / Mac / Linux
            - [MySQL Workbench](https://dev.mysql.com/downloads/workbench/)
            - [phpMyAdmin](https://www.phpmyadmin.net/)
            - [DBeaver](https://dbeaver.io/download/)
    - with IDE configuration
        - with [PHPStorm](https://www.jetbrains.com/phpstorm/download/)

## Instructions

- Clone repo

```bash
git clone https://github.com/cvarela0702/ipc-laravel-backend
```

- Prepare docker in backend

```bash
cd ipc-laravel-backend
```

- Install laravel sail

```bash
docker run --rm \
    --pull=always \
    -v "$(pwd)":/opt \
    -w /opt \
    laravelsail/php84-composer:latest \
    bash -c "composer install --ignore-platform-reqs && composer run post-root-package-install && php ./artisan key:generate --ansi && php ./artisan sail:install --with=mysql,redis,meilisearch,mailpit,selenium "
```

- For the IPC only (it brings the application to the first commit)

```bash
git checkout breeze
```

- Build the docker images

```bash
./vendor/bin/sail build --no-cache
```

- Configure cors

```php
// config/cors.php
//...
'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:3000')],
//...
```

- Configure .env file

```.env
APP_URL=http://localhost:80
FRONTEND_URL=http://localhost:3000
```

- Start the application (this is a watch like mode, leave this running and continue in another tab from your terminal for the rest of the instructions)

```bash
./vendor/bin/sail up
```

- Migrate the DB

```bash
./vendor/bin/sail artisan migrate
```

- checking versions

```bash
./vendor/bin/sail php --version
./vendor/bin/sail artisan --version
./vendor/bin/sail composer --version
./vendor/bin/sail npm --version
```

- If you go to the browser to `http://localhost/` you should see something similar to:

```json
{"Laravel":"12.1.1"}
```

- Follow the instructions from [ipc-nextjs-frontend](https://github.com/cvarela0702/ipc-nextjs-frontend)

## Important commands

- Laravel migrate

```bash
./vendor/bin/sail artisan migrate
```

- Laravel migrate fresh

```bash
./vendor/bin/sail artisan migrate:fresh
```

- Laravel scout sync

```bash
./vendor/bin/sail artisan scout:sync-index-settings
```

- Laravel seed DB

```bash
./vendor/bin/sail artisan db:seed --class=SampleDataSeeder
```

- Laravel scout import models

```bash
./vendor/bin/sail artisan scout:import "App\Models\Recipe"
```

- Flush to clean if needed

```bash
./vendor/bin/sail artisan scout:flush "App\Models\Recipe"
```


- Laravel tests

```bash
./vendor/bin/sail artisan test
```

- DB access

```bash
mycli -usail laravel
```