@echo off

composer require brick/math:0.12.3
composer require carbonphp/carbon-doctrine-types:3.2.0
composer require dflydev/dot-access-data:3.0.3
composer require doctrine/dbal:4.2.3
composer require doctrine/deprecations:1.1.5
composer require doctrine/inflector:2.0.10
composer require doctrine/lexer:3.0.1
composer require dragonmantank/cron-expression:3.4.0
composer require egulias/email-validator:4.0.4
composer require fruitcake/php-cors:1.3.0
composer require graham-campbell/result-type:1.1.3
composer require guzzlehttp/guzzle:7.9.3
composer require guzzlehttp/promises:2.2.0
composer require guzzlehttp/psr7:2.7.1
composer require guzzlehttp/uri-template:1.0.4
composer require laravel/framework:12.15.0
composer require laravel/prompts:0.3.5
composer require laravel/sanctum:4.1.1
composer require laravel/serializable-closure:2.0.4
composer require laravel/tinker:2.10.1
composer require league/commonmark:2.7.0
composer require league/config:1.2.0
composer require league/flysystem:3.29.1
composer require league/flysystem-local:3.29.0
composer require league/mime-type-detection:1.16.0
composer require league/uri:7.5.1
composer require league/uri-interfaces:7.5.0
composer require monolog/monolog:3.9.0
composer require nesbot/carbon:3.9.1
composer require nette/schema:1.3.2
composer require nette/utils:4.0.6
composer require nikic/php-parser:5.4.0
composer require nunomaduro/termwind:2.3.1
composer require phpoption/phpoption:1.9.3
composer require psr/cache:3.0.0
composer require psr/clock:1.0.0
composer require psr/container:2.0.2
composer require psr/event-dispatcher:1.0.0
composer require psr/http-client:1.0.3
composer require psr/http-factory:1.1.0
composer require psr/http-message:2.0
composer require psr/log:3.0.2
composer require psr/simple-cache:3.0.0
composer require psy/psysh:0.12.8
composer require ralouphie/getallheaders:3.0.3
composer require ramsey/collection:2.1.1
composer require ramsey/uuid:4.7.6
composer require symfony/clock:7.2.0
composer require symfony/console:7.2.6
composer require symfony/css-selector:7.2.0
composer require symfony/deprecation-contracts:3.5.1
composer require symfony/error-handler:7.2.5
composer require symfony/event-dispatcher:7.2.0
composer require symfony/event-dispatcher-contracts:3.5.1
composer require symfony/finder:7.2.2
composer require symfony/http-foundation:7.2.6
composer require symfony/http-kernel:7.2.6
composer require symfony/mailer:7.2.6
composer require symfony/mime:7.2.6
composer require symfony/polyfill-ctype:1.32.0
composer require symfony/polyfill-intl-grapheme:1.32.0
composer require symfony/polyfill-intl-idn:1.32.0
composer require symfony/polyfill-intl-normalizer:1.32.0
composer require symfony/polyfill-mbstring:1.32.0
composer require symfony/polyfill-php80:1.32.0
composer require symfony/polyfill-php83:1.32.0
composer require symfony/polyfill-uuid:1.32.0
composer require symfony/process:7.2.5
composer require symfony/routing:7.2.3
composer require symfony/service-contracts:3.5.1
composer require symfony/string:7.2.6
composer require symfony/translation:7.2.6
composer require symfony/translation-contracts:3.5.1
composer require symfony/uid:7.2.0
composer require symfony/var-dumper:7.2.6
composer require tijsverkoyen/css-to-inline-styles:2.3.0
composer require vlucas/phpdotenv:5.6.2
composer require voku/portable-ascii:2.0.3
composer require webmozart/assert:1.11.0
composer require brianium/paratest:7.8.3
composer require fakerphp/faker:1.24.1
composer require fidry/cpu-core-counter:1.2.0
composer require filp/whoops:2.18.0
composer require hamcrest/hamcrest-php:2.1.1
composer require jean85/pretty-package-versions:2.1.1
composer require laravel/breeze:2.3.6
composer require laravel/pail:1.2.2
composer require laravel/pint:1.22.1
composer require laravel/sail:1.43.0
composer require mockery/mockery:1.6.12
composer require myclabs/deep-copy:1.13.1
composer require nunomaduro/collision:8.8.0
composer require pestphp/pest:3.8.2
composer require pestphp/pest-plugin:3.0.0
composer require pestphp/pest-plugin-arch:3.1.1
composer require pestphp/pest-plugin-laravel:3.2.0
composer require pestphp/pest-plugin-mutate:3.0.5
composer require phar-io/manifest:2.0.4
composer require phar-io/version:3.2.1
composer require phpdocumentor/reflection-common:2.2.0
composer require phpdocumentor/reflection-docblock:5.6.2
composer require phpdocumentor/type-resolver:1.10.0
composer require phpstan/phpdoc-parser:2.1.0
composer require phpunit/php-code-coverage:11.0.9
composer require phpunit/php-file-iterator:5.1.0
composer require phpunit/php-invoker:5.0.1
composer require phpunit/php-text-template:4.0.1
composer require phpunit/php-timer:7.0.1
composer require phpunit/phpunit:11.5.15
composer require sebastian/cli-parser:3.0.2
composer require sebastian/code-unit:3.0.3
composer require sebastian/code-unit-reverse-lookup:4.0.1
composer require sebastian/comparator:6.3.1
composer require sebastian/complexity:4.0.1
composer require sebastian/diff:6.0.2
composer require sebastian/environment:7.2.1
composer require sebastian/exporter:6.3.0
composer require sebastian/global-state:7.0.2
composer require sebastian/lines-of-code:3.0.1
composer require sebastian/object-enumerator:6.0.1
composer require sebastian/object-reflector:4.0.1
composer require sebastian/recursion-context:6.0.2
composer require sebastian/type:5.1.2
composer require sebastian/version:5.0.2
composer require staabm/side-effects-detector:1.0.5
composer require symfony/yaml:7.2.6
composer require ta-tikoma/phpunit-architecture-test:0.8.5
composer require theseer/tokenizer:1.2.3

:: Instalacja rozszerzeń VS Code
call code --install-extension ritwickdey.liveserver --force
call code --install-extension zignd.html-css-class-completion --force
call code --install-extension onecentlin.laravel-extension-pack --force
call code --install-extension xdebug.php-debug --force
call code --install-extension bmewburn.vscode-intelephense-client --force
call code --install-extension humao.rest-client --force
call code --install-extension pkief.material-icon-theme --force
call code --install-extension damms005.devdb --force

pause

:: Laravel commands
php artisan breeze:install blade yes Pest

npm install && npm run dev

php artisan migrate

pause
