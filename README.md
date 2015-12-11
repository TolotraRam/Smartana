# dailyfind

# api-dev branch


## Features:

Independent Laravel, Angular
Laravel 5.1 for API
Include Json Auth Token
Include Data Transformer
Include API Data Exception
Output JSON or others
Include CSRF Protection
Timezone
Angular 1.4 for Backend
Include AdminLTE template
Include ui-router, Restangular etc ...
Timezone
Support Multi-Languages interface
Backend
User & Role management
Manage Media & Media Categories
Manage posts and posts categories
## Frontend
Soon
## Install:

Step 1: Get the code
Step 2: Init api
Step 3: Init Backend
Step 4: Production

### Step 1: Get the code

Download Now


### Step 2: Init api

Init Laravel

Move to api directory
Run composer install
Init Database

Setup database config in .env file (copy from .env.example)
Run php artisan migrate --seed

### Step 3: Init Backend

This project makes use of Bower. You must first ensure that Node.js (included in homestead) is installed on your machine.

Install npm, gulp, bower
Run sudo npm install
Run bower install
Edit backend/src/index.js, replace cms.dev to your api domain
Run gulp serve for development

### Step 4: Production

API

edit .env file set APP_DEBUG to false
Backend

run gulp in backend directory. It will auto copy backend/dist all files to api/public/assets-backend
Frontend

Move all frontend files to api/public