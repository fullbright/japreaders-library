<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<style type="text/css">
		
		ul.nav li { margin: 20px; }
		.container-fluid { margin: 20px auto }
		.tab-content { margin: 20px; }
	</style>
</head>
<body>
	<div class="container-fluid">
		<nav role="navigation">
		<ul class="nav nav-tabs nav-justified" id="navigation-tabs">
			<li role="presentation" class="active">
				<a data-toggle="tab" href="#home-tab">Home</a>
			</li>
			<li role="presentation" class="">
				<a data-toggle="tab" href="#addbook-tab">Add a book</a>
			</li>
			<li role="presentation" class="">
				<a data-toggle="tab" href="#listbooks-tab">Books lists</a>
			</li>
		</ul>
		</nav>
		
		<div class="tab-content">
			<div id="home-tab" class="tab-pane fade in active">
					Bienvenue dans la librairie de Jap Readers.
			</div>
			<div id="addbook-tab" class="tab-pane fade">
				<form action="/jap-readers-library/books" id="addbook" enctype="multipart/form-data">
					<div class="form-group">
						<label for="form-book-title">Title</label>
						<input type="text" class="form-control" id="form-book-title" name="title" placeholder="Enter the book's title here.">
					</div>
					<div class="form-group">
						<label for="form-book-author">Author</label>
						<input type="text" class="form-control" id="form-book-author" name="author" placeholder="Enter the book's author here.">
					</div>
					<div class="form-group">
						<label for="form-book-publishinghouse">Publishing house</label>
						<input type="text" class="form-control" id="form-book-publishinghouse" name="publishinghouse" placeholder="Enter the book's publishing house here.">
					</div>
					<div class="form-group">
						<label for="form-book-description">Description</label>
						<textarea class="form-control" rows="5" id="form-book-description" name="description" placeholder="Enter the book's description, your impressions, the first comments ...'"></textarea>
					</div>
					<div class="form-group">
						<label for="form-book-rate">Rate (over 5)</label>
						<input type="text" class="form-control" id="form-book-rate" placeholder="3/5">
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="recommended">I recommend this book.
						</label>
					</div>
					<div class="form-group">
						<label for="book-coverage">Cover image (PNG, GIF, JPG)</label>
						<input type="file" id="book-coverage">
						<p class="help-block">Please select the book's cover image and upload it.</p>
					</div>
					<button type="submit" class="btn btn-default">Add the book</button>
				</form>
				<div id="result">Result here : </div>
			</div>
			<div id="listbooks-tab" class="tab-pane fade">
				<table class="table">
					<thead>
					<tr>
						<th>Title</th>
						<th>Url</th>
						<th>Delete</th>
					</tr>
					</thead>
					<tbody>
					<?php
						foreach($books as $book)
						{
					?>
					<tr>
						<td><?php echo $book->title; ?></td>
						<td><?php echo $book->url; ?></td>
						<td><a href="#" id="<?php echo $book->id; ?>" class="delete-book">Delete book</a></td>
					</tr>
					<?php
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
		
		<h1 id="fb-welcome"></h1>
		
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<!-- <script src="js/dropzone.js"></script> -->
		<script>
		$(document).ready(function(){
			$("button").click(function(){
				$("p").hide();
			});
			
			// Attach a submit handler to the form
			$('#addbook').on('submit', uploadFiles);
			
			/*$("#addbook").submit(function( event ) {
			 
			  // Stop form from submitting normally
			  event.preventDefault();
			 
				$( "#result" ).empty().append("Sending ...");
			  // Get some values from elements on the page:
			  var $form = $( this ),
				title = $form.find( "input[name='title']" ).val(),
				author = $form.find( "input[name='author']" ).val(),
				publishinghouse = $form.find( "input[name='publishinghouse']" ).val(),
				description = $form.find( "input[name='description']" ).val(),
				recommend = $form.find( "input[name='recommend']" ).val(),
				rate = $form.find( "input[name='rate']" ).val(),
				posturl = $form.attr( "action" );
			 
			  // Send the data using post
			  var posting = $.post( 
				posturl, 
				{ 
					title: title, 
					author: author, 
					publishinghouse: publishinghouse, 
					description: description, 
					recommend: recommend, 
					rate: rate 
				}
			  );
			 
			  // Put the results in a div
			  posting.done(function( data ) {
				//var content = $( data ).find( "#content" );
				$( "#result" ).append(data); //content );
				$( "#result" ).append("Done.");
				
			  });
			});
			*/
			
			// To handle deletions
			$(".delete-book").click(function(){
				event.preventDefault();
				
				var bookid = $(this).attr('id');
				$.ajax({
					method: "DELETE",
					url: "/jap-readers-library/books",
					data: { id: bookid }
				})
				.done(function( response ) {
					$( "#result" ).empty().append(response);
				});
				
				return false;
			});
			
			$('#navigation-tabs a').click(function (e) {
				e.preventDefault()
				$(this).tab('show')
			});
			
			// --- Handling uploads
			// Variable to store your files
			var files;

			$('input[type=file]').on('change', prepareUpload);
			function prepareUpload(event)
			{
				files = event.target.files;
			}
			function uploadFiles(event)
			{
				event.stopPropagation(); // Stop stuff happening
				event.preventDefault(); // Totally stop stuff happening

				// START A LOADING SPINNER HERE

				// Create a formdata object and add the files
				var data = new FormData();
				$.each(files, function(key, value)
				{
					data.append(key, value);
				});

				$.ajax({
					url: '/jap-readers-library/upload',
					type: 'POST',
					data: data,
					cache: false,
					dataType: 'json',
					processData: false, // Don't process the files
					contentType: false, // Set content type to false as jQuery will tell the server its a query string request
					success: function(data, textStatus, jqXHR)
					{
						if(typeof data.error === 'undefined')
						{
							// Success so call function to process the form
							submitForm(event, data);
						}
						else
						{
							// Handle errors here
							console.log('ERRORS: ' + data.error);
						}
					},
					error: function(jqXHR, textStatus, errorThrown)
					{
						// Handle errors here
						console.log('ERRORS: ' + textStatus);
						// STOP LOADING SPINNER
					}
				});
			}
			
			function submitForm(event, data)
			{
			  // Create a jQuery object from the form
				$form = $(event.target);

				// Serialize the form data
				var formData = $form.serialize();

				// You should sterilise the file names
				$.each(data.files, function(key, value)
				{
					formData = formData + '&filenames[]=' + value;
				});

				$.ajax({
					url: '/jap-readers-library/books',
					type: 'POST',
					data: formData,
					cache: false,
					dataType: 'json',
					success: function(data, textStatus, jqXHR)
					{
						if(typeof data.error === 'undefined')
						{
							// Success so call function to process the form
							console.log('SUCCESS: ' + data.success);
						}
						else
						{
							// Handle errors here
							console.log('ERRORS: ' + data.error);
						}
					},
					error: function(jqXHR, textStatus, errorThrown)
					{
						// Handle errors here
						console.log('ERRORS: ' + textStatus);
					},
					complete: function()
					{
						// STOP LOADING SPINNER
					}
				});
			}
		});
		
		

		// FACEBOOK 
		window.fbAsyncInit = function() {
			FB.init({
				appId      : '840246512714017',
				xfbml      : true,
				version    : 'v2.2'
			});
			
			function onLogin(response) 
			{
			  if (response.status == 'connected') 
			  {
				FB.api('/me?fields=first_name', function(data) 
				{
				  var welcomeBlock = document.getElementById('fb-welcome');
				  welcomeBlock.innerHTML = 'Hello, ' + data.first_name + '!';
				});
			  }
			}

			FB.getLoginStatus(function(response) 
			{
			  // Check login status on load, and if the user is
			  // already logged in, go directly to the welcome message.
			  if (response.status == 'connected') 
			  {
				onLogin(response);
			  }
			  else
			  {
				// Otherwise, show Login dialog first.
				FB.login(function(response) 
				{
				  onLogin(response);
				}, {scope: 'user_friends, email'}
				);
			  }
			});

			// ADD ADDITIONAL FACEBOOK CODE HERE
		};

		(function(d, s, id){
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		</script>
	</div>
</body>
</html>