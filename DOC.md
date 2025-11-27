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

| Name        | Type    | Default Value |
| ----------- | ------- | ------------- |
| customer_id | integer | null          |
| search      | string  | null          |
| limit       | integer | 10            |

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

| Name            | Type    | Default Value |
| --------------- | ------- | ------------- |
| customer_id     | integer | required      |
| nama            | string  | required      |
| jenis_kendaraan | string  | required      |
| merk            | string  | required      |
| no_polisi       | string  | required      |
| sn              | string  | null          |

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

| Name            | Type    | Default Value |
| --------------- | ------- | ------------- |
| customer_id     | integer | null          |
| nama            | string  | null          |
| jenis_kendaraan | string  | null          |
| merk            | string  | null          |
| no_polisi       | string  | null          |
| sn              | string  | null          |

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

## Products

### GET /product

Get list of products with optional filters and pagination.

#### Parameters

| Name           | Type    | Default Value       |
| -------------- | ------- | ------------------- |
| jenis          | string  | null (barang, jasa) |
| category_id    | integer | null                |
| distributor_id | integer | null                |
| search         | string  | null                |
| limit          | integer | 10                  |

#### Response

```json
{
    "products": [
        {
            "id": 1,
            "kode": "6743a123456789",
            "jenis": "barang",
            "category_id": 1,
            "nama": "Product 1",
            "barcode": "barcodes/product/6743a123456789.png",
            "distributor_id": 1,
            "harga_pokok": 10000,
            "harga_jual": 15000,
            "stok": 100,
            "satuan": "pcs",
            "created_at": "2025-11-25T00:25:44.000000Z",
            "updated_at": "2025-11-25T00:25:44.000000Z"
        }
    ],
    "total": 1,
    "per_page": 10
}
```

### GET /product/{id}

Get a specific product.

#### Parameters

None

#### Response

```json
{
    "product": {
        "id": 1,
        "kode": "6743a123456789",
        "jenis": "barang",
        "category_id": 1,
        "nama": "Product 1",
        "barcode": "barcodes/product/6743a123456789.png",
        "distributor_id": 1,
        "harga_pokok": 10000,
        "harga_jual": 15000,
        "stok": 100,
        "satuan": "pcs",
        "created_at": "2025-11-25T00:25:44.000000Z",
        "updated_at": "2025-11-25T00:25:44.000000Z"
    }
}
```

### POST /product

Create a new product. Requires admin role.

#### Parameters

| Name           | Type    | Default Value            |
| -------------- | ------- | ------------------------ |
| jenis          | string  | required (barang, jasa)  |
| category_id    | integer | required                 |
| nama           | string  | required                 |
| distributor_id | integer | null                     |
| harga_pokok    | numeric | required if jenis=barang |
| harga_jual     | numeric | required                 |
| stok           | integer | required if jenis=barang |
| satuan         | string  | required if jenis=barang |

#### Response

```json
{
    "product": {
        "id": 1,
        "kode": "6743a123456789",
        "jenis": "barang",
        "category_id": 1,
        "nama": "New Product",
        "barcode": "barcodes/product/6743a123456789.png",
        "distributor_id": 1,
        "harga_pokok": 10000,
        "harga_jual": 15000,
        "stok": 100,
        "satuan": "pcs",
        "created_at": "2025-11-25T00:25:44.000000Z",
        "updated_at": "2025-11-25T00:25:44.000000Z"
    }
}
```

### PUT /product/{id}

Update a product. Requires admin role.

#### Parameters

| Name           | Type    | Default Value       |
| -------------- | ------- | ------------------- |
| jenis          | string  | null (barang, jasa) |
| category_id    | integer | null                |
| nama           | string  | null                |
| distributor_id | integer | null                |
| harga_pokok    | numeric | null                |
| harga_jual     | numeric | null                |
| stok           | integer | null                |
| satuan         | string  | null                |

#### Response

```json
{
    "product": {
        "id": 1,
        "kode": "6743a123456789",
        "jenis": "barang",
        "category_id": 1,
        "nama": "Updated Product",
        "barcode": "barcodes/product/6743a123456789.png",
        "distributor_id": 1,
        "harga_pokok": 10000,
        "harga_jual": 15000,
        "stok": 100,
        "satuan": "pcs",
        "created_at": "2025-11-25T00:25:44.000000Z",
        "updated_at": "2025-11-25T00:25:44.000000Z"
    }
}
```

### DELETE /product/{id}

Delete a product. Requires admin role.

#### Parameters

None

#### Response

```json
{
    "message": "Product deleted successfully"
}
```

## Purchase Orders

### GET /purchase-order

Get list of purchase orders with optional filters and pagination, sorted by transaction_at desc.

#### Parameters

| Name                | Type    | Default Value                        |
| ------------------- | ------- | ------------------------------------ |
| status              | string  | null (pending, completed, cancelled) |
| distributor_id      | integer | null                                 |
| user_id             | integer | null                                 |
| search              | string  | null                                 |
| transaction_at_from | date    | null                                 |
| transaction_at_to   | date    | null                                 |
| limit               | integer | 10                                   |

#### Response

```json
{
    "purchase_orders": [
        {
            "id": 1,
            "kode": "PO-6743a123456789",
            "distributor_id": 1,
            "user_id": 1,
            "ppn": 10,
            "subtotal": 100000,
            "diskon": 0,
            "total": 110000,
            "status": "pending",
            "transaction_at": "2025-11-25T00:00:00.000000Z",
            "created_at": "2025-11-25T00:25:44.000000Z",
            "updated_at": "2025-11-25T00:25:44.000000Z",
            "distributor": {
                "id": 1,
                "nama": "Distributor 1"
            },
            "user": {
                "id": 1,
                "nama": "User 1"
            },
            "details": [
                {
                    "id": 1,
                    "product_id": 1,
                    "harga_pokok": 10000,
                    "jumlah": 10,
                    "ppn": 1000,
                    "diskon": 500,
                    "subtotal": 109500,
                    "product": {
                        "id": 1,
                        "nama": "Product 1"
                    }
                }
            ]
        }
    ],
    "total": 1,
    "per_page": 10
}
```

### GET /purchase-order/{id}

Get a specific purchase order with details.

#### Parameters

None

#### Response

```json
{
    "purchase_order": {
        "id": 1,
        "kode": "PO-6743a123456789",
        "distributor_id": 1,
        "user_id": 1,
        "ppn": 10,
        "subtotal": 100000,
        "diskon": 0,
        "total": 110000,
        "status": "pending",
        "transaction_at": "2025-11-25T00:00:00.000000Z",
        "created_at": "2025-11-25T00:25:44.000000Z",
        "updated_at": "2025-11-25T00:25:44.000000Z",
        "distributor": {
            "id": 1,
            "nama": "Distributor 1"
        },
        "user": {
            "id": 1,
            "nama": "User 1"
        },
        "details": [
            {
                "id": 1,
                "product_id": 1,
                "harga_pokok": 10000,
                "jumlah": 10,
                "ppn": 1000,
                "diskon": 500,
                "subtotal": 109500,
                "product": {
                    "id": 1,
                    "nama": "Product 1"
                }
            }
        ]
    }
}
```

### POST /purchase-order

Create a new purchase order with batch items.

#### Parameters

| Name                 | Type    | Default Value |
| -------------------- | ------- | ------------- |
| distributor_id       | integer | required      |
| user_id              | integer | required      |
| ppn                  | numeric | required      |
| diskon               | numeric | required      |
| transaction_at       | date    | required      |
| items                | array   | required      |
| items.\*.product_id  | integer | required      |
| items.\*.harga_pokok | integer | required      |
| items.\*.jumlah      | integer | required      |
| items.\*.ppn         | numeric | required      |
| items.\*.diskon      | numeric | required      |

#### Response

```json
{
    "message": "Purchase order created successfully"
}
```

### PUT /purchase-order/{id}

Update a purchase order. Items are optional; if provided, existing details will be replaced.

#### Parameters

| Name                 | Type    | Default Value     |
| -------------------- | ------- | ----------------- |
| distributor_id       | integer | null              |
| user_id              | integer | null              |
| ppn                  | numeric | null              |
| diskon               | numeric | null              |
| transaction_at       | date    | null              |
| items                | array   | null              |
| items.\*.product_id  | integer | required if items |
| items.\*.harga_pokok | integer | required if items |
| items.\*.jumlah      | integer | required if items |
| items.\*.ppn         | numeric | required if items |
| items.\*.diskon      | numeric | required if items |

#### Response

```json
{
    "message": "Purchase order updated successfully"
}
```

### PATCH /purchase-order/{id}/status

Update status of a purchase order.

#### Parameters

| Name   | Type   | Default Value                            |
| ------ | ------ | ---------------------------------------- |
| status | string | required (pending, completed, cancelled) |

#### Response

```json
{
    "message": "Purchase order status updated successfully"
}
```

### DELETE /purchase-order/{id}

Delete a purchase order.

#### Parameters

None

#### Response

```json
{
    "message": "Purchase order deleted successfully"
}
```

## Sales Transactions

### GET /sales-transaction

Get list of sales transactions with optional filters and pagination, sorted by transaction_at desc.

#### Parameters

| Name                | Type    | Default Value                        |
| ------------------- | ------- | ------------------------------------ |
| status              | string  | null (pending, completed, cancelled) |
| customer_id         | integer | null                                 |
| user_id             | integer | null                                 |
| search              | string  | null                                 |
| transaction_at_from | date    | null                                 |
| transaction_at_to   | date    | null                                 |
| limit               | integer | 10                                   |

#### Response

```json
{
    "sales_transactions": [
        {
            "id": 1,
            "kode": "ST-6743a123456789",
            "customer_id": 1,
            "user_id": 1,
            "ppn": 15000,
            "subtotal": 150000,
            "diskon": 0,
            "total": 165000,
            "status": "pending",
            "transaction_at": "2025-11-25T00:00:00.000000Z",
            "created_at": "2025-11-25T00:25:44.000000Z",
            "updated_at": "2025-11-25T00:25:44.000000Z",
            "customer": {
                "id": 1,
                "nama": "Customer 1"
            },
            "user": {
                "id": 1,
                "nama": "User 1"
            },
            "details": [
                {
                    "id": 1,
                    "product_id": 1,
                    "harga_pokok": 10000,
                    "harga_jual": 15000,
                    "jumlah": 10,
                    "ppn": 10,
                    "diskon": 5,
                    "subtotal": 150000,
                    "total": 157500,
                    "product": {
                        "id": 1,
                        "nama": "Product 1"
                    }
                }
            ]
        }
    ],
    "total": 1,
    "per_page": 10
}
```

### GET /sales-transaction/{id}

Get a specific sales transaction with details.

#### Parameters

None

#### Response

```json
{
    "sales_transaction": {
        "id": 1,
        "kode": "ST-6743a123456789",
        "customer_id": 1,
        "user_id": 1,
        "ppn": 15000,
        "subtotal": 150000,
        "diskon": 0,
        "total": 165000,
        "status": "pending",
        "transaction_at": "2025-11-25T00:00:00.000000Z",
        "created_at": "2025-11-25T00:25:44.000000Z",
        "updated_at": "2025-11-25T00:25:44.000000Z",
        "customer": {
            "id": 1,
            "nama": "Customer 1"
        },
        "user": {
            "id": 1,
            "nama": "User 1"
        },
        "details": [
            {
                "id": 1,
                "product_id": 1,
                "harga_pokok": 10000,
                "harga_jual": 15000,
                "jumlah": 10,
                "ppn": 10,
                "diskon": 5,
                "subtotal": 150000,
                "total": 157500,
                "product": {
                    "id": 1,
                    "nama": "Product 1"
                }
            }
        ]
    }
}
```

### POST /sales-transaction

Create a new sales transaction with batch items.

#### Parameters

| Name                | Type    | Default Value |
| ------------------- | ------- | ------------- |
| customer_id         | integer | required      |
| ppn                 | numeric | 0             |
| diskon              | numeric | 0             |
| transaction_at      | date    | now           |
| items               | array   | required      |
| items.\*.product_id | integer | required      |
| items.\*.jumlah     | integer | required      |
| items.\*.ppn        | numeric | 0             |
| items.\*.diskon     | numeric | 0             |

#### Response

```json
{
    "message": "Sales transaction created successfully"
}
```

### PUT /sales-transaction/{id}

Update a sales transaction. Existing details will be replaced with new items.

#### Parameters

| Name                | Type    | Default Value |
| ------------------- | ------- | ------------- |
| customer_id         | integer | required      |
| ppn                 | numeric | 0             |
| diskon              | numeric | 0             |
| items               | array   | required      |
| items.\*.product_id | integer | required      |
| items.\*.jumlah     | integer | required      |
| items.\*.ppn        | numeric | 0             |
| items.\*.diskon     | numeric | 0             |

#### Response

```json
{
    "message": "Sales transaction updated successfully"
}
```

### PATCH /sales-transaction/{id}/status

Update status of a sales transaction.

#### Parameters

| Name   | Type   | Default Value                            |
| ------ | ------ | ---------------------------------------- |
| status | string | required (pending, completed, cancelled) |

#### Response

```json
{
    "message": "Sales transaction status updated successfully"
}
```

### DELETE /sales-transaction/{id}

Delete a sales transaction.

#### Parameters

None

#### Response

```json
{
    "message": "Sales transaction deleted successfully"
}
```
