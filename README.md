# daaaaave

Need a simple web API for testing such functionalities? Ask Dave.

## Usage

### Base case

`/`

returns JSON asking for more details

``` json
{
  "body": null,
  "customType": "server",
  "error": true,
  "message": "Dave says: I think you forgot to ask for something.",
  "status": "204",
  "statusText": "OK"
}
```

### Daves

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

`/?binary&size=10`

returns a 10 MB binary file called `10mb_of_dave`

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
      "word": "hourglass"
      "name": "Elsie"
    },
    ...
  ]
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

### Slack api key

Copy `.env.example` to `.env` and add in Dave's slack token...*if you know it*.
