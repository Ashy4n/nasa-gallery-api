# NASA Gallery API

This Symfony application provides a REST API that returns JSON containing holiday information and related photos taken on specific dates or date ranges.
The application integrates with the NASA Mars Rover Photos API to fetch and store the images.
And holidayapi.com API to fetch and store the holidays.

## Prerequisites

Before using this application, make sure you have the following:

- PHP 8.1 or higher installed on your system.
- Composer installed to manage PHP dependencies.
- An API key from NASA's API. You can sign up for a free API key at https://api.nasa.gov/index.html#apply-for-an-api-key.
- An API key from Holiday API. You can sign up for a free API key at https://holidayapi.com/.


## Installation

Clone the repository:

```bash
git clone https://github.com/your-username/holiday-photos-api.git
```

Navigate to the project directory:

```bash
cd ./nasa-gallery-api
```
Install the dependencies:
```bash
composer install
```


[//]: # (Configure the NASA API key:)

[//]: # ()
[//]: # (    Copy the .env file:)

[//]: # ()
[//]: # (    bash)

[//]: # ()
[//]: # (    cp .env.example .env)

[//]: # ()
[//]: # (    Open the .env file and set the value of NASA_API_KEY to your NASA API key.)

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

You can get all data using console command 
```bash
symfony conosle app:get-defaults
```
or seperate [Commands](#Commands) section.

#### Load Polish holidays data:
```bash
symfony conosle doctrine:migrations:migrate
```

#### Load NASA Mars Rover data:
```bash
symfony conosle app:default-setup
```

#### Load Polish holidays data:

```bash
symfony conosle app:holidays:load
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
/photo/123
```
## Commands

This application provides the following console commands:
app:holidays:load

This command fetches and saves the list of Polish holidays in 2021 to the database. You can use this command to refresh the holiday data.

bash

php bin/console app:holidays:load

app:photos:load

This command fetches and saves the Mars Rover photos for the holidays in 2021 from the NASA API to the database. You can use this command to refresh the photo data.

bash

php bin/console app:photos:load