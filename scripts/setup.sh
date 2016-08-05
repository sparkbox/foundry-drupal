#!/usr/bin/env bash

echo "Creating local settings for development"
cp web/sites/default/dev.settings.local.php web/sites/default/settings.local.php

echo "Creating local services for development"
cp web/sites/default/dev.services.local.yml web/sites/default/services.local.yml

echo "Create files directory"
mkdir -m 777 web/sites/default/files
