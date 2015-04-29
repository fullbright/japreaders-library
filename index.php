<?php

require './Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array(
	'debug' => true,
	'templates_path' => './templates',
	'mode' => 'development',
));
$db = new PDO('sqlite:db.sqlite3');


// Only invoked if mode is "development"
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'log.enable' => false,
        'debug' => true
    ));
});

$app->get('/', function() use ($db, $app)
{
	$books = $db->query('SELECT * FROM book;')->fetchAll(PDO::FETCH_CLASS);
	$app->render('books.php', ['books' => $books]);
});

$app->get('/list', function () use ($db, $app) {
    $sth = $db->query('select * from book;');
    echo json_encode($sth->fetchAll(PDO::FETCH_CLASS));
});

$app->get('/book/:id', function ($id) use ($db, $app) {
    $sth = $db->query("select * from book where id = $id;");
    echo json_encode($sth->fetchAll(PDO::FETCH_CLASS));
});


$app->put('/book', function () use ($db, $app){
    //Create book
	$title = $app->request->params('title');
    $sth = $db->prepare('INSERT INTO book (url, title, author, image, review, editions, published_year, nbpages, language) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);');
    $sth->execute([
            $url = $app->request()->put('url'),
            $title = $app->request()->put('title'),
            $author = $app->request()->put('author'),
            $image = $app->request()->put('image'),
            $review = intval($app->request()->put('review')),
            $editions = $app->request()->put('editions'),
            $published_year = $app->request()->put('published_year'), 
            $nbpages = intval($app->request()->put('nbpages')), 
            $languages = $app->request()->put('language'),
    ]);
	
	echo json_encode($title);
});

$app->post('/book/:id', function ($id) use ($db, $app){
   // Update an existing book
   $title = $app->request()->post('title'); 
   $url = $app->request()->post('url');
   $sth = $db->prepare("UPDATE book SET url = ?, 
                                            title = ?, 
                                            author = ?,
                                            image = '',
                                            review = ?,
                                            editions = ?,
                                            published_year = ?,
                                            nbpages = ?,
                                            language = ? WHERE id = $id");
   $sth->execute([
        $url = $app->request()->post('url'), 
        $title = $app->request()->post('title'),
        $author = $app->request()->post('author'), 
        $review = intval($app->request()->post('review')),
        $editions = $app->request()->post('editions'),
        $published_year = $app->request()->post('published_year'),
        $nbpages = intval($app->request()->post('nbpages')),
        $language = $app->request()->post('language'),
    ]);

	echo json_encode($id);
});

$app->post('/upload', function () use ($db, $app){
    	
	$data = array();

	/*if(isset($_GET['files']))
	{  
		$error = false;
		$files = array();

		$uploaddir = './uploads/';
		foreach($_FILES as $file)
		{
			if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
			{
				$files[] = $uploaddir .$file['name'];
			}
			else
			{
				$error = true;
			}
		}
		$data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
	}
	else
	{
		$data = array('success' => 'Form was submitted', 'formData' => $_POST);
	}*/
	
	$ds          = DIRECTORY_SEPARATOR;  //1
 
	$storeFolder = 'uploads';   //2
	
	var_dump($postvariables);
	 
	if (!empty($_FILES)) {
		 
		$tempFile = $_FILES['file']['tmp_name'];          //3             
		  
		$targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
		 
		$targetFile =  $targetPath. $_FILES['file']['name'];  //5
	 
		move_uploaded_file($tempFile,$targetFile); //6
		 
	}
	
	$postvariables = $app->request->post();
	
	var_dump($postvariables);
	$data = array('success' => 'Form was submitted', 'formData' => $postvariables);

	echo json_encode($data);
});

$app->delete('/book/:id', function($id) use($db, $app){
	$sth = $db->prepare('DELETE FROM book WHERE id = ?;');
    $sth->execute([intval($id)]);
	echo $id;
});

$app->post('/install', function () use ($db) {
    $db->exec('DROP TABLE IF EXISTS BOOK;');
    $db->exec('  CREATE TABLE IF NOT EXISTS book (
                    id INTEGER PRIMARY KEY, 
                    title TEXT,
                    author TEXT,
                    image TEXT,
                    review INTEGER,
                    editions TEXT,
                    published_year TEXT,
                    nbpages INTEGER,
                    language TEXT,
                    url TEXT);');
    echo json_encode("database installed.");
});

$app->run();
