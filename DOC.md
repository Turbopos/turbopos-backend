# TurboPOS Backend API Documentation

## Authentication

### POST /login

Login endpoint to authenticate user and get JWT token.

#### Parameters

| Name     | Type   | Default Value |
| -------- | ------ | ------------- |
| username | string | required      |
| password | string | required      |

#### Response

```json
{
    "token": "jwt_token_here",
    "user": {
        "id": 1,
        "username": "admin",
        "nama": "Administrator",
        "role": "admin",
        "is_admin": true,
        "created_at": "2025-11-23T08:13:07.000000Z",
        "updated_at": "2025-11-23T08:13:07.000000Z"
    }
}
```

### GET /profile

Get authenticated user profile.

#### Parameters

None (uses JWT token from Authorization header)

#### Response

```json
{
    "profile": {
        "id": 1,
        "username": "admin",
        "nama": "Administrator",
        "role": "admin",
        "is_admin": true,
        "created_at": "2025-11-23T08:13:07.000000Z",
        "updated_at": "2025-11-23T08:13:07.000000Z"
    }
}
```

## Categories

### GET /category

Get list of categories with optional search.

#### Parameters

| Name   | Type   | Default Value |
| ------ | ------ | ------------- |
| search | string | null          |

#### Response

```json
{
    "categories": [
        {
            "id": 1,
            "nama": "Category 1",
            "created_at": "2025-11-23T08:13:09.000000Z",
            "updated_at": "2025-11-23T08:13:09.000000Z"
        }
    ]
}
```

### POST /category

Create a new category. Requires admin role.

#### Parameters

| Name | Type   | Default Value |
| ---- | ------ | ------------- |
| nama | string | required      |

#### Response

```json
{
    "category": {
        "id": 1,
        "nama": "New Category",
        "created_at": "2025-11-23T08:13:09.000000Z",
        "updated_at": "2025-11-23T08:13:09.000000Z"
    }
}
```

### PUT /category/{id}

Update a category. Requires admin role.

#### Parameters

| Name | Type   | Default Value |
| ---- | ------ | ------------- |
| nama | string | null          |

#### Response

```json
{
    "category": {
        "id": 1,
        "nama": "Updated Category",
        "created_at": "2025-11-23T08:13:09.000000Z",
        "updated_at": "2025-11-23T08:13:09.000000Z"
    }
}
```

### DELETE /category/{id}

Delete a category. Requires admin role.

#### Parameters

None

#### Response

```json
{
    "message": "Category deleted successfully"
}
```

## Users

### GET /user

Get list of users with optional search and role filter.

#### Parameters

| Name   | Type   | Default Value |
| ------ | ------ | ------------- |
| search | string | null          |
| role   | string | null          |

#### Response

```json
{
    "users": [
        {
            "id": 1,
            "username": "admin",
            "nama": "Administrator",
            "role": "admin",
            "is_admin": true,
            "created_at": "2025-11-23T08:13:07.000000Z",
            "updated_at": "2025-11-23T08:13:07.000000Z"
        }
    ]
}
```

### GET /user/{id}

Get a specific user.

#### Parameters

None

#### Response

```json
{
    "user": {
        "id": 1,
        "username": "admin",
        "nama": "Administrator",
        "role": "admin",
        "is_admin": true,
        "created_at": "2025-11-23T08:13:07.000000Z",
        "updated_at": "2025-11-23T08:13:07.000000Z"
    }
}
```

### POST /user

Create a new user. Requires admin role.

#### Parameters

| Name     | Type   | Default Value                       |
| -------- | ------ | ----------------------------------- |
| username | string | required                            |
| password | string | required                            |
| nama     | string | required                            |
| role     | string | required (admin, mekanik, operator) |

#### Response

```json
{
    "user": {
        "id": 2,
        "username": "newuser",
        "nama": "New User",
        "role": "operator",
        "is_admin": false,
        "created_at": "2025-11-23T08:13:07.000000Z",
        "updated_at": "2025-11-23T08:13:07.000000Z"
    }
}
```

### PUT /user/{id}

Update a user. Requires admin role.

#### Parameters

| Name     | Type   | Default Value                   |
| -------- | ------ | ------------------------------- |
| username | string | null                            |
| password | string | null                            |
| nama     | string | null                            |
| role     | string | null (admin, mekanik, operator) |

#### Response

```json
{
    "user": {
        "id": 2,
        "username": "updateduser",
        "nama": "Updated User",
        "role": "mekanik",
        "is_admin": false,
        "created_at": "2025-11-23T08:13:07.000000Z",
        "updated_at": "2025-11-23T08:13:07.000000Z"
    }
}
```

### DELETE /user/{id}

Delete a user. Requires admin role.

#### Parameters

None

#### Response

```json
{
    "message": "User deleted successfully"
}
```

## Customers

### GET /customer

Get list of customers with optional search and pagination.

#### Parameters

| Name   | Type    | Default Value |
| ------ | ------- | ------------- |
| search | string  | null          |
| limit  | integer | 10            |

#### Response

```json
{
    "customers": [
        {
            "id": 1,
            "nama": "Customer 1",
            "alamat": "Address 1",
            "telepon": "123456789",
            "whatsapp": "987654321",
            "keterangan": "Description",
            "created_at": "2025-11-23T08:13:08.000000Z",
            "updated_at": "2025-11-23T08:13:08.000000Z"
        }
    ],
    "total": 1,
    "per_page": 10
}
```

### GET /customer/{id}

Get a specific customer with transports.

#### Parameters

None

#### Response

```json
{
    "customer": {
        "id": 1,
        "nama": "Customer 1",
        "alamat": "Address 1",
        "telepon": "123456789",
        "whatsapp": "987654321",
        "keterangan": "Description",
        "created_at": "2025-11-23T08:13:08.000000Z",
        "updated_at": "2025-11-23T08:13:08.000000Z",
        "transports": []
    }
}
```

### POST /customer

Create a new customer. Requires admin role.

#### Parameters

| Name       | Type   | Default Value |
| ---------- | ------ | ------------- |
| nama       | string | required      |
| alamat     | string | required      |
| telepon    | string | required      |
| whatsapp   | string | null          |
| keterangan | string | null          |

#### Response

```json
{
    "customer": {
        "id": 1,
        "nama": "New Customer",
        "alamat": "New Address",
        "telepon": "123456789",
        "whatsapp": null,
        "keterangan": null,
        "created_at": "2025-11-23T08:13:08.000000Z",
        "updated_at": "2025-11-23T08:13:08.000000Z"
    }
}
```

### PUT /customer/{id}

Update a customer. Requires admin role.

#### Parameters

| Name       | Type   | Default Value |
| ---------- | ------ | ------------- |
| nama       | string | null          |
| alamat     | string | null          |
| telepon    | string | null          |
| whatsapp   | string | null          |
| keterangan | string | null          |

#### Response

```json
{
    "customer": {
        "id": 1,
        "nama": "Updated Customer",
        "alamat": "Updated Address",
        "telepon": "123456789",
        "whatsapp": null,
        "keterangan": null,
        "created_at": "2025-11-23T08:13:08.000000Z",
        "updated_at": "2025-11-23T08:13:08.000000Z"
    }
}
```

### DELETE /customer/{id}

Delete a customer. Requires admin role.

#### Parameters

None

#### Response

```json
{
    "message": "Customer deleted successfully"
}
```

## Customer Transports

### GET /customer-transport

Get list of customer transports with optional filters and pagination.

#### Parameters

| Name            | Type    | Default Value             |
| --------------- | ------- | ------------------------- |
| customer_id     | integer | null                      |
| jenis_kendaraan | string  | null (mobil, motor, truk) |
| search          | string  | null                      |
| limit           | integer | 10                        |

#### Response

```json
{
    "transports": [
        {
            "id": 1,
            "customer_id": 1,
            "nama": "Transport 1",
            "jenis_kendaraan": "mobil",
            "merk": "Toyota",
            "no_polisi": "B 1234 AB",
            "sn": "SN123456",
            "created_at": "2025-11-23T08:13:08.000000Z",
            "updated_at": "2025-11-23T08:13:08.000000Z"
        }
    ],
    "total": 1,
    "per_page": 10
}
```

### GET /customer-transport/{id}

Get a specific customer transport.

#### Parameters

None

#### Response

```json
{
    "transport": {
        "id": 1,
        "customer_id": 1,
        "nama": "Transport 1",
        "jenis_kendaraan": "mobil",
        "merk": "Toyota",
        "no_polisi": "B 1234 AB",
        "sn": "SN123456",
        "created_at": "2025-11-23T08:13:08.000000Z",
        "updated_at": "2025-11-23T08:13:08.000000Z"
    }
}
```

### POST /customer-transport

Create a new customer transport.

#### Parameters

| Name            | Type    | Default Value                 |
| --------------- | ------- | ----------------------------- |
| customer_id     | integer | required                      |
| nama            | string  | required                      |
| jenis_kendaraan | string  | required (mobil, motor, truk) |
| merk            | string  | required                      |
| no_polisi       | string  | required                      |
| sn              | string  | null                          |

#### Response

```json
{
    "transport": {
        "id": 1,
        "customer_id": 1,
        "nama": "New Transport",
        "jenis_kendaraan": "motor",
        "merk": "Honda",
        "no_polisi": "B 5678 CD",
        "sn": null,
        "created_at": "2025-11-23T08:13:08.000000Z",
        "updated_at": "2025-11-23T08:13:08.000000Z"
    }
}
```

### PUT /customer-transport/{id}

Update a customer transport.

#### Parameters

| Name            | Type    | Default Value             |
| --------------- | ------- | ------------------------- |
| customer_id     | integer | null                      |
| nama            | string  | null                      |
| jenis_kendaraan | string  | null (mobil, motor, truk) |
| merk            | string  | null                      |
| no_polisi       | string  | null                      |
| sn              | string  | null                      |

#### Response

```json
{
    "transport": {
        "id": 1,
        "customer_id": 1,
        "nama": "Updated Transport",
        "jenis_kendaraan": "truk",
        "merk": "Mitsubishi",
        "no_polisi": "B 5678 CD",
        "sn": null,
        "created_at": "2025-11-23T08:13:08.000000Z",
        "updated_at": "2025-11-23T08:13:08.000000Z"
    }
}
```

### DELETE /customer-transport/{id}

Delete a customer transport.

#### Parameters

None

#### Response

```json
{
    "message": "Customer transport deleted successfully"
}
```

## Distributors

### GET /distributor

Get list of distributors with optional search and pagination.

#### Parameters

| Name   | Type    | Default Value |
| ------ | ------- | ------------- |
| search | string  | null          |
| limit  | integer | 10            |

#### Response

```json
{
    "distributors": [
        {
            "id": 1,
            "nama": "Distributor 1",
            "alamat": "Address 1",
            "telepon": "123456789",
            "whatsapp": "987654321",
            "created_at": "2025-11-23T08:13:09.000000Z",
            "updated_at": "2025-11-23T08:13:09.000000Z"
        }
    ],
    "total": 1,
    "per_page": 10
}
```

### GET /distributor/{id}

Get a specific distributor with products.

#### Parameters

None

#### Response

```json
{
    "distributor": {
        "id": 1,
        "nama": "Distributor 1",
        "alamat": "Address 1",
        "telepon": "123456789",
        "whatsapp": "987654321",
        "created_at": "2025-11-23T08:13:09.000000Z",
        "updated_at": "2025-11-23T08:13:09.000000Z",
        "products": []
    }
}
```

### POST /distributor

Create a new distributor. Requires admin role.

#### Parameters

| Name     | Type   | Default Value |
| -------- | ------ | ------------- |
| nama     | string | required      |
| alamat   | string | required      |
| telepon  | string | required      |
| whatsapp | string | null          |

#### Response

```json
{
    "distributor": {
        "id": 1,
        "nama": "New Distributor",
        "alamat": "New Address",
        "telepon": "123456789",
        "whatsapp": null,
        "created_at": "2025-11-23T08:13:09.000000Z",
        "updated_at": "2025-11-23T08:13:09.000000Z"
    }
}
```

### PUT /distributor/{id}

Update a distributor. Requires admin role.

#### Parameters

| Name     | Type   | Default Value |
| -------- | ------ | ------------- |
| nama     | string | null          |
| alamat   | string | null          |
| telepon  | string | null          |
| whatsapp | string | null          |

#### Response

```json
{
    "distributor": {
        "id": 1,
        "nama": "Updated Distributor",
        "alamat": "Updated Address",
        "telepon": "123456789",
        "whatsapp": null,
        "created_at": "2025-11-23T08:13:09.000000Z",
        "updated_at": "2025-11-23T08:13:09.000000Z"
    }
}
```

### DELETE /distributor/{id}

Delete a distributor. Requires admin role.

#### Parameters

None

#### Response

```json
{
    "message": "Distributor deleted successfully"
}
```
