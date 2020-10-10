---
tags: [Errors]
---

# Errors

This API uses conventional HTTP response codes to indicate the success or failure of a request. Codes in the `2xx` range indicate success, codes in the `4xx` range indicate a validation error (e.g., a required parameter was omitted) and codes in the `5xx` range indicate an error with the service internal programming (these should be very rare). Check the table below for a list of possible status codes and their meaning:

| Code | Status               | Description                                                                    |
| :--- | :------------------- | :----------------------------------------------------------------------------- |
| 200  | OK                   | Everything worked as expected.                                                 |
| 401  | Unauthorized         | No valid authorization header value provided.                                  |
| 403  | Forbidden            | The supplied authorization key doesn't have permission to perform the request. |
| 404  | Not Found            | The requested resource doesn't exist.                                          |
| 422  | Unprocessable Entity | The request was unacceptable, often due to missing a required parameter.       |
| 429  | Too Many Requests    | Too many requests hit the API too quickly.                                     |
| 5xx  | Server Errors        | Something went wrong on the internal service programming.                      |
