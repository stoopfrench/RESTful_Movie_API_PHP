# RESTful Movie API

*PHP/Slim 3 with mySQL*

**INSTALLATION -----------------------------------------------------------------------------**

1. Clone this repository in your `htdocs` directory:
	`git clone https://github.com/stoopfrench/movie_api_mongo.git`
2. cd into the directory:
3. Install Dependencies:
	`php composer.phar install`
4. Start your server and database.		
5. Run the custom seeder to build the database, tables and import data from sample .csv file.
	`composer seeder`
6. Use an API Development Enviroment (ex. Postman) to make requests to the API.


**ENDPOINTS --------------------------------------------------------------------------------**

**Movie Search**

GET `api/titles`
 	
 	Returns ALL the movies in the database

GET `api/titles/<id>`
 	
 	Returns the movie stored with that ID

**Create New Movie**

POST `api/titles`
	
	Creates a new movie in the database.
	
	Template: { title: 'string', year: 'string', genres: 'string ( seperated by , )' }

**Update Movie**

PATCH `api/titles/<id>`
	
	Updates one or more properties of a movie in the database.

	Template: { title: <new title>, year: <new year>, genres: <new genres> }

**Delete Movie**

DELETE `api/titles/<id>`

	Deletes the movie with that ID.

**Genre Index**

GET `api/genre`
	
	Returns a list of ALL the genres in the database sorted by the number of movies in the genre.

**Movies by Genre**

GET `api/genre/<genre>`

	Returns a list of movies that have this genre.

**Rename a Genre**

PATCH `api/genre`

	Renames a genre.

	Template: { genre: <genre to rename>, newName: <new name for genre> }

**Year Index**

GET `api/year`
	
	Returns a list of ALL the years in the database sorted by the number of movies released that year.

**Movies by Year**

GET `api/year/<year>`

	Returns a list of movies that were released during this year.















