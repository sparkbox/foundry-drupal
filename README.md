# Sparkbox Foundry on Drupal 8

This is a proof-of-concept build of the Foundry on Drupal 8 for research purposes.

## Requirements

- Install the latest version of [Docker}(https://docs.docker.com/engine/installation/)  
for installing a local environment.
- Install [Composer](https://getcomposer.org/) with Homebrew: `brew install composer`
- Install [Platform.sh CLI](https://docs.platform.sh/user_guide/overview/cli/index.html)  
tool: `curl -sS https://platform.sh/cli/installer | php`
- Run `platform` once to authenticate (see credentials in 1Password)
- Add your public key with the command `platform ssh-key:add`

## Setup

1. Clone the repository and `cd` into the root of the repo
2. Run `composer install` to install all Drupal dependencies
3. Run `docker-compose up -d` to initialize a local environment
4. Run `./script/setup.sh` to configure your local environment
5. Run `./scripts/sync.sh` to sync data and files to your local environment
5. View the site at: http://localhost:8000

## Docker

A `docker-compose.yml` file is included to setup a fully functioning 
environment for this site. Here are the relevant commands:

- `docker-compose up -d` - Setup the environment
- `docker-compose down` - Tear down the environment

With Docker running and setup you can access the website and various tools
with the following URLs:

- http://localhost:8000 - The Drupal website
- http://localhost:8001 - phpMyAdmin access to your database
- http://localhost:8002 - Mailhog, a service to capture and view transactional emails from the site

For more information about the Docker setup see the [docs](http://docker4drupal.org/). 


