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
  <label>Name (e.g. 123):</label>   
  <input type="text" id="name" placeholder="Enter Item Name" name="name" required> 

  <label>Details:</label>   
  <textarea id="details" name="details" placeholder="Enter Details" required rows="10" cols="45"></textarea>

  <label>Model:</label>   
  <input type="text" id="model" placeholder="Enter Model" name="model">

  <label>Serial:</label>   
  <input type="text" id="serial" placeholder="Enter Serial Number" name="serial">

  <label>Notes:</label>   <br>
  <textarea id="notes" name="notes" placeholder="Enter Notes"></textarea><br>
  

  <label>Capacity:</label>   
  <input type="text" id="capacity" placeholder="Enter Capacity" name="capacity">

  <label>Length:</label>   
  <input type="text" id="length" placeholder="Enter Length" name="length">

  <label>Width:</label>   
  <input type="text" id="width" placeholder="Enter Width" name="width">

  <label>Height:</label>   
  <input type="text" id="height" placeholder="Enter Height" name="height">

  <label>Weight (Empty):</label>   
  <input type="text" id="weight_empty" placeholder="Enter Weight (Empty)" name="weight_empty">

  <label>Weight (Full):</label>   
  <input type="text" id="weight_full" placeholder="Enter Weight (Full)" name="weight_full">

  <label>Supplier:</label>   
  <input type="text" id="supplier" placeholder="Enter Supplier" name="supplier">
  <br>
  <label>Date Received:</label>   
  <input type="date" id="date_received" name="date_received">
  <br><br>
  <label>Date Returned:</label>   
  <input type="date" id="date_returned" name="date_returned">

  <br><br>

  <label>Attach An Image:</label>   
  <input type="file" name="fileToUpload" id="fileToUpload">
  <progress id="uploadProgress" value="0" max="100"></progress>
  <div id="uploadStatus"></div>

  <button type="button" onclick="submitForm();">Create Bowser</button>
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

    const detailsInput = document.getElementById("details");
    const detailsValue = detailsInput.value;

    const modelInput = document.getElementById("model");
    const modelValue = modelInput.value;

    const serialInput = document.getElementById("serial");
    const serialValue = serialInput.value;

    const notesInput = document.getElementById("notes");
    const notesValue = notesInput.value;

    const capacityInput = document.getElementById("capacity");
    const capacityValue = capacityInput.value;

    const lengthInput = document.getElementById("length");
    const lengthValue = lengthInput.value;

    const widthInput = document.getElementById("width");
    const widthValue = widthInput.value;

    const heightInput = document.getElementById("height");
    const heightValue = heightInput.value;

    const weightEmptyInput = document.getElementById("weight_empty");
    const weightEmptyValue = weightEmptyInput.value;

    const weightFullInput = document.getElementById("weight_full");
    const weightFullValue = weightFullInput.value;

    const supplierInput = document.getElementById("supplier");
    const supplierValue = supplierInput.value;

    const dateReceivedInput = document.getElementById("date_received");
    const dateReceivedValue = dateReceivedInput.value;

    const dateReturnedInput = document.getElementById("date_returned");
    const dateReturnedValue = dateReturnedInput.value;

    // Create FormData for both form data and file upload
    const formData = new FormData();
    formData.append("name", nameValue);
    formData.append("details", detailsValue);
    formData.append("model", modelValue);
    formData.append("serial", serialValue);
    formData.append("notes", notesValue);
    formData.append("capacity", capacityValue);
    formData.append("length", lengthValue);
    formData.append("width", widthValue);
    formData.append("height", heightValue);
    formData.append("weight_empty", weightEmptyValue);
    formData.append("weight_full", weightFullValue);
    formData.append("supplier", supplierValue);
    formData.append("date_received", dateReceivedValue);
    formData.append("date_returned", dateReturnedValue);

    // Send the form data using fetch
    fetch("./submit.php", {
        method: "POST",
        body: formData,
    })
    .then(response => {
        if (response.ok) {
            return response.text(); // Handle response text
        } else {
            throw new Error(`Failed with status: ${response.status}`);
        }
    })
    .then(data => {
        console.log(data); // Log the response from the server
        // File upload part
        const fileInput = document.getElementById("fileToUpload");
        const file = fileInput.files[0];
        if (file) {
            // Trigger file upload if a file is selected
            uploadFile(file);
        }
    })
    .catch(error => {
        console.error("Error during fetch:", error);
        alert("An error occurred. Please try again.");
    });
}




	
</script>



</body>


</script>     
</html>  