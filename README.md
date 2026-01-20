# Controle

## Table of contens <!-- omit in toc -->

- [Pre-requisites](#pre-requisites)
- [Objective](#objective)
  - [Notation](#notation)
- [Installation](#installation)
- [How to submit](#how-to-submit)
- [Features](#features)

## Pre-requisites

You need to have these installed on your machine :

- docker engine
- docker compose

You would also need to know how a website made in PHP works, and how to write object oriented code.

## Objective

The original maintainer wants you to create a REST API, written in PHP with object oriented style, so people could use the application without using the frontend.

You will translate as much features as you could.

To differentiate the API from the rest of the application, every API routes should start with `/api/`.

### Notation

Every new class you will create is worth 3 points. It will be judged on :
- its implementation (is it working properly, does it respect coding standards, ...)
- its usefulness (is the class has an utility in the app)
- how you used it in the app (is it called properly)

Every major feature of a framework (routing, ORM, database maintaining, ...) is worth 5 points. Add as many as you want.

## Installation

```bash
# Step 1 : download the project
git clone https://github.com/<your-username>/php-oop-exercice

cd php-oop-exercice

# Step 2 : initialize the docker containers
docker compose up -d --build

docker compose exec php-oop-exercice composer install

docker compose exec php-oop-exercice composer dump-autoload

# Step 3 : initialize the database
# open app/mysql/blog.sql
# copy the content of the file

docker compose exec php-oop-exercice-db mysql -u root -p

# the root password is `password`

# once in the mysql CLI, paste the content of the file
# check the db has been created with :

show tables;

# exit the mysql CLI

docker compose exec php-oop-exercice php ./fixtures/generate.php

```

The application should now be available at `http://127.0.0.1:8888`.

## How to submit

1. Fork this project by clicking on this button :

![fork button](assets/fork.png)

Github will clone this repository on your account so you won't modify this template.

Once the execice is done, you will do a Pull Request from the Github page of your repository

![pull request](assets/PR.png)

The title of your Pull Request must contain your name, you firstname and you class.

## Features

| Feature                                 | Route                          |
|-----------------------------------------|--------------------------------|
| List of blogs                           | /                              |
| Login                                   | /login.php                     |
| Register                                | /register.php                  |
| Read a blog and the associated comments | /index.php?id=<blog-id>        |
| Post a new comment                      | /index.php?id=<blog-id>        |
| List the blogs of a user                | /users.php?id=<user-id>        |
| Update profile                          | /profile.php                   |
| Create a new blog                       | /blogs/new.php                 |
| Logout                                  | /logout.php                    |
