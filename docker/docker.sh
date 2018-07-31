#!/usr/bin/env bash

echo_and_run() { echo "\$ $@" ; "$@" ; }

RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo_blue() { printf "\n${BLUE} $@ ${NC}\n"; }

if [ "$1" = "init" ]
then
    echo_blue "init"
    # for linux
    #pip install --user awscli
    #add to .bashrc or .zshrc etc.
    #export PATH=$PATH:$HOME/.local/bin

    # or for mac
    brew install awscli
fi

if [ "$1" = "build" ]
then
    echo_blue "build"
    docker-compose -f docker/docker-compose.travis.yml build && \
    docker-compose -f docker/docker-compose.travis.yml run travis_php ./docker/docker.sh private:build
fi

if [ "$1" = "build:development" ]
then
    echo_blue "build"
fi

if [ "$1" = "private:build" ]
then
    echo_blue "private:build"
    pwd && \
    ./docker/wait-for-it.sh -h percona -p 3306 -t 30 -- pwd
    mysql -uroot -hpercona -P3306 -pk9XjPjCaYmda -e "SHOW VARIABLES LIKE '%version%';"
    mysql -uroot -hpercona -P3306 -pk9XjPjCaYmda -e "CREATE DATABASE citicash"
    mysql -uroot -hpercona -P3306 -pk9XjPjCaYmda -e "CREATE DATABASE citicash_test"
fi



if [ "$1" = "run:app" ]
then
    echo_blue "run"
    docker-compose -f docker/docker-compose.development.yml up -d
fi



if [ "$1" = "bash" ]
then
    echo_blue "bash"
    docker-compose -f docker/docker-compose.travis.yml run travis_php bash
fi



if [ "$1" = "travis:before_install" ]
then
    out=0
    docker-compose -f docker/docker-compose.travis.yml run travis_php composer install --no-interaction --prefer-source || { out=1; }
    docker-compose -f docker/docker-compose.travis.yml run travis_php composer config minimum-stability dev || { out=1; }
    docker-compose -f docker/docker-compose.travis.yml run travis_php composer require "roave/security-advisories:dev-master" || { out=1; }
    docker-compose -f docker/docker-compose.travis.yml run travis_php composer dump-autoloader --classmap-authoritative || { out=1; }
    exit $out;
fi



if [ "$1" = "travis:before_script" ]
then
    out=0
    docker-compose -f docker/docker-compose.travis.yml run travis_php ./docker/docker.sh private:travis:before_script || { out=1; }
    exit $out
fi

if [ "$1" = "private:travis:before_script" ]
then
    if [ ! -d "vendor/php-parallel-lint" ]; then git clone https://github.com/mzk/PHP-Parallel-Lint.git vendor/php-parallel-lint && composer install  --no-interaction --prefer-dist --no-dev -d vendor/php-parallel-lint ; fi
    #git -C vendor/php-parallel-lint reset --hard dc6dc7246dceb44dcc316cfd97bfebeb53c613da

    if [ ! -d "vendor/phpcs" ]; then git clone https://github.com/slevomat/coding-standard.git vendor/phpcs; fi
    git -C vendor/phpcs  reset --hard 76e31b7cb2ce1de53b36430a332daae2db0be549 && composer install  --no-interaction --prefer-dist --no-dev -d vendor/phpcs ;

    if [ ! -d "vendor/code-checker" ]; then composer create-project nette/code-checker -d vendor; fi

    if [ ! -d "vendor/phpstan" ]; then git clone https://github.com/phpstan/phpstan.git vendor/phpstan; fi
    git -C vendor/phpstan reset --hard 3179cf27542e9e47acb548150e7ca21ca5ab92d6 && composer install  --no-interaction --prefer-dist --no-dev -d vendor/phpstan ;
    composer require phpstan/phpstan-strict-rules -d vendor/phpstan ;
    composer require thecodingmachine/phpstan-strict-rules -d vendor/phpstan ;
fi


if [ "$1" = "test:coding-style" ]
then
    echo_blue "test-coding-style"
    out=0
    docker-compose -f docker/docker-compose.travis.yml run travis_php ./docker/docker.sh private:test-coding-style || { out=1; }
    exit $out
fi



if [ "$1" = "private:test-coding-style" ]
then
    out=0
    #echo_blue "development=true php app/console orm:validate"
    #development=true php app/console orm:validate || { out=1; }
    echo_blue "private:test-coding-style"
    #php app/console cache:warmup --env=dev || { out=1; }
    echo_blue "php vendor/php-parallel-lint/parallel-lint.php -e php,phpt,phtml --exclude vendor --show-deprecated ."
    php vendor/php-parallel-lint/parallel-lint.php -e php,phpt,phtml -j 5 --exclude vendor --show-deprecated . || { out=1; }
    echo_blue "phpcs app"
    vendor/phpcs/bin/phpcs --standard=ruleset.xml --extensions=php,phpt --warning-severity=0 --encoding=utf-8 --report-width=auto -sp app tests || { out=1; }
    echo_blue "nette code checker"
    php vendor/code-checker/code-checker -l -f --short-arrays --strict-types -d app || { out=1; }
    php vendor/code-checker/code-checker -l -f --short-arrays --strict-types -d tests || { out=1; }
    echo_blue "phpstan"
    rm -rf vendor/php-code-checker/vendor/phpstan/tmp/cache
    vendor/phpstan/bin/phpstan analyse -l 7 -c phpstan.neon app tests || { out=1; }
    ./vendor/bin/tester -C -j 1 -o tap -C tests || { out=1; }
    exit $out
fi


if [ "$1" = "test:migration" ]
then
    echo_blue "test:migration"
    out=0
    docker-compose -f docker/docker-compose.travis.yml run travis_php ./docker/docker.sh private:test:migration || { out=1; }
    exit $out
fi



if [ "$1" = "private:test:migration" ]
then
    echo_blue "private:test:migration"
    out=0
    echo_blue "migration"
    php app/console --no-interaction doctrine:migrations:migrate --env=test --allow-no-migration --quiet || { out=1; }
    php app/console doctrine:schema:validate --env=test || { out=1; }
    exit $out
fi


if [ "$1" = "stop" ]
then
    echo_blue "stop"
    docker-compose -f docker/docker-compose.travis.yml stop
    docker-compose -f docker/docker-compose.development.yml stop
fi



if [ "$1" = "down" ]
then
    echo_blue "down"
    docker-compose -f docker/docker-compose.travis.yml down
    docker-compose -f docker/docker-compose.development.yml down
fi



if [ "$1" = "delete:all" ]
then
    echo_blue "delete:all"
    docker-compose -f docker/docker-compose.travis.yml stop
    docker-compose -f docker/docker-compose.travis.yml down
    docker stop $(docker ps -a -q)
    docker rm $(docker ps -a -q) -f
    docker rmi $(docker images -q) -f
    docker system prune -a
fi
