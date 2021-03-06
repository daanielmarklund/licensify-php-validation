Licensify PHP Validation
========================

This is our public library to connect your web application (it works as long as it's built on PHP. Themes, single pages etc) to our licensing service at Licensify. To read more about our service, please visit: http://www.licensify.com

Requierments:
```
PHP Version >= 5.2.0, curl & json enabled
```

## Usage & Creating an account

To use this library, you need to have an account at Licensify. If you don't have one, sign up at: https://www.licensify.com/dashboard/index.php/signup

Once you have created an account, visit the "Settings" (https://www.licensify.com/dashboard/index.php/settings/) tab in the administrator dashboard.

## Obtaining the public key

In the settings tab in the administration dashboard, you'll find a section named "API Keys".
Copy the public key as it will be needed to configure the php library.

## What product will you use this library for?

We are assuming that you've already setup your product in the administrator dashboard. Browse to its location and get the unique ID for the product you wish to licensify. (Save it for later, it will be needed)

## Configuration & finishing up

Okay, you have all the information that we need. Open up "licensify.php" and go to line nr: 7. Insert your public key which you retrieved earlier on. On the line below (product_id), insert the unique product id you obtained earlier on. 
Last but not least, choose your encryption key by editing the $crypt variable. This is highly recommended since it's the encryption key for the local storage for the license. 

## Features

This library has all of the licensing features available plus the local storage.

## Debugging

To enable debugging, open licensify.php and set $debug to false (line nr 9). This will print out the messages from the licensing server.

## What does this library contain?

This library contains everything you need to license your product. The library (licensify.php) and a quick demo of how to integrate it (index.php). To try out the features, simply insert "123456" as the purchase code when you are promted about it when running the index.php file in your browser.
