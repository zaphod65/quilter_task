### Set Up

Implemented in PHP 8.3
run `composer install`
run `npm install && npm run build`
run `php artisan key:gen`
run `php artisan passport:install`

#### Assumptions
The primary assumption of the API is that balances and transaction amounts should be in pennies/cents rather than fractional values of pounds/dollars/euros.  This is to avoid any rounding or floating point errors.

#### Further improvements
There are some improvements that can be made:
* use of resource classes for formatting returned objects
* use of https://spatie.be/docs/laravel-query-builder/v6/introduction to allow graceful use of includes for relations
* addition of filtering to list APIs (accounts and transactions)
* Some code refactors, mainly around graceful error handling and routes that could be better nested.
* Expansion of tests:
  * unit tests are not implemented here for most functionality, due to time constraints and issues with unit testing with Passport/OAuth
  * Tests included here use the PEST framework and to display understanding of basic principles