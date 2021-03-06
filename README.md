# applications-subscription API
[applications-subscription](https://github.com/mnourrhan/application-subscription) API - Mobile applications subscription built with Laravel framework on which multiple applications on different devices can enable in-app purchases, confirm purchase order and check
subscription status by using this API

## Installation
- Clone the repo
- Run "php artisan config:cache"
- Run "php artisan config:clear"
- Change the .env file DB_HOST with your local host and your DB configuration.
- Run "php artisan config:cache"
- Run "php artisan config:clear"
- Run "php artisan migrate"
- You can run "vendor/bin/phpunit" for testing the APIs


## Authentication
Aside from the initial call to the `/sessions` endpoint, `POST`, `PATCH`, `DELETE` and sensitive `GET` requests will need to be authenticated.

An `Authorization: Basic` header with a Base64 encoding of `access_token`, where `access_token` is retrieved from the JWT authentication.

## Media Types
This API uses the JSON format, given limited client support `Content-Type` and `Accept` should still be set to `application/json`.

Requests with a message-body are using plain JSON to set or update resource states.

## Error States
statu with fail value will be returned when error occur

Specifally, this API uses:

- 200: "Successful", often return from a GET/POST request
- 422: "Failed", invalid request often return from a GET/POST request
- 500: "Failed", server error often return from a GET/POST request

## Application device register [/api/v1/register]
A single device object.

The Device resource has the following attributes:

- id
- uid
- app_id
- language
- operating_system
- created_at
- updated_at

The states *id*, *created_at* and *updated_at* are assigned by the API at the moment of creation.

+ Request (application/json)

    + Headers

            Accept: application/json

    + Body

            {
                "uid": "5213-5423-hj5uj-5jhg",
                "app_id": "123456",
                "language": "english",
                "operating_system": "ios"
            }

+ Response 200

        {
            "message": "Registered successfully",
            "data": {
                "token": "eyJ0eXAiOiJKV1QiLCJh...",
            }
        }

+ Response 422  
  
      {
          "message": "The given data was invalid.",
          "errors": {
              "app_id": [
                "The app id field is required."
              ],...
          }
      }

## Application purchase verification [/api/v1/purchase]

The Subscription resource has the following attributes:

- id
- device_id
- receipt
- expiry_date
- created_at
- updated_at

The states *id*, *created_at* and *updated_at* are assigned by the API at the moment of creation.

+ Request (application/json)

    + Headers

            Accept: application/json
            Authorization: Bearer "token ..."

    + Body

            {
                "receipt": "12345"
            }

+ Response 200

        {
            "message": "Purchase verified successfully",
            "data": {
            }
        }

+ Response 422

      {
          "message": "The given data was invalid.",
          "errors": {
              "receipt": [
                "The receipt field is required."
              ],...
          }
      }

+ Response 500

      {
          "message": "Server error occurred. Please try again later!",
          "errors": {
          }
      }

+ Response 406

      {
          "message": "The receipt you have sent is not verified!",
          "errors": {
          }
      }

## Check Subscription Status [/api/v1/check/subscription/status]

The Subscription resource has the following attributes:

+ Request (application/json)

    + Headers

            Accept: application/json
            Authorization: Bearer "token ..."

    + Body

            {
                
            }

+ Response 200

        {
            "message": "The subscription is active! or The subscription is inactive!",
            "data": {
            }
        }

+ Response 500

      {
          "message": "Server error occurred. Please try again later!",
          "errors": {
          }
      }

+ Response 406

      {
          "message": "No subscriptions existing for this app!",
          "errors": {
          }
      }
