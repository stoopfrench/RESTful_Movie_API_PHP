# RESTful Movie API

*PHP/Slim Framework with mySQL*

**INSTALLATION -----------------------------------------------------------------------------**

1. Clone this repository in your `htdocs` directory:
	`git clone https://github.com/stoopfrench/movie_api_mongo.git`
2. cd into the directory:
3. Install Dependencies:
	`php composer.phar install`
4. Start your server and database.		
5. Open a new terminal tab (the other one should be running the mongoDB service).
6. Create a new database and name it `movie-database` and create a new table named `movies`.
7. Make 4 new columns and create the following: `id int (11) Auto-Increment, title varchar (255), year varchar (255), genres varchar (255)`
8. Import the csv file in the `assets` folder into your .
9. Use an API Development Enviroment (ex. Postman) to make requests to the API.


**ENDPOINTS --------------------------------------------------------------------------------**

**Movie Search**

GET `/titles`
 	
 	Returns ALL the movies in the database

GET `/titles/<id>`
 	
 	Returns the movie stored with that ID

**Create New Movie**

POST `/titles`
	
	Creates a new movie in the database.
	
	Template: { title: 'string', year: 'number', genres: 'string ( seperated by | )' }

**Update Movie**

PATCH `/titles/<id>`
	
	Updates one or more values of a movie in the database.

**Delete Movie**

DELETE `/titles/<id>`

	Deletes the movie with that ID.

**Genre Index**

GET `/genre`
	
	Returns a list of ALL the genres in the database sorted by the number of movies in the genre.