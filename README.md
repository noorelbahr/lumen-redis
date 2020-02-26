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

Don't forget to create new database named `news`, we will use it as our database in this project as mentioned in our `.env` file (`DB_DATABASE=news`).
```
php artisan migrate && php artisan db:seed
```
It will creates 2 default users for us, `Admin` and `User`. So, keep in mind we will use these credentials to get `access_token` when login.
```
Admin -> John Doe
username: admin@gmail.com
password: john123

User -> Jane Doe
username: user@gmail.com
password: jane123
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

#### Testing Redis Queue
Now we are going to test our `queue job` with `redis`. In the project, we have 1 job (`CommentJob.php`) created in `app/Jobs` directory.

The comment job will be called in `NewsCommentController.php` when we hit comment creation API (`POST` : `/v1/news/:id/comment`)
```
// Create job for saving a comment
$job = new CommentJob($news, [
    'user_id'       => Auth::user()->id,
    'comment'       => $request->input('comment'),
    'created_by'    => Auth::user()->id
]);

// Add delay time to the job for 60 seconds, to see that our job is running and exist in redis-cli
$this->dispatch($job->delay(60));
```

Before hitting the comment creation API, `Make sure Redis Server is already installed` and run command below in the project root directory to listen our queue job :
```
php artisan queue:listen
```

and then hit comment creation API (`POST` : `/v1/news/:id/comment`) and wait for the queue job to work about 60 minutes (mentioned above : `$job->delay(60)`)

In the meantime, run command below :
```
redis-cli
``` 
Then, in Redis server run :
```
keys *
```

If it replies with `1) "queues:default:delayed"`, our job has been queued and delayed as expected in Redis server.

You can run `keys *` command repeatedly until the list is empty indicates that the job is done.

Let's check our posted comment in news list API (`GET` : `/v1/news`) and look for the news we commented on it. Or, of course we can check it on our database table (`news_comments`) for a shortcut ;) 


## Conclusion

It's nice to use Lumen for API implementation, even though many services or libraries from Laravel are trimmed.
So, we have to look for someone created a service provider to make it work with Lumen

And for me, in this project i learned a lot of things such as API Resources, Form Requests, Design Pattern, Event Listener, Queue Job and of course implementing Queue with Redis is new to me.
I don't know i have implemented correctly or not, i will figure it out and keep learning.

Cheers :)

--
- - -
