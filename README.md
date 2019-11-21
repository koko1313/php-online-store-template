# Online shop template

Something like framework that helps you easy create your web store.
The project is built by components that can be implemented and combinated to work together.

## Components
- **Alert messages**- alert messages that show errors, warnings and success information to the user.
- **User system** - the user system, containing login, register, and profile manager components.
- **Carousel** - page header with images (something like a slideshow).
- **Contact form** - a simple contact form that sents email directly to administrator e-mail.
- **Store** - the actual store with categories, products, and ordering functions.

## Install project
1. In **components** folder there is **db_config.php** file. In this file you have to manage your database connection.
2. In **components** folder there is **templatedb.sql** file. This file contains database startup SQL code.

## Set-up the layout
> Layout fils are located in the **layout** folder

- **head.php** - this file contains the header of the web site.
- **nav.php** - this file contains the navigation bar.
- **end_body.php** - this file contains the footer of the web site.

## Using of the components

### Alert messages
To use this component you just have to *include* it. Alert messages will show where this component is included.  
```php
<?php include "components/alert_messages/alert_messages.php"; ?>
```
##### Error messages
To show **error messages** use:
```php
$GLOBALS["error_message"] = "Error info;";
$GLOBALS["error_message"] .= "Error info 2;";
```

##### Success messages
To show **success messages** use:
```php
$GLOBALS["success_message"] = "Success message;";
$GLOBALS["success_message"] .= "Success message 2;";
```


### User system
>User system contains 3 component

##### Registration page
To include the **registration page** use:
```php
<?php include "components/user_system/register.php" ?>
```

##### Login page
To include the **login page** use:
```php
<?php include "components/user_system/login.php" ?>
```

##### Profile page
To include the **profile page** use:
```php
<?php include "components/user_system/profile.php" ?>
```


### Carousel
To include the **carousel** use:
```php
<?php include "components/carousel/carousel.php" ?>
```


### Contact form
1. To use the contact form you have to include it like this:
```php
<?php include("components/contact_form/contact_form.php"); ?>
```
2. Also you have to setup it. Go to *components/contact_form/contact_form.php*
```php
<?php
ini_set("SMTP","ssl://smtp.gmail.com"); // the SMTP
ini_set("smtp_port","465"); // the SMTP port
$to = "kaloyanvelchkov@gmail.com"; // the administrator email
$subject = "The site"; // the email's subject
?>
```


### Store
To use the store just include this:
```php
<?php include "components/store/product_list.php" ?>
```

## Examples
- **index.php** is an example of using the layout to create the index page and other custom pages.
- **page2.php** is an example of using the ***contact form***.
- **page3.php** is an example of using the ***shop component***.
- **page4.php** is an example of using the ***registration page***.
- **page5.php** is an example of using the ***login page***.
- **page6.php** is an example of using the ***profile page***.