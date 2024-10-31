# daaaaave

Need a simple web API for testing such functionalities? Ask Dave.

## Usage

### Base case

`/`

returns HTML politely repudiating you for forgetting to ask for anything.

### Dave(s)

`/?dave`

returns a simple question: "dave?"

`/?daves=5`

returns an array of (5) daves as JSON:

``` json
{
  "body": [
    "dave",
    "daave",
    "daaave",
    "daaaave",
    "daaaaave"
  ],
  "customType": "server",
  "error": true,
  "message": "",
  "status": "204",
  "statusText": "OK"
}
```

### Files

#### Binary

`/?binary&size=2`

returns a 2 MB binary file called `2mb_of_dave`

#### JSON

`/?json&size=8`

returns an array of (8) random items as JSON:

``` json
{
  "items": [
    {
      "seq": 1,
      "count": "0001",
      "integer": 40,
      "float": 12.041,
      "string": "fc93iefsads",
      "hex": "33ccc3a01108aa01100f2ee5cc0a0ad8",
      "uuid": "08438494-b500-40ef-9607-68fc08f0ba8a",
      "bool": true,
      "word": "hourglass",
      "name": "Elsie"
    },
    ...
  ]
}
```

#### Config

`/?config`

returns a fake configuration JSON object:

``` json
{
  "type": "form",
  "locale": "en",
  "apiVersion": "v1.10",
  "appVersion": "2.251.4",
  "maxUploadSize": 256000000,
  "searchEnabled": true,
  "trackingEnabled": false,
  "userSearchType": "local"
}
```

#### Text

`/?text&size=100`

returns 100 random names as text:

``` text
Cestz
Trind
Abaele
Gusphil
Dorinerus
Thideda
Marcker
...
```

#### Version

`/?version`

returns a random app version, with major, minor, and patch segments, as a string:

``` text
1.40.233
```

### HTTP Codes

`/?http_code=404`

returns an HTTP response as JSON with the chosen code and an appropriate message:

``` json
{
  "body": null,
  "customType": "server",
  "error": true,
  "message": "Dave says: I'm not here, man.",
  "status": 404,
  "statusText": "OK"
}
```

### Slack API key

Copy `.env.example` to `.env` and add in Dave's slack token...*if you know it*.
