# REST API Documentation

## Routes

### Root

```
.../api/
```

### Customers

#### List

List all customers

```
GET .../api/customers
```

Optional URL parameters

```
order     = id|name|surname
direction = asc|desc

Example:
.../api/customers?order=id&direction=asc
```

Get single customer
```
GET .../api/customers/{id}
```

#### Search

```
GET .../api/customers/search?keyword=a
```

Optional URL parameters

```
order     = id|name|surname
direction = asc|desc

Example:
.../api/customers?order=id&direction=asc
```

#### Create

Returns JSON:
```json
{
  "id": "11",
  "href": "http://videothek.test/api/customers/11"
}
```

```
POST .../api/customers
```

Required body parameters

```
title         Herr|Frau
name          string
surname       string
birthday      YYYY-MM-DD
phone         string
street        string
streetNumber  string
onrp          number
```

#### Update

```
PUT .../api/customers/{id}
```

Required body parameters

```
title         Herr|Frau
name          string
surname       string
birthday      YYYY-MM-DD
phone         int
street        string
streetNumber  string
onrp          number
```

#### Delete

```
DELETE .../api/customers/{id}
```

### Lendings

#### List lendings from customer

```
GET .../api/lendings/customer/{customer id}
```

#### Delete lending

```
DELETE .../api/lendings/{id}
```

#### Create lending

```
POST .../api/lendings
```

Required body parameters

```json
{
	"custId": "1",
	"vidId": "1",
	"from": "2019-12-26",
	"until": "2019-12-28"
}
```

Response

```json
{
    "id": "1",
    "href": "http://videothek.test/api/lendings/1"
}
```

### Places

#### Get all places by PLZ

```
GET .../api/places/plz/{plz}
```

Response

```json
[
    {
        "onrp": "4805",
        "plz": "8500",
        "city": "Frauenfeld"
    },
    {
        "onrp": "4807",
        "plz": "8500",
        "city": "Gerlikon"
    }
]
```
