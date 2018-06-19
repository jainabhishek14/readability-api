# PHP Readability Service

Use this readability service to extract relevant content from links or messed up html. This service uses the latest Slim 3.

This skeleton application was built for Composer. This makes setting up a new Slim Framework application quick and easy.

## Pre-requisites
* PHP >= 5.6
* php-xml
* php-mbstring
* composer >= 1.5.2

## Install the Application

Run this command from the directory in which you want to install your new service.

    php composer.phar install

You'll want to:

* Point your virtual host document root to `public/` directory.
* Ensure `logs/` is web writeable.

To run the application in development, you can run these commands 

	cd readability
	php composer.phar start

Run this command in the application directory to run the test suite

	php composer.phar test

That's it!
