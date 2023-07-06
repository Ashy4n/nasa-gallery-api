# NASA Gallery API

This Symfony application provides a REST API that returns JSON containing holiday information and related photos taken on specific dates or date ranges.
The application integrates with the NASA Mars Rover Photos API to fetch and store the images.
And https://date.nager.at API to fetch and store the holidays.

## Prerequisites

Before using this application, make sure you have the following:

- PHP 8.1 or higher installed on your system.
- Composer installed to manage PHP dependencies.
- An API key from NASA's API. You can sign up for a free API key at https://api.nasa.gov/index.html#apply-for-an-api-key.


## Installation

Clone the repository:

```bash
git clone https://github.com/Ashy4n/nasa-gallery-api
```

Navigate to the project directory:

```bash
cd ./nasa-gallery-api
```
Install the dependencies:
```bash
composer install
```
## Set up the database
You can configure your database credentials in the .env file.

#### Crate docker database:
```bash
docker compose up
```
#### Get migrations:
```bash
symfony conosle doctrine:migrations:migrate
```
## Loading data

You can get all data using this command or use separate [Commands](#Commands) to get specific data.
You can specify country and year options to get more specific data.
```bash
symfony conosle app:get-defaults -y 2022 -c PL
```

## Usage

Start the Symfony development server:

```bash
symfony serve
``` 

The API will be accessible at http://localhost:8000.

## API  Endpoints
### Endpoint 1: Get Holiday Photos

This endpoint allows you to retrieve a JSON response containing photos taken during holidays occurring on a defined date or date range.

    URL: /api/photos
    Method: GET
    Parameters:
        data (optional): Filter photos by date (YYYY-MM-DD).
        start_date (optional): The start date of the date range (format: YYYY-MM-DD).
        end_date (optional): The end date of the date range (format: YYYY-MM-DD).
        rover (optional): Filter photos by rover (curiosity, opportunity, spirit).
        camera (optional): Filter photos by camera (fhaz, rhaz).
        

If no parameters are provided, the endpoint will return photos from all holidays.
### Endpoint 2: Get Photo Details

This endpoint allows you to retrieve detailed information for a single photo.

    URL: /api/photos/{photoId}
    Method: GET
    Parameters:
        photoId: The ID of the photo.

### Example Requests

Get holiday photos for a specific date range:

```
/photos?start_date=2021-12-24&end_date=2021-12-31
```

Get holiday photos for a specific rover and camera:

```
/photos?rover=curiosity&camera=fhaz
```


Get details of a photo:

```
/photos/123
```
## Commands

This application provides the following console commands:

#### Load Polish holidays data:

This command fetches and saves the list of holidays to the database.
You can specify country and year arguments to get more specific data.
Default values are set to get data for Polish holidays in 2022.
```bash
symfony conosle app:get-holidays
```

#### Load Polish NASA Rovers and cameras data:
This command fetches and saves the list of rovers and cameras available by NASA api.
```bash
symfony conosle app:get-rovers
```

#### Load Polish NASA Photos data:
This command fetches and saves photos from Nasa API for all holidays that are in database.
You need to have rovers and cameras data in database to use this command.
```bash
symfony conosle app:get-photos
```