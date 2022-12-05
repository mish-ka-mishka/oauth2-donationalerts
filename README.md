# DonationAlerts Provider for OAuth 2.0 Client

This package provides DonationAlerts OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require mkaverin/oauth2-donationalerts
```

## Usage

Usage is the same as The League's OAuth client, using `\MKaverin\OAuth2\Client\Provider\DonationAlerts` as the provider.

### Authorization Code Flow

```php
$provider = new MKaverin\OAuth2\Client\Provider\DonationAlerts([
    'clientId'          => '{donationalerts-client-id}',
    'clientSecret'      => '{donationalerts-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url',
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $user->getName());

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}
```

### Managing Scopes

When creating your DonationAlerts authorization URL, you can specify the state and scopes your application may authorize.

```php
$options = [
    'state' => 'OPTIONAL_CUSTOM_CONFIGURED_STATE',
    'scope' => ['oauth-user-show','oauth-donation-index'] // array or string; at least one is required
];

$authorizationUrl = $provider->getAuthorizationUrl($options);
```
If neither are defined, the provider will utilize internal defaults.

At the time of authoring this documentation, the [following scopes are available](https://www.donationalerts.com/apidoc#authorization__scopes).

- oauth-user-show
- oauth-donation-subscribe
- oauth-donation-index
- oauth-custom_alert-store
- oauth-goal-subscribe
- oauth-poll-subscribe

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## License

The MIT License (MIT). Please see [License File](https://github.com/mish-ka-mishka/oauth2-donationalerts/blob/master/LICENSE) for more information.
