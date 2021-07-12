# RedSky Recruitment

#### Recruitment test from RedSky to Shorte.st project

## Requirements
- PHP >= 7.4
- Composer

## How to run / Installation
1. Clone from Repo
2. ```.env``` change Swift mailer
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

