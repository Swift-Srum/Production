<?php
// Set error reporting level to report all errors except E_NOTICE
error_reporting(1);

// Include necessary files
include('../essential/backbone.php');

// Set HTTP headers for security measures
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
$origin = $_SERVER['HTTP_REFERER'];
if($origin != "https://s4308324-ctxxxx.uogs.co.uk/view-bikes/" && $origin != "https://s4308324-ctxxxx.uogs.co.uk/view-all-bikes/")
	$origin = "../";

// Retrieve username and session ID from cookies
$username = $_COOKIE['user_name'];
$sessionID = $_COOKIE['sessionId'];

// Check if user is logged in and if they are an admin
$loggedIn = confirmSessionKey($username, $sessionID);
$isAdmin = checkIsUserAdmin($username, $sessionID);

$userType = "";

// Retrieve bike ID from URL parameter
$itemId =  $_GET['itemId'];

// Retrieve bike details based on user ID and bike ID
$itemInfo = getItemDetails($itemId);

// Get bike image name
$itemImageName = getItemImage($itemId);

// Initialize AES encryption object and decrypt error message from URL parameter
$aes = new AES256;
$err = $_GET['err'];
$err = $aes->decrypt($err, "henrywroteallofthiscode");

// Loop through bike details array and assign values to variables
foreach ($itemInfo as $item) {
	$id = $item['id'];
    $name = $item['name'];
	$price = $item['price'];
	$platform = $item['platform'];
	$serverType = $item['serverType'];
	$details = $item['details'];
	$buyLink = $item['buyLink'];
	
	if($platform == "XB")
		$platform = "Xbox";
	else
		$platform = "PlayStation";
	
	if($serverType == "1")
		$serverType = "1st Person";
	else
		$serverType = "3rd Person";
}

// If item ID is null, redirect to main page
if($id == null)
	header("Location: ../");
?>
<!DOCTYPE html>   
<html>   
<head>  
<link rel="stylesheet" href="/assets/styleItemDetails.css"> 
<style>
    img {
        max-width: 100%; /* Ensure the image does not exceed the container width */
        height: auto; /* Maintain the aspect ratio */
        display: block; /* Ensure proper layout */
        margin: 0 auto; /* Center the image horizontally */
    }
</style>
<meta name="viewport" content="width=device-width, initial-scale=1">  
<title> <?php echo $name;?></title>  
</head>    
<body>    
    <form>  
        <div class="container">   
            <?php 
			echo "<img src=\"../create-item/uploads/$itemImageName\" onerror=\"this.onerror=null;this.src='/create-item/uploads/NOIMAGE.jpg';\"";
			echo "<br>";
            echo "<label>Kit Name: $name</label><br>";
            echo "<label>Price: $$price</label><br>";
            echo "<label>Platform: $platform</label><br>";
            echo "<label>Server Type: $serverType</label><br>";
			echo "<br>";
            echo "<label>Details: <br>$details</label><br>";
			echo "<a href = \"$buyLink\"><button type=\"button\">Buy Kit</button></a>";
            
            ?>
			<br>

        </div>

    </form>   

    <center> <h1> <?php echo $err ?> </h1> </center> 
  <script>
  function updateBikeStolen() {
  const commentInput = document.getElementById("comment");
  const commentValue = commentInput.value;

  const bikeId = <?php echo $id ?>;
  const status = "<?php echo $reportStatus ?>";


  // Create URL-encoded string
  const urlEncodedData = new URLSearchParams();
  urlEncodedData.append("status", status);
  urlEncodedData.append("comments", commentValue);
  urlEncodedData.append("bikeId", bikeId);

  fetch("../php/updateBikeStatus.php", {
    method: "POST",
    body: urlEncodedData,
    headers: {
      "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
    },
  })
    .then(response => {
      // Check if the response is a redirect
      if (response.redirected) {
        // If redirected, get the new location
        const redirectLocation = response.url;

        // Redirect the user to the specified location
        window.location.href = redirectLocation;
      } else {
        // Handle other aspects of the response if needed
        if (response.ok) {
          return response.text(); // or response.json() if expecting JSON
        } else {
          throw new Error(`Failed with status: ${response.status}`);
        }
      }
    })
    .then(data => {
      // Handle the response data if needed
      console.log(data);
    })
    .catch(error => {
      // Handle fetch errors
      console.error("Error during fetch:", error);
    });
}

function deleteBike() {

  const bikeId = <?php echo $id ?>;


  // Create URL-encoded string
  const urlEncodedData = new URLSearchParams();
  urlEncodedData.append("bikeId", bikeId);

  fetch("../php/deleteBike.php", {
    method: "POST",
    body: urlEncodedData,
    headers: {
      "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
    },
  })
    .then(response => {
      // Check if the response is a redirect
      if (response.redirected) {
        // If redirected, get the new location
        const redirectLocation = response.url;

        // Redirect the user to the specified location
        window.location.href = redirectLocation;
      } else {
        // Handle other aspects of the response if needed
        if (response.ok) {
          return response.text(); // or response.json() if expecting JSON
        } else {
          throw new Error(`Failed with status: ${response.status}`);
        }
      }
    })
    .then(data => {
      // Handle the response data if needed
      console.log(data);
    })
    .catch(error => {
      // Handle fetch errors
      console.error("Error during fetch:", error);
    });
}
</script>

</body>     
</html>
