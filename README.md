# PHP-Blog-Application

## Backend Overview

This backend, built with PHP, provides a RESTful API for managing a blog application. The API handles user authentication, blog posts, and comments. Below are the available endpoints and their functionalities.

## API Endpoints

### Users

- **GET /users**: Retrieve a list of all users.
- **GET /users/{id}**: Retrieve details of a single user by their ID.
- **POST /users**: Register a new user.
- **POST /users/login**: Login a user.
- **POST /users/logout**: Logout the currently logged-in user.

### Blogs

- **GET /blogs**: Retrieve a list of all blogs.
- **GET /blogs/{id}**: Retrieve details of a single blog by its ID.
- **POST /blogs**: Create a new blog post.
- **PATCH /blogs/{id}**: Update an existing blog post by its ID.
- **DELETE /blogs/{id}**: Delete a blog post by its ID.

### Comments

- **GET /comments/{blog_id}**: Retrieve all comments for a specific blog post.
- **POST /comments/{blog_id}**: Create a new comment for a specific blog post.
- **DELETE /comments/{id}**: Delete a comment by its ID.

## Frontend Overview

The frontend portion of the site was build using HTML5, CSS3, JavaScript, adn jQuery. When initially staring the application, make sure you are in the root folder - the application won't work if it is not started from frontend/index.html
