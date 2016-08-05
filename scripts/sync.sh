#!/usr/bin/env bash

echo "Export data from Platform.sh master environment..."
platform sql-dump -e master -f dump.sql

echo "now import data into local database..."
docker-compose exec --user 82 php sh -c "cd web; drush sqlc < ../dump.sql"

echo "Sync files"
rsync -r $(platform project:info id)-master@ssh.us.platform.sh:web/sites/default/files/. web/sites/default/files/

echo "clean up"
rm dump.sql

echo "remote-->local sync complete"
