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
	$books = $db->query('SELECT * FROM bookmark;')->fetchAll(PDO::FETCH_CLASS);
	$app->render('books.php', ['books' => $books]);
});

$app->post('/books', function () use ($db, $app){
    //Create book
	$title = $app->request()->post('title');
    $sth = $db->prepare('INSERT INTO bookmark (url, title) VALUES (?, ?);');
    $sth->execute([
        $description = $app->request()->post('description'),
        $title = $app->request()->post('title'),
    ]);
	
	echo json_encode($title);
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

$app->delete('/books', function() use($db, $app){
	$sth = $db->prepare('DELETE FROM bookmark WHERE id = ?;');
    $sth->execute([intval($id)]);
	echo $id;
});

$app->get('/install', function () use ($db) {
    $db->exec('  CREATE TABLE IF NOT EXISTS bookmark (
                    id INTEGER PRIMARY KEY, 
                    title TEXT, 
                    url TEXT UNIQUE);');
});

$app->run();