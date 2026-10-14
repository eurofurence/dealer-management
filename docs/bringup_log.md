# Bringup Log

This file shall document the steps taken during the initial bringup of this project, so it can be recreated in the future.


## 1. Prepare and launch Devcontainer

Since we aim for a devcontainer from the start, it is defined first before initializing the symfony application template.
All files unique to the devcontainer live in the `.devcontainer/` folder.
See the [Devcontainer Readme](../.devcontainer/README.md) for details on design decisions taken.
The next steps are then performed in this devcontainer.


## 2. Create Symfony Skeleton

See also: https://symfony.com/doc/current/setup.html

First checked container setup with `symfony check:requirements`.

Then created project with:

    symfony new --webapp --version="8.1.*" --php="8.5" --dir="/workspace/tmp" --debug

Note that we must use the temporary subfolder or symfony will complain the path is not empty.
The `--debug` flag is only for more verbose info to see what actually happened.

**Interactive questions:**
- "Do you want to include Docker configuration from recipes?" -> NO PERMANENTLY (x), since we do this manually

After this we move the project files back up one layer:

**⚠️ DANGER:** *Inside `tmp`, symfony will have created a git. When moving this up as well, it could crash with an existing one!*

    # maybe: rm -rf tmp/.git
    mv tmp/* .
    mv tmp/.* .
    rmdir tmp

Change `composer.json` to require PHP 8.5 for this project, then `composer upgrade` to sync lockfile.

## 3. Add basic packages

    composer require easycorp/easyadmin-bundle
    composer require tecnickcom/tc-lib-pdf
    composer require --dev orm-fixtures

## 4. Add auxiliary services

Add service containers for dev into `.devcontainer/compose.yaml` and add the fitting configuration into the `.env.dev` file.
Create a `Caddyfile` for the devcontainer and add shortcuts to `justfile`.

## 5. Add minimal homepage and admin dashboard

To create the dashboard and first controller, use maker bundle:

    php bin/console make:admin:dashboard
    php bin/console make:controller

## 6. Add bootstrap with sass theming

For now we try to stick with the new asset mapper without a separate vite/webpack build.
To make it easy to create good looking forms and since our UI focus is on correct functionality rather than fancy graphics, we go with bootstap 5, which is also natively supported by Symfony Forms.
To recolor bootstrap, we need sass as well but this can be done with a small bundle working with the asset mapper:

    php bin/console importmap:require bootstrap
    php bin/console importmap:require bootstrap-icons/font/bootstrap-icons.min.css
    composer require symfonycasts/sass-bundle
    composer require twbs/bootstrap

And then adding the appropriate content to `assets/app.js` etc.
Note: For JS we use the importmap feature, for SCSS we need the composer package.
See: https://symfony.com/bundles/SassBundle/current/index.html#using-bootstrap-sass

This should be fine for now, if we get more complex later we can still switch to webpack/vite.

Manually fix bootstrap version precisely to "5.8.3" in both `importmap.php` and `composer.json`.  Remove CSS from importmap since not needed.
Delete `assets/vendor` folder, repopulate with:

    php bin/console importmap:install

Then add sass watcher to justfile.

