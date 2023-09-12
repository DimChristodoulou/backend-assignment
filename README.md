# MT Backend Assignment

# Setting up the project locally

- Clone the project and run
    > composer install

- After this, create a `.env` file at the root of the project and copy the contents of `.env.example` into it.
  -  The example env contains values to configure the DB.

- To run the DB migration and seed the table, run
    > php artisan migrate --seed

- To view a list of routes, run
    > php artisan route:list

- To run tests
    > php artisan test

- Finally, to start the development server, run
    > php artisan serve

# Setting up the project via docker and sail

Container support is provided via sail. To run the project via sail:

- Clone the project and run
    > composer install

- After this, create a `.env` file at the root of the project and copy the contents of `.env.example` into it.
  - The example env contains values to configure the DB.

- Run `sail up` (for UNIX systems) or `bash ./vendor/laravel/sail/bin/sail up` for Windows.

You now have 2 options: either run the server and DB operations from the container terminal or via sail:

- To run the DB migration and seed the table, you now run:
   > sail artisan migrate --seed (for sail)
   > php artisan migrate --seed (for container terminal)

- To run tests
    > sail artisan test (for sail)
    > php artisan test (for container terminal)

- To start the development server, run
    > sail artisan serve (for sail)
    > php artisan serve (for container terminal)


Please note that I am working in a Windows system with WSL and Docker Desktop, so the above is tested for Windows.

# Architecture

The main endpoint logic is located in `app\Http\Controllers\ShipController.php`.
The DB entity for the ship track data is located in `app\Models\Ship.php`.
The DB seeder is located in `database\seeders\ShipSeeder.php`.
The API routes are defined in `routes\api.php`.
Logs are stored in `storage/logs/requests.log`.
Feature tests are located in `tests\Feature\ShipControllerTest.php`.

Rate limiting is set to 10/min, filtered by request IP.

# API

The API runs by default in port 8000.

- Content negotiation is available for
    - application/json, application/vnd.api+json
    - application/xml
    - text/csv

- The application accepts strict routes
  - *Get by MMSI*: http://127.0.0.1:8000/api/ships/311040700,247039300
  - *Get by Latitude*: http://127.0.0.1:8000/api/ships/latStart:XY.Z,latEnd:XY.Z
  - *Get by Longitude*: http://127.0.0.1:8000/api/ships/lonStart:XY.Z,lonEnd:XY.Z
  - *Get by time interval*: http://127.0.0.1:8000/api/ships/from:{datetime},to:{datetime}

- The application also contains a generic route
  - http://127.0.0.1:8000/api/ships
  
Valid request parameters for the generic route are:
- mmsi
- latStart
- latEnd
- lonStart
- lonEnd
- from
- to

Furthermore, all requests accept pagination, when the Accepts header is
- application/json
- application/vnd.api+json
- application/xml


Finally, caching was added as a PoC for the *Get by MMSI* endpoint for text/* content-types.