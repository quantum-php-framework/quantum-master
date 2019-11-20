#!/bin/sh


BASEDIR=$(dirname $0)

${BASEDIR}/../composer/vendor/bin/phpunit --bootstrap ${BASEDIR}/../composer/vendor/autoload.php --testdox .