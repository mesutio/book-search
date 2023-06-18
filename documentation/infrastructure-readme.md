## Installation
You can type following command to up and run for first time:

`make build-environment`

That command start to download docker images for building them and install application to get ready to 'up' it.

Also, you can remove all environment to rebuild it if you need with this command:

`make remove-environment`

## Up & Run the Application
If you already installed the app you can start and stop it via these commands:

- `make up-environment`
- `make stop-environment`

Then you can see the endpoints here: `http://127.0.0.1:8001/api-doc`

## Coding Standard and Static Analyse tools
There are several code style and static analyse tools available in this application.

### PHP CS-Fixer
This tool fixes coding standard issues in the codebase. Following command is to run coding style checker:

`make code-fixer-fix`

**Note**: This command added as git hook to system and its automatically checking and fixing at every commit.

#### PHPStan
PHPStan focuses to find errors and code smelling without running application.

`make phpstan-check`