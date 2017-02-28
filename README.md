AuthBundle
==========
[![Build Status](https://travis-ci.org/netbull/AuthBundle.svg?branch=master)](https://travis-ci.org/netbull/AuthBundle)<br>
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/f91df530-6930-44c3-b300-0ac712498063/big.png)](https://insight.sensiolabs.com/projects/f91df530-6930-44c3-b300-0ac712498063)

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require netbull/auth-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...

            new Netbull\AuthBundle\NetbullAuthBundle(),
        ];

        // ...
    }

    // ...
}
```

Step 3: Update the Database
---------------------------

update the database:
```bash
php bin/console doctrine:schema:update --force
```

Step 4: Configuration
---------------------------

```yaml
// app/config/security.yml

// ...
    firewalls:
        // ...
        
        main:
            pattern: ^/
            anonymous: ~
            form_login:
                remember_me: true
                check_path: netbull_auth_check
                login_path: netbull_auth_login
                provider: default
                csrf_token_generator: security.csrf.token_manager
            logout:
                path: netbull_auth_logout
                target: /login
                invalidate_session: true
                delete_cookies:
                    name:
                        path: null
                        domain: null
                handlers: []
            switch_user: { role: ROLE_ADMIN, parameter: _view_as }
            remember_me:
                secret  : "%secret%"
                lifetime: 31536000 # Year
                path    : /
                domain  : ~
        // ...
        
        access_control:
            - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/office, roles: ROLE_TEAM }
```
