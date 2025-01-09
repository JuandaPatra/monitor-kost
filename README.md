# Backend API Documentation

## Overview
This backend API provides functionality for managing and retrieving data related to properties and leads. The API supports endpoints for getting the most chosen properties, lead data over the last 7 days, and lead status distribution.

## Base URL
```
http://localhost:8000/api/statistics
```

---

## API Endpoints

### 1. **GET /properties/most-chosen**
Retrieve a list of properties most chosen by users.

#### Response Format
```json
{
    "status": "success",
    "data": {
        "propertyMostChosen": [
            {
                "property_id": 1,
                "name": "Kost Karley Kovacek",
                "total": 11
            },
            {
                "property_id": 3,
                "name": "Kost Viola Kilback",
                "total": 8
            },
            {
                "property_id": 2,
                "name": "Kost Isabella Cronin PhD",
                "total": 5
            }
        ]
    }
}
```

#### Example Request
```
GET /properties/most-chosen
```

#### Example Curl
```bash
curl -X GET http://localhost:8000/api/statistics/properties/most-chosen
```

---

### 2. **GET /leads/last-7-days**
Retrieve lead data for the last 7 days.

#### Response Format
```json
{
    "status": "success",
    "data": {
        "last7DaysLeads": [
            {
                "date": "2024-12-31",
                "total": 12
            },
            {
                "date": "2025-01-01",
                "total": 12
            },
            {
                "date": "2025-01-02",
                "total": 3
            }
        ]
    }
}
```

#### Example Request
```
GET /leads/last-7-days
```

#### Example Curl
```bash
curl -X GET http://localhost:8000/api/statistics/leads/last-7-days
```

---

### 3. **GET /leads/status-distribution**
Retrieve a breakdown of lead statuses.

#### Response Format
```json
{
    "status": "success",
    "data": {
        "leadsStatusDistribution": [
            {
                "status": "menghubungi pemilik",
                "total": 13
            },
            {
                "status": "menyewa kos",
                "total": 12
            },
            {
                "status": "Tidak memberi feedback",
                "total": 9
            }
        ]
    }
}
```

#### Example Request
```
GET /leads/status-distribution
```

#### Example Curl
```bash
curl -X GET http://localhost:8000/api/statistics/leads/status-distribution
```

---

## Response Status Codes
- **200 OK**: Request was successful.
- **400 Bad Request**: The request was invalid.
- **404 Not Found**: The resource could not be found.
- **500 Internal Server Error**: Server encountered an unexpected condition.

---

## Notes
- Ensure that the API is accessed over HTTPS for security.
- Use proper authentication headers (if applicable) to access endpoints.

---

## License
This API documentation is licensed under [MIT License](LICENSE).
