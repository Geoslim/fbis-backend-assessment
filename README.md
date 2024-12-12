
## Airtime Vending System API
This is an Airtime Vending Service built using Laravel, providing an API endpoint for vending airtime. The service handles wallet balances, transactions, and interactions with external vending partners.

## Features

- Vending Airtime through a simple API.
- User Created with Wallet with default balance.
- Transaction recording and management.
- Error handling and logging.
- Supports multiple vending partners (SHAGGO, BAP).

## Prerequisites

- PHP v8.3
- (Used SQLite for this implementation. can be placed in the database directory as `database.sqlite`) but MySQL or any compatible database is allowed 
- Redis (if available) for background jobs. (you can set QUEUE_CONNECTION=sync as well)

## Start up

To start project, perform the following steps in the order
```bash
- Clone the repository by running the command
- git clone 'https://github.com/Geoslim/fbis-backend-assessment.git'
- cd fbis-backend-assessment
- Run `composer install`
- Run `cp .env.example .env`
- Fill your configuration settings in the '.env' file you created above
- Create database
- Run `php artisan key:generate`
- Run `php artisan migrate --seed`
- Run `php artisan serve`
```

## Configuration
### Vending Partner
The default vending partner is defined in the ```config/vending.php``` file. It can be set to the 
available partner of choice. The file also allows you configure the minimum and maximum airtime amount
```bash
'partner' => VendingPartners::BAP->value,  // Set to SHAGGO or BAP
```

### Commission
The commission calculation mode is defined in ```config/commission.php```.
```bash
- mode: 'percentage' or 'flat'
- rate: A percentage if the mode is set to 'percentage'.
- flat_rate: A flat commission if the mode is set to 'flat'.
```

### Wallet
The default balance for new users is defined in ```config/wallet.php```.
```bash
- 'default_balance' => 1000
```

## API Usage (Showing only major endpoints)
Application utilizes Laravel Sanctum for authentication. So make use of the token generated at sign up / login.
- Include token as bearer token
- Make use of the following endpoints to test the application

Base url = ```http://127.0.0.1:8000```

Sign Up : POST
```bash
{{baseUrl}}/api/v1/auth/register
````
```json
{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "password": "Secret@123"
}
```

Login : POST
```bash
{{baseUrl}}/api/v1/auth/login
````
```json
{
    "email": "john@example.com",
    "password": "Secret@123"
}
```

Fetch Wallet Balance : GET
```bash
{{baseUrl}}/api/v1/wallet
Accept: application/json
Authorization: ••••••
```

Vend Airtime : POST
```bash
{{baseUrl}}/api/v1/transactions/vend-airtime
Accept: application/json
Authorization: ••••••
````
```json
{
    "recipient": "07030100000",
    "amount": "500",
    "network" : "GLO"
}
```
