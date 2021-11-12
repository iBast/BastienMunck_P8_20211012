# ToDoList
==========
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/4fd3154b190944b4987dddbdd7a5ded5)](https://www.codacy.com/gh/iBast/BastienMunck_P8_20211012/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=iBast/BastienMunck_P8_20211012&amp;utm_campaign=Badge_Grade) [![Codacy Badge](https://app.codacy.com/project/badge/Grade/4fd3154b190944b4987dddbdd7a5ded5)](https://www.codacy.com/gh/iBast/BastienMunck_P8_20211012/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=iBast/BastienMunck_P8_20211012&amp;utm_campaign=Badge_Grade)
*This is part of [OpenClassrooms](https://openclassrooms.com/fr/) PHP / Symfony training program*

## Install the project

### Requirements
To folow the instructions make sure to have installed : 
- [Composer](https://getcomposer.org)
- [Symfony CLI](https://symfony.com/doc/current/cloud/getting-started#installing-the-cli-tool)

- Clone this repository & enter the new folder
```console 
git clone https://github.com/iBast/BastienMunck_P8_20211012.git
cd BastienMunck_P8_20211012
```

- Add a file *.env.local* with the following lines :
``ènv
APP_ENV={*dev*} for developpement {*prod*} for production
APP_SECRET={YourAppSecret}
DATABASE_URL="mysql://{*user*}:{*$password*}@{*host*}:{*port*}/{*DbName*}?serverVersion={*server version*}"
```

you can do it with the console :
```console
touch .env.local
nano .env.local
```

- install the dependencies with composer 
```console
composer install
```

- create the database
```console
symfony console doctrine:database:create
```

- update the database schema
```console
symfony console doctrine:schema:update --force
```

## Contribute
