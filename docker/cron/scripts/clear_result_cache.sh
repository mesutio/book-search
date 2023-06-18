#!/usr/bin/env bash

BASEDIR=${BASEDIR:=/var/www}

$(which php) $BASEDIR/bin/console cache:pool:clear doctrine.result_cache_pool