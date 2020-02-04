# Toodoo 

Creation of a simple todo app (called Toodoo) that will help multiple users quickly collaborate on the same tasks.

This repository provides only the frontend functionality of the app, a RESTful API still needs to be created. 

## Missing Endpoints

The following endpoints are being used by the frontend app and need to be implemented. 

### Create Todo List  

##### URL 

POST - `api.php/lists`

##### PAYLOAD

```
{
	"name": "Todo List Name", 
	"participants": [
		"participant1@example.org",
		"participant2@example.org",
		"participant3@example.org"
	]
}
```


### Delete Todo List 

##### URL 

DELETE - `api.php/lists/:list-id`

##### PAYLOAD

*None*


### Read Todo List Items

##### URL 

GET - `api.php/lists/:list-id/todos`

##### PAYLOAD

*None*

### Create Todo Item 
 
##### URL 

POST - `api.php/lists/:list-id/todos`

##### PAYLOAD

```
{
	"id": "5270138f-865b-4835-976e-6ff132ee3fab",
	"title": "Sample Todo Item",
	"completed": false
}
```
 
### Update Todo Item

##### URL 

PUT - `api.php/lists/:list-id/todos/:item-id`

##### PAYLOAD

```
{
	"id": "5270138f-865b-4835-976e-6ff132ee3fab",
	"title": "Sample Todo Item",
	"completed": false
}
```

### Mark Multiple Todo Items As Completed/Pending

PATCH - `api.php/lists/:list-id/todos`

##### PAYLOAD

```
{
	"completed": false
}
```

### Delete Todo Item 

DELETE - `api.php/lists/:list-id/todos/:item-id`

##### PAYLOAD

*None* 

### Delete Completed Todo Items 

DELETE - `api.php/lists/:list-id/todos?completed`

##### PAYLOAD

*None* 
