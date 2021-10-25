# Simple rule engine

Provides an API for interaction with [Debricked](https://debricked.com/). 

## Installation
1. ``docker-compose up -d`` 
2. ``docker-compose exec php composer install``
3. Add next tasks to system crontab:
 - ``* * * * * docker exec sre-php bin/console app:file-upload:check >/dev/null 2>&1``
 - ``* * * * * docker exec sre-php bin/console app:file-upload:status >/dev/null 2>&1``

## Using
1. Set [Debricked credentials](https://debricked.com/docs/integrations/api.html#introduction) in ``.env.local`` configuration file
2. Run [Postman](https://www.postman.com/) and import [SRE Collection](./SRE.postman_collection.json) to it
3. Change ``files``, ``repositoryName`` and ``commitName`` parameters in Postman ``Upload new file`` query according to your settings.
4. Run ``Upload new file`` query and obtain an ``uploadId`` as a query result
5. Your upload will be processed by application

## Backlog
- create **SendNotification** queue message/handler to notify a user about found vulnerabilities (in **GetUploadStatus** command)
- add validation for [UploadNewFile](./src/Controller/API/V10/UploadNewFileAction.php) endpoint
- create API docs for [application endpoints](./src/Controller/API/V10/)


 