# Lessy (Laravel4 Package)

![Confide Poster](https://dl.dropbox.com/u/12506137/libs_bundles/lessy.png)

Lessy is a simple and lean less compiler for Laravel.

In summary, Lessy will compile the files contained in `app/less` to the `public/asset/css` directory respecting any existing directory structure. For example: if you have `app/less/admin/panel.less` lessy will compile it to `public/asset/css/admin/panel.css` when the application receives a request. You can change the input and output directories trough configuration (see below).

The automatic compilation occurs only if the output file doesn't exist or it's an older version than the input file. 

**Important:**
Note that the **automatic compilation does not occur when the application is in 'production' environment**. So make sure to [change the application environment](http://four.laravel.com/docs/configuration#environment-configuration "Environment Configuration") to `'local'` or something that is not `'production'` if you need Lessy to automagically compile your less files.

## Features

**Current:**
- Automagically compiles less files when not in production
- Respects directory structure when compiling
- Compile less files trough `artisan lessy:compile`

## Quick start

### Required setup

In the `require` key of `composer.json` file add the following

    "zizaco/lessy": "dev-master"

Run the Composer update comand

    $ composer update

In your `config/app.php` add `'Zizaco\Lessy\LessyServiceProvider'` to the end of the `$providers` array

    'providers' => array(

        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        ...
        'Zizaco\Lessy\LessyServiceProvider',

    ),

**You are ready to go!** Your application will now compile less files when needed. Remember that this does not occur in production environment.

### Configuration (Optional)

By default, Lessy will consider the directory `app/less` as the input and `public/assets/css` for the output. But if you wish to change these values ​​simply define `lessy.origin` and `lessy.destination` keys in `config/app.php`. Example:

    // config/app.php

    'lessy' => array(
        'origin'        => __DIR__.'/../mylessdir',
        'destination'   => __DIR__.'/../../public/mycssfiles'
    ),

The automatic **compilation does not occur when the application is in 'production' environment**. So make sure to [change the application environment](http://four.laravel.com/docs/configuration#environment-configuration "Environment Configuration") to `'local'` or something that is not `'production'` if you need Lessy to automagically compile your less files.

### Console usage

If for some reason you need to force the compilation of less files (ex: in production environment), its possible through the command:

    $ php artisan lessy:compile

## License

Confide is free software distributed under the terms of the MIT license

## Aditional information

Any questions, feel free to contact me.
