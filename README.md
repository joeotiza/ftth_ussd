# PHP package for FTTH account management solution on USSD

This is a PHP package for a FTTH account management solution on USSD based on the `Africa's Talking` API. This application has been developed on sandbox (testing) mode. To go live contact `Africa's Talking Ltd` on https://www.africastalking.com/contact.

## Prerequisites

For testing download `Africa's Talking` android app from Google Playstore or use the web interface at https://simulator.africastalking.com:1517/

## Installation

This project needs composer to use phpspreadsheet to handle import and export of `.xlsx`/`.xls`/`.csv` files.

- Run the following commands in terminal to install composer

```
(echo; echo 'eval "$(/opt/homebrew/bin/brew shellenv)"') >> /Users/joseph/.zprofile

eval "$(/opt/homebrew/bin/brew shellenv)"

brew install php

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

```
- Run the following commands to install phpspreadsheet

```
php composer.phar require phpoffice/phpspreadsheet
```

## Configuration

1. Import the ftth_ussd.sql file into MySQL database.


2. Go to "https://account.africastalking.com/". Create an account then click on the `Go to Sandbox App` button

3. In your sandbox account under USSD > Create Channel , pick a shared service code such as `*384*` and a channel such as 1100 i.e `*384*1100#` (Be sure to take a unique channel which is not taken already)

4. Configure your callback URL (the URL that points to your application) e.g http://www.example.com/ftth_ussd/ussd.php then click `Create channel`. This assumes you are working from a live server whose domain name is example.com. Replace the domain name with your own.

5. If working from localhost you can set up a `Ngrok` server or `Localtunnel` to expose your localhost to the internet. Use the temporary URL provided as your callback e.g http://6a71f5ec.ngrok.io/ftth_ussd/ussd.php. This only works when the computer is on and connected to the internet. If using `Ngrok` free package this address may change every 8 hours. You could opt for a paid version at 5 US dollars a month. Configure `Ngrok` using the command:

```

sudo ~/Downloads/ngrok http 80

```


6. Now test the USSD application using `Africa's Talking` android app downloaded from Google Playstore or use the web interface at https://simulator.africastalking.com:1517/ using the USSD code you configured i.e. `*384*1100#`


