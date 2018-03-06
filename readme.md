# RESTful Movie API

*PHP/Slim 3 with mySQL*

**INSTALLATION -----------------------------------------------------------------------------**

1. Clone this repository in your `htdocs` directory:
	`git clone https://github.com/stoopfrench/movie_api_mongo.git`
2. cd into the directory:
3. Install Dependencies:
	`php composer.phar install`
4. Start your server and database.		
5. Create a new database and name it `movie-database` and create a new table named `movies`.
6. Make 4 new columns and create the following: `id int (11) Auto-Increment, title varchar (255), year varchar (255), genres varchar (255)`
7. Import the csv file in the `assets` folder into your .
8. Use an API Development Enviroment (ex. Postman) to make requests to the API.


**ENDPOINTS --------------------------------------------------------------------------------**

**Movie Search**

GET `api/titles`
 	
 	Returns ALL the movies in the database

GET `api/titles/<id>`
 	
 	Returns the movie stored with that ID

**Create New Movie**

POST `api/titles`
	
	Creates a new movie in the database.
	
	Template: { title: 'string', year: 'string', genres: 'string ( seperated by | )' }

**Update Movie**

PATCH `api/titles/<id>`
	
	Updates one or more properties of a movie in the database.

**Delete Movie**

DELETE `api/titles/<id>`

	Deletes the movie with that ID.

**Genre Index**

GET `api/genre`
	
	Returns a list of ALL the genres in the database sorted by the number of movies in the genre.

**Movies by Genre**

GET `api/genre/<genre>

	Returns a list of movies that have this genre.

**Year Index**

GET `api/year`
	
	Returns a list of ALL the years in the database sorted by the number of movies released that year.

**Movies by Year**

GET `api/year/<year>`

	Returns a list of movies that were released during this year.















