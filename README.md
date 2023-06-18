# Book Search API

Book Search API is providing search ability among books in the system. This project written via Symfony 6.3.

## Project Requirements

This project needs the following dependencies to run properly.

- PHP >=8.1
- MySQL 5.7
- Redis

## Infrastructure & Installation

Information for infrastructure to run on local environment explained in [infrastructure](documentation/infrastructure-readme.md) readme.

## Project Structure

### Migrations
Each database changes doing via migrations. You can find them in [migrations](migrations) folder.

### Entities
There are 3 entities for this application. The main entity is `Book` and related tables are `BookAuthor` and `BookCategory` . These entities inserting and updating for each 5 minute by cron job.

### Command
The following `sync command` is crucial for this application to keep it up with latest data. It has been scheduled to run for each 5 minutes.

- Inserting and updating all information regarding Books : `bin/console consumer:sync-books`

### Crontab
This application needs to run cronjob to keep and up the application in case any change on data. And it is handling by `crontab` service in docker environment.

### Swagger API Documentation
Endpoints example requests and responses has Swagger documentation, which mean you can easily test it without needing effort. You may reach Swagger UI following URL once you finished installation:

`http://127.0.0.1:8001/api-doc`

### Tests

All tests live in the same namespace as tested classes.

```
tests
├── unit
│   ├── Testing each unit of application. 
```

You can easily run the tests with following command:

`make tests-unit`

## Cache
This application using various caching to make it faster and speed. Cache is the most crucial part of this application like many others. In this application using
metadata cache, query cache for some queries and second level cache which is most vital.

You can find doctrine cache configurations in [this](config/packages/doctrine.yaml) config file. And in cache pool configuration in [this](config/packages/cache.yaml) file.