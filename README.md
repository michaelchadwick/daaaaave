# daaaaave

Need a simple web API for testing such functionalities? Ask Dave.

## Usage

### daves

`/?api`

returns an array of (10) daves:

``` json
[
  "dave",
  "daave",
  "daaave",
  "daaaave",
  "daaaaave",
  "daaaaaave",
  "daaaaaaave",
  "daaaaaaaave",
  "daaaaaaaaave",
  "daaaaaaaaaave"
]
```

`/?api&daves=5`

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

`/?api&file&type=data&size=10`

returns a 10MB binary file called `10mb`

`/?api&file&type=json`

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
}
```

`/?api&file&type=text&size=100`

returns 100 names as text:

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

## Slack api key

Copy `.env.example` to `.env` and add in Dave's slack token...*if you know it*.
