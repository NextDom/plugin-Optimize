#!/bin/sh

if [ -z "$PHP_FOR_TESTS" ]; then
    PHP_FOR_TESTS=php
fi

echo Version de PHP
php --version

MOCKED_ENV=tests/mocked_Jeedom_env

rm -fr $MOCKED_ENV/plugins/Optimize
mkdir $MOCKED_ENV/plugins/Optimize
mkdir $MOCKED_ENV/plugins/Optimize/tests
cp -fr core $MOCKED_ENV/plugins/Optimize
cp -fr desktop $MOCKED_ENV/plugins/Optimize
cp -fr plugin_info $MOCKED_ENV/plugins/Optimize
cp -fr tests/testsuite $MOCKED_ENV/plugins/Optimize/tests
cp -fr tests/phpunit.xml $MOCKED_ENV/plugins/Optimize/phpunit.xml
cp -fr tests/phpunit_without_cover.xml $MOCKED_ENV/plugins/Optimize/phpunit_without_cover.xml
cp -fr vendor $MOCKED_ENV/plugins/Optimize
pwd

cd $MOCKED_ENV/plugins/Optimize

echo "Version ligne de commande"
./vendor/phpunit/phpunit/phpunit --coverage-clover ./build/logs/clover.xml --whitelist "." --exclude "vendor" ./tests
echo "Version directe"
$PHP_FOR_TESTS ./vendor/phpunit/phpunit/phpunit ./tests
echo "Version XML"
$PHP_FOR_TESTS ./vendor/phpunit/phpunit/phpunit --configuration phpunit_without_cover.xml
echo "Version XML avec coverage"
$PHP_FOR_TESTS ./vendor/phpunit/phpunit/phpunit --configuration phpunit.xml
