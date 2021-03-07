## About The Guardian RSS Feed news sections
Server side application exposing RSS feeds corresponding to the categories of [The Guardian](https://www.theguardian.com), the UK leading newspaper. User can ask for URLs in the format /[ section-name ] corresponding to the newspaper sections (.i.e /fashion, /technology, /politics, /lifeandstyle) and receive an RSS feed with the latest articles.

## Requirements
- This application is developed with **Laravel v.7** / **Php 7.4.1**
Please check the official Laravel installation guide for [server requirements](https://laravel.com/docs/7.x/installation#server-requirements) before you start.
- [Composer](https://getcomposer.org/) is need to handle dependencies

## Running locally the application
- Download / clone this repo to the desired project directory on your local pc
- Open the console and cd your project root directory
- Run `composer install`
- In your project root, rename the **.env.example** file to **.env**
- Run `php artisan key:generate`
- Run `php -S localhost:8000`
- Access the application at **http://localhost:8000**