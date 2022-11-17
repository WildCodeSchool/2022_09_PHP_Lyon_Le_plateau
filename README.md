# Le PLateau

## Description

![logo.png](public/assets/images/logo.png)

Welcome fellow gamer !

This repository is all you ever dreamed as a social (and poor) boardgamer.
It contains a collaborative project called "Le plateau": a MVC based dynamic website that offers a solution to gamers to freely share and borrow boardgames from other members.

Do you want to join the adventure and help us develop the site?
Alright ! You will find belo, instructions to install the project on your local machine.


## Steps

1. Clone the repo from Github.
2. Run `composer install` on project folder.
3. Create *config/db.php* from *config/db.php.dist* file and add your DB parameters. Don't delete the *.dist* file, it must be kept.
```php

define('APP_DB_USER', 'your_db_name');          (put here your unique and very original user name )
define('APP_DB_PASSWORD', 'your_db_password');  (do not worry, db.config is part of the gitignore, your password will not leak on the world wide web)
define('APP_DB_HOST', 'your_db_host');          (most part of the time "localhost")
define('APP_DB_NAME', 'your_db_name');          (may I suggest you to use "le_plateau" ? You would avoid many difficulites)
```
4. Import *le_plateau.sql* in your SQL server, you can do it manually or use the *migration.php* script which will import a *database.sql* file.
5. Run the internal PHP webserver with `php -S localhost:8000 -t public/`. The option `-t` with `public` as parameter means your localhost will target the `/public` folder.
6. Go to `localhost:8000` with your favorite browser.
7. From this starter kit, create your own web application.


### Windows Users

If you develop on Windows, you should edit you git configuration to change your end of line rules with this command :

`git config --global core.autocrlf true`


## Example 

An example (a basic list of items) is provided (you can load the *simple-mvc.sql* file in a test database). The accessible URLs are :

* Home page at [localhost:8000/home](localhost:8000/home)
* Game list at [localhost:8000/games?page=1](localhost:8000/games?page=1)
* Login connection [localhost:8000/items/show?id=:id](localhost:8000/users/login)
* Contact page [localhost:8000/contact](localhost:8000/contact)

You can find all these routes declared in the file `src/routes.php`. This is the very same file where you'll add your own new routes to the application.


## Thanks for your participation or for sharing the project

![thanks.jpg](/public/assets/images/youaregreat.jpg)
![Your turn](https://media.giphy.com/media/kc6BNwUnGNNW6TxNpB/giphy.gif)
