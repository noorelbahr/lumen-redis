# Building API Service for News Management with Lumen #
The stunningly fast micro-framework by Laravel. More info at https://lumen.laravel.com

## Getting Started
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Set up Lumen project

Copy `.env.example` file to `.env`
```
cp .env.example .env
```

Install dependencies
```
composer install
```

Then, we have to migrate our migration files to create our tables and seed default data for `users` and `roles`
(in `database/migrations` and `database/seeds`)
```
php artisan migrate && php artisan db:seed
```


##### Install Laravel Passport
In order to make laravel passport work for Lumen, we are going to use `dusterio/lumen-passport` package, a simple service provider for Laravel Passport. 
For more detail visit https://github.com/dusterio/lumen-passport

Then, run command below to install passport
```
php artisan passport:install
```
It will generates 2 clients for us
```
Personal access client created successfully.
Client ID: 1
Client secret: bo72cMzHsoXcIU5sTIYIZzD0qlfSa6VYJf8J5KYk
Password grant client created successfully.
Client ID: 2
Client secret: 6UzPDESm8pQ4o474cKh6MPFLLBK0LgfiB3rLjjSu
```

##### Create Symlink for Storage Public Folder
Lumen's artisan doesn't support `storage`, that's why we can't use `php artisan storage:link` in Lumen.

So, we have to create it manually with `ln -s source_file symbolic_link`, example:
```
ln -s ~/full/path/to/lumen-passport/storage/app/public ~/full/path/to/lument-passport/public/storage
```
Replace `~/full/path/to/` with your full path of your project directory.

##### Run Our Project
Run command below to serve our project locally, we are going to use `port 8888`
```
php -S localhost:8888 -t public
```
Now we can access our project with url http://localhost:8888


### Install Redis
In this project, i used `redis queue` to for queuing the comment creation process.

So, we have to install redis server with following command 
```
brew install redis
```
More instructions [click here](https://medium.com/@petehouston/install-and-config-redis-on-mac-os-x-via-homebrew-eb8df9a4f298)

Test redis server
```
redis-cli ping
```
If it replies `PONG`, then it works!

## Testing Our API
To test our API, visit Postman Public Link below

link

You can test every single endpoint in the postman collection

#### Test Redis Queue

