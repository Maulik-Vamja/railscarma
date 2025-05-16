# Rails Carma Interview Task

## Installation

Follow below steps to install and run this project.

Step 1. Clone this Project.

Step 2. run below commands, It will install all the necessary dependencies

```
composer install
npm install
npm run dev
```

Step 3. Create a copy of the `.env.example` file and rename it to `.env`. You can do this by running the following command in your terminal:

```
cp .env.example .env
```
Step 4. Generate an application key by running the following command:

```
php artisan key:generate
```
Step 5. Set up your database connection in the `.env` file. Update the following lines with your database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```
Step 6. Create a new database in your MySQL server with the name you specified in the `.env` file.

Step 7: run all the migrations
```
php artisan migrate
```

Step 8: Create the symbolink for the filesystem, change filesystem to public in the .env file

```
FILESYSTEM_DRIVER=public
```

Then run below command to create the symlink for the storage folder

```
php artisan storage:link
```


Step 9: run project, If you are using Laravel herd then directly run from the "Sites" Menu of the Herd, otherwise run below command
make sure your php version is ^8.2. Laravel v12 is required PHP Version ^8.2.
```
php artisan serve
```

You're good to go.
