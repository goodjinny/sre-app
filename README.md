# Simple rule engine

Provides an API for interaction with [Debricked](https://debricked.com/). 

## Installation
1. ``docker-compose up -d``
   
## Using
1. Set [Debricked credentials](https://debricked.com/docs/integrations/api.html#introduction) in ``.env.local`` configuration file
2. Run [Postman](https://www.postman.com/) and import [SRE Collection](./SRE.postman_collection.json) to it
3. Change ``files``, ``repositoryName`` and ``commitName`` parameters in Postman ``Upload new file`` query according to your settings.
4. Run ``Upload new file`` query and obtain an ``uploadId`` as a query result
5. Run ``Get upload status`` query for getting a result of previous upload processing

## Backlog
- create **RedisSession** service and use it to store JWT tokens (for running queue messages asynchronously in CLI)
- create **CheckFilesUpload** command for checking of not uploaded files (to conclude fileUpload sessions)   
- setup running **CheckFilesUpload** command via crontab
- create **GetUploadStatus** command and run it via crontab  
- create **SendNotification** queue message/handler to notify a user about found vulnerabilities (in **GetUploadStatus** command)
- add validation for [UploadNewFile](./src/Controller/API/V10/UploadNewFileAction.php) endpoint
- create API docs for [application endpoints](./src/Controller/API/V10/)


 