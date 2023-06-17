#!/usr/bin/env bash

BASEDIR=${BASEDIR:=/var/www}

$(which php) $BASEDIR/bin/console consumer:sync-books