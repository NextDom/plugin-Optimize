#!/bin/sh

MOCKED_ENV=tests/mocked_Jeedom_env

if [ -z "$PHP_FOR_TESTS" ]; then
    PHP_FOR_TESTS=php
fi

echo "Version de PHP"
$PHP_FOR_TESTS --version

rm -fr $MOCKED_ENV/plugins/Optimize
mkdir $MOCKED_ENV/plugins/Optimize
mkdir $MOCKED_ENV/plugins/Optimize/tests
cp -fr core $MOCKED_ENV/plugins/Optimize
cp -fr desktop $MOCKED_ENV/plugins/Optimize
cp -fr plugin_info $MOCKED_ENV/plugins/Optimize
cp -fr tests/testsuite $MOCKED_ENV/plugins/Optimize/tests
cp -fr tests/phpunit_local.xml $MOCKED_ENV/plugins/Optimize/phpunit.xml
cp -fr vendor $MOCKED_ENV/plugins/Optimize

cd $MOCKED_ENV/plugins/Optimize
$PHP_FOR_TESTS ./vendor/phpunit/phpunit/phpunit --configuration phpunit.xml
