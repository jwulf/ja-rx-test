# Movie API

A simple Laravel API app for managing movies, genres, and actors.

## Requirements

* [Docker](https://www.docker.com/)
* [Docker Compose](https://docs.docker.com/compose/install/)

## Installation

To set up the environment using Docker Compose, simply run the following command
to download and build php-fpm, nginx, and postgres containers as well as run
migrations and database seeders.

```sh
docker-compose up
```

You should now be able to access the app at [http://localhost:8000](http://localhost:8000).

## Authentication

The app uses OAuth2 to protect all API routes. On initial setup you will need to
create OAuth keys for your environment, and generate a password grant client.
You will then able to request access tokens for authenticating against the API.

### Generate OAuth2 keys and generate a password grant client

```
docker-compose exec app su app -c "php artisan passport:install"
```

Make a note of the `Client ID` and `Client Secret` for the Password Grant Client
(generally the second client outputted) as you will need them to request an
access token in the next step.

### Request an access token

You will need to provide the `Client ID` and `Client Secret` from the previous
step, as well as a valid username and password to generate an access token.
A development testing user account will have been created for you on
installation with a username of `dev@example.com` and password `secret`.

Make an HTTP POST request to `http://localhost:8000` with a JSON body as follows:

```json
{
    "grant_type": "password",
    "client_id": <Your Client ID>,
    "client_secret": "<Your Client Secret>",
    "username": "dev@example.com",
    "password": "secret"
}
```

You should receive a JSON response with a Bearer access_token for use in
authenticating API requests.

### Pass the access token in the HTTP header of all API requests

Any requests made to the API require authenticating via access token. This is
passed via the `Authorization` HTTP header. As this is a JSON API, you should
also pass an appropriate `Accepts` header, and a `Content-Type` header where
appropriate. E.g:

```
Authorization: Bearer <Your Access Token>
Accepts: application/json
Content-Type: application/json
```

## Usage

See [api-description.apib](api-description.apib) for an API Blueprint describing
the available resources.

## Testing

To run the test suite for the application, execute:

```sh
docker-compose exec app su app -c "php vendor/bin/phpunit"
```
