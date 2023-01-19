This is main accounts hub for all of my other projects to keep users data in one main place.

## Commands
- application:create - creates new application
- application:regenerate - regenerates application's token
- application:revoke - revokes token for application
- application:remove - revokes token and removes application

## Api Routes:
### Users:
- `GET /api/users` - returns list of users. Supported query parameters: 
  - `query` search string (searching in email and name), 
  - `page` page number for pagination,
  - `limit` number of rows returned
- `POST /api/users` - creates new user
- `GET /api/users/{id}` - returns user by id
- `PUT /api/users/{id}` - updates user
- `DELETE /api/users/{id}` - deletes user
