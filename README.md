# Panneau d'affichage numérique, WebApp

![demo img PNG](./demo/7.png)



## More Screenshots: [ demo.md ](./demo.md)

## Requirements / installation

- need [ PHPMailer 6.2.0 ](https://github.com/PHPMailer/PHPMailer/releases/tag/v6.2.0)

- need `mysql pdo` and of course a web server with php
   
   `git submodule init` at the root of the project

- Configure the `./config/var_config.php` file

- SQL code: `./sql/dev_immeuble.sql`
  -  your need to change the lines 48 and 49 at `CHANGE ME` notice with the `./hash.php` given with your password