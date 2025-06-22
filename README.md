# opensimcurrencyserver-dotnet-tests
opensimcurrencyserver Json API test for PHP

Die Json API funktioniert zu 60% - The Json API works 60%

Zum Aktivieren der API müssen beide Einstellungen gesetzt werden: 

To activate the API, both settings must be set:

     ; # ApiKey is the secret key required for API access to the MoneyServer.
     ; # AllowedUser specifies the authorized username for API requests.
     ; # Both values must be set and must match the credentials sent by your external scripts or web clients.
     ApiKey = 123456789
     AllowedUser = myadminuser
