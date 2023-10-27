## How to make app working

- Clone the repo using `git clone https://github.com/OmarAshour207/casita.git`.
- Run `cd /casita` then `composer install`.
- Make copy from .env.example file called .env .
- Make new database and put the credentials in .env file.
- Run `php artisan migrate --seed`.
- Congrats it works.

# There are many endpoints for countries.

## Index countries endpoint GET: /api/countries/index

Getting all the countries and the logs also

## Store Countries endpoint POST: /api/countries/store

There are required data.

- `name_ar`, `name_en`, `description_ar`, `description_en`

## Update Countries endpoint POST: /api/countries/update/{id}

There are required data.

- `name_ar`, `name_en`, `description_ar`, `description_en`

## Delete Countries endpoint POST: /api/countries/delete/{id}

### Note: in create and update send the changes to webhook URL

## get Countries and save webhook endpoint GET: /api/soap?callback_url="http://example.com"


## Api collection link
https://api.postman.com/collections/8536121-9403f409-c3a5-422a-8b2a-c82deb1088aa?access_key=PMAT-01HDQ8TMJJQ5BGY532XKZ6JBDZ
