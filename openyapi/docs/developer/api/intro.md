Welcome to Openy API Documentation 
---------------------------------

1. Endpoint are singulars. Except for those whose respond lists of things and only implements GET
2. Any call without changes in the state machine (repository) must be via GET
3. A response of 1 = present or true, 0 = not present or false, null = imposible to determine


Forms to call API
-----------------

#HTTP Header
Pass the API key into the X-Api-Key header:

	curl -H 'X-Api-Key: DEMO_KEY' 'https://api.data.gov/nrel/alt-fuel-stations/v1.json?limit=1'

#GET Query Parameter_
Pass the API key into the api_key GET query string parameter:

	curl 'https://api.data.gov/nrel/alt-fuel-stations/v1.json?limit=1&api_key=YOUR_KEY_HERE'

Note: The GET query parameter may be used for non-GET requests (such as POST and PUT).

#HTTP Basic Auth Username
As an alternative, pass the API key as the username (with an empty password) using HTTP basic authentication:

	curl 'https://YOUR_KEY_HERE@api.data.gov/nrel/alt-fuel-stations/v1.json?limit=1'