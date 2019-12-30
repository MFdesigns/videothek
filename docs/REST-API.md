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
phone         int
street        string
streetNumber  string
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
```

#### Delete

```
DELETE .../api/customers/{id}
```
