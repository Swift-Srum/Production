<?php
error_reporting(1);
include('../essential/backbone.php');
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
$aes = new AES256;
$err = $_GET['err'];
$err = $aes->decrypt($err, "secretkey");
?>


<!DOCTYPE html>   
<html>   
<head>  
<meta name="viewport" content="width=device-width, initial-scale=1">  
<title> Create Item </title>  
<link rel="stylesheet" href="/assets/style_create.css">  

</head>    
<body>    
  <center><h1>Create Item</h1></center>   
  <form>  
    <div class="container">   
	  <label>Name (e.g. PvP Kit):</label>   
      <input type="text" id="name" placeholder="Enter Item Name" name="name" required> 
      <label>Price:</label>   
      <input type="text" id="price" placeholder="Enter Price" name="price" required>  
      <label>Details:</label>   
      <textarea id="details" name="details" placeholder="Enter Details" required rows="10" cols="45"></textarea>
	  <label>Platform:</label>   
	  <select id="platform" name="platform">
       <option value="PS">PlayStation</option>
       <option value="XB">Xbox</option>
      </select>
	  <br>
	  <label>Server Type:</label>   
	  <select id="serverType" name="serverType">
       <option value="1">1st Person</option>
       <option value="3">3rd Person</option>
      </select>
	  <br>
	  <label>Buy Link (Can use shoppy.gg):</label>   
      <input type="text" id="buyLink" placeholder="Enter Buy Link" name="buyLink" required>  
	  <br>
	  <br>
	  <label>Attach Any Images:</label>   
	  <input type="file" name="fileToUpload" id="fileToUpload">
      <progress id="uploadProgress" value="0" max="100"></progress>
      <div id="uploadStatus"></div>
      <button type="button" onclick="submitForm();">Create Item</button>
      <a href="../"><button type="button" class="cancelbtn">Cancel</button></a>
    </div>   
  </form>     
  <center><h1><?php echo $err ?></h1></center>

  <script>
    function handleFileSelect() {
        const fileInput = document.getElementById('fileToUpload');
        const file = fileInput.files[0];

        if (file) {
            uploadFile(file);
        }
    }

    function uploadFile(file) {
        const xhr = new XMLHttpRequest();
        const formData = new FormData();

        formData.append('fileToUpload', file);

        xhr.upload.addEventListener('progress', function (event) {
            if (event.lengthComputable) {
                const percentComplete = (event.loaded / event.total) * 100;
                document.getElementById('uploadProgress').value = percentComplete;
            }
        });

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    document.getElementById('uploadStatus').innerHTML = xhr.responseText;
                } else {
                    document.getElementById('uploadStatus').innerHTML = 'Error uploading file.';
                }
            }
        };

        xhr.open('POST', 'upload.php', true);
        xhr.send(formData);
    }

    function submitForm() {
		const nameInput = document.getElementById("name");
        const nameValue = nameInput.value;
		
        const priceInput = document.getElementById("price");
        const priceValue = priceInput.value;

        const detailsInput = document.getElementById("details");
        const detailsValue = detailsInput.value;

        const platformInput = document.getElementById("platform");
        const platformValue = platformInput.value;

        const serverTypeInput = document.getElementById("serverType");
        const serverTypeValue = serverTypeInput.value;
		
		const buyLinkInput = document.getElementById("buyLink");
        const buyLinkValue = buyLinkInput.value;



        // Create URL-encoded string
        const urlEncodedData = new URLSearchParams();
		urlEncodedData.append("name", nameValue);
        urlEncodedData.append("price", priceValue);
        urlEncodedData.append("details", detailsValue);
        urlEncodedData.append("platform", platformValue);
        urlEncodedData.append("serverType", serverTypeValue);
		urlEncodedData.append("buyLink", buyLinkValue);

        fetch("./submit.php", {
            method: "POST",
            body: urlEncodedData,
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
        })
        .then(response => {
            if (response.redirected) {
				handleFileSelect();
                const redirectLocation = response.url;
                window.location.href = redirectLocation;
            } else {
                if (response.ok) {
                    return response.text();
				
                } else {
                    throw new Error(`Failed with status: ${response.status}`);
                }
            }
        })
        .then(data => {
            console.log(data);
        })
        .catch(error => {
            console.error("Error during fetch:", error);
        });
    }
	
</script>

<script>
var el      =  $('body')[0];
var src     =  $(el).css('background-image').slice(4, -1);
var img     =  new Image();

checkHeight =  function(){    
    if(img.height < window.innerHeight){
       repeatBg();     
    }
}
img.onload  =  checkHeight();
img.src = src;
</script>

</body>


</script>     
</html>  