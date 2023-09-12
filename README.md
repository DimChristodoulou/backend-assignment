# MT Backend Assignment

- Clone the project and run
    > composer install

- After this, create a `.env` file at the root of the project and copy the contents of `.env.example` into it.

- To run the DB migration and seed the table, run
    > php artisan migrate --seed

- To view a list of routes, run
    > php artisan route:list

- To run tests
    > php artisan test

- Finally, to start the development server, run
    > php artisan serve

Logs are stored in `storage/logs/requests.log`

Rate limiting is set to 10/min, filtered by request IP.

The API runs by default at in port 8000

- Content negotiation is available for
    - application/json, application/vnd.api+json
    - application/xml
    - text/csv

- The application accepts strict routes
  - Get by MMSI: http://127.0.0.1:8000/api/ships/311040700,247039300
  - Get by Latitude: http://127.0.0.1:8000/api/ships/latStart:XY.Z,latEnd:XY.Z
  - Get by Longitude: http://127.0.0.1:8000/api/ships/lonStart:XY.Z,lonEnd:XY.Z
  - Get by time interval: http://127.0.0.1:8000/api/ships/from:{datetime},to:{datetime}

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


Finally, responses are cached for a period of one week