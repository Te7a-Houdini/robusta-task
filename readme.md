## About App

a simple app that manages employees salaries and bonuses built with laravel 5.7 & passport package for api authentication

## Installation

- clone the repo and run the following commands
- composer install
- cp .env.example .env
- configure the database driver & mail driver in .env file
- php artisan key:generate
- php artisan migrate --seed
- php artisan passport:install


## Api Endpoints

```javascript

/api/salaries-to-be-paid GET (Accepts filter[month] & filter[date] as parameters)

[
    {
        "Month": "Jan",
        "Salaries_payment_day": "31",
        "Bonus_payment_day": "15",
        "salaries_total": 107211,
        "bonus_total": 10721,
        "payments_total": 117932
    },
]


/api/employees GET

{
    "data": [
        {
            "id": 2,
            "name": "Stephon Harber",
            "email": "chowell@example.org",
            "salary": "10000",
            "bonus_percentage": "10"
        },
    ]
}


/api/employees POST (to create an employee)

email required
password required
password_confirmation required
name required
salary required


/api/employees/{id}/bonus-percentage PUT (to update an employee bonus percentage)

bonus_percentage required


```