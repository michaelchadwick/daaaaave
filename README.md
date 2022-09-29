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

returns an array of (5) daves:

``` json
[
  "dave",
  "daave",
  "daaave",
  "daaaave",
  "daaaaave"
]
```

### Files

#### Binary

`/?file&type=binary&size=10`

returns a 10 MB binary file called `10mb_of_dave`

#### JSON

`/?file&type=json`

returns random json data:

``` json
{
  "items": [
    {
      "index": 1,
      "index_start_at": 56,
      "integer": 40,
      "float": 12.041,
      "name": "Elsie",
      "surname": "Horn",
      "fullname": "Hazel Petersen",
      "email": "dana@o.il",
      "bool": true
    },
    ...
  ]
}
```

#### Text

`/?file&type=text&size=100`

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

returns an HTTP response with the chosen code and an appropriate message:

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
