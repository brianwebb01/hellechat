# Applicaiton Roadmap

## Software Features

### MUST HAVE
--------------
 - Beta deploy
    - configure domain & letsencrypt cert
    - configure deployment pipeline
    - configure container-rotation script
 - create a sweeper cron to delete gotify messages older than X age (should have been delivered to clients by then, no need to store on gotify server).
 - voicemail gotify notification
 - ability to select a number in messages and voicemail UI, then filter results to only show related data.

### SHOULD HAVE
---------------
- mysql post-create script to init gotify db & user perms.
- UI tests
- wire up infinite pagination on serviceAccounts, numbers
- show 'active' thread in messages ui w/ 'currentThread' setter or click, activating
- post account creation dashboard directions (goes away after some event)
- Contact Import

### COULD HAVE
--------------
- Number could have custom voicemail text or recording
- Observers / queued jobs to delete records from provider after ingestion. (means we have to store files locally)

### WOULD LIKE TO HAVE
---------------------
- Encrypted database w/ User defined key, so with terminal access one still can't see everything.
- Purchase numbers & auto-config sip, number and message endpoints.  All user would do would be enter service-account info.
- Self hosted docker-compose setup with: web, app, db, queue-worker, queue-server, gotify
- Inbound / Outbound message handling with Telnyx
- Voicemail call control for Telnyx
- Voicemail recording handling with Telnyx