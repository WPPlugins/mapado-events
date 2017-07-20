PHP SDK for Mapado API [![Build Status](https://travis-ci.org/mapado/php-sdk.svg?branch=master)](https://travis-ci.org/mapado/php-sdk)
=======

## Installation
Install with [composer](http://getcomposer.org):
```
composer require "mapado/php-sdk:~0.1"
```

## Usage

### Get a token
```php
$oauth = \Mapado\Sdk\Oauth::createOauth('your_client_id', 'your_client_secret');
// get a client token (ie. anonymous user)
$token = $oauth->getClientToken();

// get a user token (logged user)
$token = $oauth->getUserToken('username', 'password');

// get a user token from a facebook token
$token = $oauth->getFacebookToken('facebook-token');
```

### Refresh a token
The token is valid 1h. You can refresh it by calling:
```php
$token = $oauth->refreshUserToken('refresh_token');
```

### Create a client
```php
$client = \Mapado\Sdk\Client::createClient($token, 'fr');
```

### Rubrics
```php
$rubrics = $client->rubric->findBy(['q' => 'concert']);
$rubric = $client->rubric->findOne('63e4b024-6f08-43d3-a73b-634a306bc6c6');
```
Will return an MapadoList or an instance of [Rubric](https://github.com/mapado/php-sdk/blob/master/src/Model/Rubric.php)

### Activities
```php
$activities = $client->activity->findBy(['q' => 'transbordeur', latlng => '45.468,4.263']);
$activity = $client->activity->findOne('63e4b024-6f08-43d3-a73b-634a306bc6c6');
```
Will return an MapadoList or an instance of [Activity](https://github.com/mapado/php-sdk/blob/master/src/Model/Activity.php)

#### Program
If an activity contains a program, you can get it by doing this call
```php
$activityList = $client->activity->program('63e4b024-6f08-43d3-a73b-634a306bc6c6');
```
Will return an MapadoList or an instance of [Activity](https://github.com/mapado/php-sdk/blob/master/src/Model/Activity.php)

### Users
```php
$me = $client->user->me();
$user = $client->user->findOne('63e4b024-6f08-43d3-a73b-634a306bc6c6');
```
Will return an instance of [User](https://github.com/mapado/php-sdk/blob/master/src/Model/User.php)

#### User Lists
```php
$me = $client->user->me();
$userList = $client->userList->findByUuid('63e4b024-6f08-43d3-a73b-634a306bc6c6');


// get the program of this first userList
$firstUserList = current($userList); // just for the doc, you may want a more clever logic
$activityList = $client->activity->program($firstUserList->getUuid());
```
Will return an MapadoList or an instance of [Activity](https://github.com/mapado/php-sdk/blob/master/src/Model/Activity.php)

### List objects
Lists results will be contained in a [MapadoList](https://github.com/mapado/php-sdk/blob/master/src/Model/MapadoList.php) object.
The list object is traversable (you can iterate on it with a foreach),
and may contains some informations like totalhits, page, etc.


### Other calls
Every endpoint listed in our documentation *should* be available like the one atop.
If a endpoint is missing, please open an issue on github
