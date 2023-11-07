# Whoami API by Vimar

[![MIT license](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/vimar/flarum-whoami/blob/master/LICENSE.md)

This extension adds a new endpoint "/api/whoami" which exposes the data of currently logged-in user.
By using this route with the POST /api/token route, you can use Flarum as an identity provider for your site.

## Installation

Install manually:

```bash
composer require vimar/flarum-whoami
```

## Updating

```bash
composer update vimar/flarum-whoami
php flarum migrate
php flarum cache:clear
```

## Tutorial for Flarum Authentication

### Login

To emulate a login statement to Flarum, we just call the [Flarum POST /api/token](https://docs.flarum.org/rest-api#creation-1) in order to crate a token for registered user:

```text
POST /api/token HTTP/1.1

{
    "identification": "John",
    "password": "pass7word",
    "remember": 1
}

HTTP/1.1 200 OK

{
    "token": "YACub2KLfe8mfmHPcUKtt6t2SMJOGPXnZbqhc3nX",
    "userId": "1"
}
```

then we store this token as a cookie (eg: `flarum_token`) for future use (by default an access token lasts 1 hour unless you add the `remember` parameter, in which case it lasts for 5 years.).

Note: To enable SSO between our website and flarum, we need to store a session_remember token as `flarum_remember` cookie too;

### Verify logged-in user profile

Now, each time we want to know if an user is logged-in, we only need to call the following endpoint:

```text
POST /api/whoami HTTP/1.1
Authorization: Token YACub2KLfe8mfmHPcUKtt6t2SMJOGPXnZbqhc3nX

HTTP/1.1 200 OK

{
    "data": {
        "type": "users",
        "id": "4",
        "attributes": {
            "username": "John",
            "displayName": "Jon Doe",
            "slug": "John-Doe",
            "joinTime": "2003-04-02T20:29:29+00:00",
            "discussionCount": 125,
            "commentCount": 2799,
            "canEdit": true,
            "canEditCredentials": true,
            "canEditGroups": true,
            "canDelete": true,
            "lastSeenAt": "2023-11-07T09:43:13+00:00",
            "isEmailConfirmed": true,
            "email": "john@doe.com",
            "markedAllAsReadAt": null,
            "unreadNotificationCount": 0,
            "newNotificationCount": 0,
            "preferences": {
                ...
            },
            "isAdmin": true,
            ...
        },
        "relationships": {
            "groups": {
                "data": [
                    {
                        "type": "groups",
                        "id": "1"
                    }
                ]
            }
        }
    },
    "included": [
        {
            "type": "groups",
            "id": "1",
            "attributes": {
                "nameSingular": "Admin",
                "namePlural": "Admins",
                "color": "#B72A2A",
                "icon": "fas fa-wrench",
                "isHidden": 0
            }
        }
    ]
}
```

### Logout

To logout, we need to delete our session cookie (containing access token) and eventually purge all of them on Flarum by calling
