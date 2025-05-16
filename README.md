# Rails Carma Interview Task

## SetUp Instructions
This project is a Laravel application that requires PHP 8.2 or higher and Composer to manage dependencies. It also uses Node.js for front-end assets.
The project is designed to be run on a local development server, and it uses MySQL as the database.

## Prerequisites
- PHP 8.2 or higher
- Composer 1.0 or higher
- Node.js LTS 18 or higher
- MySQL
- Laravel 12
- Laravel Herd (optional, but recommended for local development)

## Installation Steps

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

## Framework & Libraries Used

- **Laravel**: 12.0.
- **PHP**: 8.2 or higher.
- **MySQL**: 8.0 or higher.
- **Node.js**: 18.0 or higher.
- **Composer**: 1.0 or higher.
- **Bootstrap**: 5.2.3: A CSS framework used for styling the application.
- **jQuery**: 3.7.1: A JavaScript library used for DOM manipulation and AJAX requests.
- **MaxMind GeoIP2**: A library used for IP geolocation. (Version 2.0.4)

## Implementation Approach

### Models
- **User**: Represents the users of the application. It has a one-to-many relationship with the `Project` model.
- **Project**: Represents the projects created by users. It belongs to a single user and can have many tasks.
- **Task**: Represents the tasks within a project. It belongs to a single project and a single user.
  
### Controllers
- **ProjectController**: Handles CRUD operations for projects.
- **TaskController**: Handles CRUD operations for tasks within a project.
- **LoginController**: Handles user authentication and token generation.
- **RegisterController**: Handles user registration and authentication.
- **HomeController**: Handles requests to the Dashboard page.

### Routes
- **web.php**: Contains routes for the web application, including authentication, project management, and task management.

### Requests
- **AddProjectRequest**: Validates project creation requests.
- **AddTaskRequest**: Validates task creation requests.
- **EditTaskRequest**: Validates task update requests.
- **EditProjectRequest**: Validates project update requests.