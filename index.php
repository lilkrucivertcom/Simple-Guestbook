<!DOCTYPE html>
<html>
<head>
	<title>guestbook</title>
     <style>
      body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 1%;
      }
      header {
        background-color: #333;
        color: white;
        text-align: center;
      }
      label {
        display: block;
      }
      input[type=text], textarea {
        border: 2px solid #ccc;
        border-radius: 4px;
      }
      input[type=submit] {
        background-color: #333;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        font-size: 1em;
        cursor: pointer;
      }
      input[type=submit]:hover {
        background-color: #555;
      }
      .comment {
        background-color: #f9f9f9;
        padding: 20px;
        border: 1px solid #ccc;
        margin-bottom: 10px;
        border-radius: 4px;
      }
    </style>
</head>
<body>
  <h1 style='display:inline;'> Guestbook </h1>  made with ❤️ by <a href='https://lilkrucivert.com'> lilkrucivert </a>
  
  
  	<form method="post">
		<label for="nickname">Nickname:</label>
		<input type="text" id="nickname" name="nickname" required><br>
		
		<label for="comment">Comment:</label>
		<textarea id="comment" name="comment" required></textarea><br>
		
		<button type="submit">Submit</button>
	</form>
  <h2>comments:</h2>
  
	<?php
	$time_limit = 3600; // <- Edit this to change how often users are allowed to post (in seconds)
	$ip_hash = md5($_SERVER['REMOTE_ADDR']);
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$file = fopen('guestbook_ip.txt', 'r');	
		if ($file) {
			$allowed_to_comment = true;
			
			while (($line = fgets($file)) !== false) {
				$parts = explode(',', $line);
				$hash = trim($parts[0]);
				$time = trim($parts[1]);
				
				if ($hash === $ip_hash && (time() - $time) < $time_limit) {
					$allowed_to_comment = false;
					break;
				}
			}
			
			fclose($file);
		}
		
		if ($allowed_to_comment) {
			$nickname = htmlspecialchars($_POST['nickname']);
			$comment = htmlspecialchars($_POST['comment']);
			$date = date('Y-m-d H:i:s');
			$data = "[$date] $nickname: $comment\n";
			$file = fopen('guestbook.txt', 'a');
			fwrite($file, $data);
			fclose($file);

			$file = fopen('guestbook_ip.txt', 'a');
			
			if ($file) {
				fwrite($file, "$ip_hash," . time() . "\n");
				fclose($file);
			}
		} else {
			echo "<p>Please don't spam ;)</p>";
		}
	}
    
	$file = fopen('guestbook.txt', 'r');
	
	if ($file) {
		while (($line = fgets($file)) !== false) {
			echo "<p>$line</p>";
		}
		fclose($file);
	}
	?>
</body>
</html>