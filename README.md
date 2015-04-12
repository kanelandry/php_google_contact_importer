# php_google_contact_importer
Import Google/GMail account's owner and contacts using OAuth 2.0, Google Contacts Data API 3.0 and PHP. 
The code has been enhanced to import the email and full name of the account's owner.
Its advantage over other existing contact importers is fully detailed in this LinkedIn article: http://www.linkedin.com/pulse/retrieving-account-owner-info-google-contacts-via-oauth2-kouajiep.

Requirements:
- php 5.+
- curl
- Google API access (follow the step as described in 25Labs (2)
- Google service "Contacts API" enabled

References:

[1] https://developers.google.com/identity/protocols/OAuth2
[2] http://25labs.com/import-gmail-or-google-contacts-using-google-contacts-data-api-3-0-and-oauth-2-0-in-php/
