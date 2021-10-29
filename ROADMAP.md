# Applicaiton Roadmap

## Software Features

### MUST HAVE
--------------
 - create a sweeper cron to delete gotify messages older than X age.
 - voicemail gotify notification

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
- Encrypted database w/ User Key SO... access to DB still can't see everything.
- Purchase numbers & auto-config sip, number and message endpoints.  All user would do would be enter service-account info.
- Self hosted docker-compose setup with: web, app, db, queue-worker, queue-server, gotify
- Inbound / Outbound message handling with Telnyx
- Voicemail call control for Telnyx
- Voicemail recording handling with Telnyx