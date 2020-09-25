## Introduction

This is a simple Auth Service based on Laravel.
 
 Token is generated in a json response from the Register, Login or Renew Token endpoint.

After token is generated, it is used in the Header for protected requests. See below

Headers for endpoints that requires token
 ```json
Authorization: Bearer  {API Token Generated}
Accepts: application/json
 ```

## Endpoints

There are 4 API Endpoints

| Route Name  | Endpoint | Type | Details  |
| ------------- | ------------- | ------------- |------------- |
| [Register](#register)  | /api/register  | POST | Unprotected |
| Login  | /api/login   | POST | Unprotected |
| Renew Token  | /api/renew-token  | GET | Requires Token |
| User Data  | /api/user-data  | GET | Requires Token |


Download this [Postman Collection file](https://github.com/timoye/patritia) of all requests
  
# Register

Register endpoint accepts 3 parameters
 ```json
name | required
email | unique to a user and required
password | required
 ```
Successful Register response 
 ```json
Status code 200
{
    "status": "success",
    "message": "Successfully Registered",
    "token": "1|yba3MVcRCFmQ2CaEnikKkuXoiXaBMuzNv1UaZiZe"
}
 ```
 
Unsuccessful Register response 
 ```json
Status code 200
{
    "message": "The given data was invalid.",
    "errors": {
        "name": [
            "The name field is required."
        ],
        "email": [
            "The email has already been taken."
        ]
    }
}
OR 
{
    "status": "fail",
    "message": "Something went wrong"
}
 ```  
## Login

Login endpoint accepts 2 parameters
 ```json
email | required
password | required
 ```
Successful Login response 
 ```json
Status code 200
{
    "status": "success",
    "message": "Successfully Authenticated",
    "token": "4|Fz4qLAbXpAnlSy6wd7YwWCDvypCUftVc629fqYP8"
}
 ```
 
Unsuccessful Login response 
 ```json
Status code 403
{
    "status": "fail",
    "message": "unauthenticated"
}
 ```
 
## Renew Token

Renew Token endpoint only requires the Header Authorization parameters
 ```json
Authorization: Bearer  {API Token Generated}
 ```
Successful Renew Token response 
 ```json
Status code 200
{
    "status": "success",
    "message": "Successfully Renewed Token",
    "token": "6|xqY7kJVnRUhRm9b4P9rKmTEnXvC8U98QTzLJcWCK"
}
 ```
 
Unsuccessful Renew Token response 
 ```json
Status code 200
{
    "status": "fail",
    "message": "Something went wrong"
}
 ```
 
## User Data

User Data endpoint only requires the Header Authorization parameters
 ```json
Authorization: Bearer  {API Token Generated}
 ```
Successful User Data response 
 ```json
Status code 200
{
    "id": 1,
    "name": "Tim",
    "email": "tim@gmail.com",
    "email_verified_at": null,
    "created_at": "2020-09-25T02:26:55.000000Z",
    "updated_at": "2020-09-25T02:26:55.000000Z"
}
 ```
 
Unsuccessful User Data response 
 ```json
Status code 200
{
    "status": "fail",
    "message": "Something went wrong"
}
 ```