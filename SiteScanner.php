<?php
	require ('functions.php');
	$errors = array();
	$msgs = array();
	function showValue($from){
		if(isset($from)) echo $from; 
	}
error_reporting(E_ALL & ~E_NOTICE);
	if(isset($_POST['submit']) )
	{	
		if(isset($_POST['site']) && isset($_POST['options']))
		{
			$site = $_POST['site'];
			$option = $_POST['options'];
			echo "Site is ".$site."\n"."You chose - ".$option."\n";

			if ($option == "all")
				full($site); 
			else if($_POST['options'] == "1")
				$errors[] = "Please select an operation!";
			else	
				$option();
		}else{
			$errors[] = "All fields are required!";
		}
		
	}
?>

<DOCTYPE! html>
<html>
	<head>
		<link rel="stylesheet" href="style.css" />
		<script type="text/javascript" src="#"></script>
		<title>Site Scanner</title>
	</head>
	<body>
		<div class="container">
			<header>
				<h1>SLYROX SITE SCANNER!</h1>
			</header>
			
			<section class="round">
				<form action="SiteScanner.php" method="POST">
					Enter your site: <input type="text" name="site" value="<?php showValue($site); ?>"  /><br /><br />
					What do you want to check for? 
					<select name = "options">
						<option value="1">Select ..</option>
						<option value = "xss" <?php if(isset($option) && $option == "xss"){echo "selected=\"selected\"";} ?> > XSS </option>
						<option value="sql" <?php if(isset($option) && $option == "sql"){echo "selected=\"selected\"";} ?> >SQL</option>
						<option value="all" <?php if(isset($option) && $option == "all"){echo "selected=\"selected\"";} ?> >ALL</option>
					</select><br/><br/>
					<input type="submit" name="submit" value="Start Scan!" />
				</form>
				<div id="error">
					<?php 
						if(isset($errors)){
							foreach ($errors as $error){
									echo $error;
							} 
						}
						
					?>
				</div>
				<div id="success">
				<?php	
					if(isset($msgs)){ 
						foreach ($msgs as $msg){
								echo $msg;
						} 
					}else{
						print "here in here";
					}
					
					
				?>
				</div>
			</section>
			
			<footer>

			</footer>
		</div>
	</body>
</html>