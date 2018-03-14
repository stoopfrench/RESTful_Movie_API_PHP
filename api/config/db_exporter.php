<?php


try {
	exec('/Applications/XAMPP/bin/mysqldump --user=root  --host=localhost movie_database > api/config/exported/movie_database.sql');
		echo "\n";	
		echo "database dump to .sql file complete";
		echo "\n";	
} catch(Throwable $t) {
	echo "\n";
	echo $t;
}
