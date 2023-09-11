# MT Backend Assignment

- Clone the project and run
    > composer install

- To run the DB migration and seed the table, run
    > php artisan migrate --seed

- To view a list of routes, run
    > php artisan route:list

- To run tests
    > php artisan test

- Finally, to start the development server, run
    > php artisan serve

Logs are stored in `storage/logs/requests.log`

Rate limiting is set to 10/min, filtered by request IP

- Content negotiation is available for
    - application/json, application/vnd.api+json
    - application/xml
    - text/csv