# Simple API

Make sure that the Database connection already established.
All environment variable may see on [sample.env](https://github.com/taliffsss/Symfony-test/blob/master/sample.env "sample.env") or rename it to **.env**

Install all dependencies:

    composer install

Usage using docker compose:
UP: `docker-compose up --build -d`
DOWN: `docker-compose down`
Note: Default port is **8000**, its up to you weather you want to change it or not, in changing it **Dockerfile** and **docker-compose.yml** file, if you changed the port please also mapped it on our Frontend https://github.com/taliffsss/commpeak-csv-upload-form,
This API has ability to send data in real-time using Pusher libraries, it worked once the csv data successfully insert in database it automatically trigger an event to auto refetch data in Front-End.
