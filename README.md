# RedSky Recruitment

#### Recruitment test from RedSky to Shorte.st project

## Requirements
- PHP >= 7.4
- Composer

## How to run / Installation
1. Clone from Repo
2. Create ```.env``` from `.env-test.env` change Mailer
3. ```composer install```
4. Set your email in ```config/packages/dev/monolog.yaml``` in swift:to_email

vHost - Apache
```
<VirtualHost *:80>
	DocumentRoot "C:/xampp/htdocs/redsky/public/"
	ServerName redsky.loc
	ServerAlias www.redsky.loc
	ServerAdmin webmaster@redsky.loc
	ErrorLog "logs/redsky.loc-error.log"
	CustomLog "logs/redsky.loc-access.log" common
	<Directory "C:/xampp/htdocs/redsky/public/">
		Options +Indexes +Includes +FollowSymLinks +MultiViews
	    AllowOverride All
	    #Require local
		#FallbackResource /index.php
	</Directory>
</VirtualHost>
```

## Using

This is console application, use in bash/terminal:
```
php bin/console redsky:get-currency currencyFrom currencyTo dateFrom dateTo
```
| Name | Type | Example |
|---|---|---|
| currencyFrom | string | EUR |
| currencyTo | string | USD |
| dateFrom | string | 2021-01-01 |
| dateTo | string | 2021-06-01 |

Free plan has only EUR in currencyFrom currency

Example:
```
php bin/console redsky:get-currency USD EUR 2021-01-01 2021-06-01
```

