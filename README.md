[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/martin-georgiev/social-post/badges/quality-score.png)](https://scrutinizer-ci.com/g/martin-georgiev/social-post/?branch=master)
[![Build Status](https://api.travis-ci.org/martin-georgiev/social-post.svg?branch=master)](https://www.travis-ci.org/martin-georgiev/social-post)
[![Latest Stable Version](https://poser.pugx.org/martin-georgiev/social-post/version)](https://packagist.org/packages/martin-georgiev/social-post)
[![Total Downloads](https://poser.pugx.org/martin-georgiev/social-post/downloads)](https://packagist.org/packages/martin-georgiev/social-post)

----
## What's this?
This is a library that provides centralised gateway for publishing post updates to social networks. Currently, it supports Facebook, LinkedIn and Twitter.


----
## How to install it?
Recommended way is through [Composer](https://getcomposer.org/download/)

    composer require "martin-georgiev/social-post"
    

----
## Additional help
Twitter has limited features for tweet customisation. This means that for tweets only `message` and `link` values (`Message` instance) will be used.

Facebook doesn't support non-expiring user access tokens. Instead, you can obtain a permanent page access token. When using such tokens you can act and post as the page itself. More information about the page access tokens from the official [Facebook documentation](https://developers.facebook.com/docs/facebook-login/access-tokens/expiration-and-extension#extendingpagetokens). Some Stackoverflow answers ([here](https://stackoverflow.com/a/21927690/3425372) and [here](https://stackoverflow.com/a/28418469/3425372)) also can of help. 

----
## License
This package is licensed under the MIT License.
