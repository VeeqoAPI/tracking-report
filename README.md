# Veeqo API Tracking Report

This example shows a basic Tracking Report

## Requirements
 
- PHP >= 5.4
- PHP CURL

## How to launch locally

    cd ./veeqo-dashboard
    php -S localhost:8080

Open http://localhost:8080

## How to launch on Heroku

Sign up for a Heroku account:
https://www.heroku.com/

Then in a console

    heroku login

Then clone the application

    git clone https://github.com/VeeqoAPI/dashboard.git
    cd veeqo-dashboard
    
Deploy the your own instance of the application to Heroku

    heroku create
    git push heroku master
    heroku ps:scale web=1
    heroku open
    
More details and solutions to common problems can be found on Heroku's Website.

https://devcenter.heroku.com/
    
 
    
    



