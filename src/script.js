        function discoverFile1(){
            var networkName = document.getElementById("networkName").value;
            var startipAddress = document.getElementById("startipAddress").value;
            var endipAddress = document.getElementById("endipAddress").value;
            
            // Create an object with form data
            var discoverformData1 = {
                networkName: networkName,
                startipAddress: startipAddress,
                endipAddress: endipAddress
            };

            // Send data to the server using AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "discoverFile1Data.php", true); //savedata.php
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                    // clear the form after successful submission
                    clearForm1();
                }
            };
            xhr.send(JSON.stringify(discoverformData1));

           // clear the form after successful submission
           clearForm1(); 
        }

        function clearForm1() {
            document.getElementById("networkName").value = "";
            document.getElementById("startipAddress").value = "";
            document.getElementById("endipAddress").value = "";
           // Unselect checkboxes
        var checkboxes = document.getElementsByName("credentials1[]");
        checkboxes.forEach(function (checkbox) {
        checkbox.checked = false;
    });
        }

        function discoverFile2(){
            var csvFileName = document.getElementById("csvFileName").value;
            
            // Create an object with form data
            var discoverformData2 = {
                csvFileName: csvFileName,

            };

            // Send data to the server using AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "discoverFile2Data.php", true); //savedata.php
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                    // clear the form after successful submission
                    clearForm2();
                }
            };
            xhr.send(JSON.stringify(discoverformData2));

           // clear the form after successful submission
           clearForm2(); 
        }

        function clearForm2(){
            document.getElementById("csvFileName").value = "";
                // Unselect checkboxes
               var checkboxes = document.getElementsByName("credentials2[]");
               checkboxes.forEach(function (checkbox) {
               checkbox.checked = false;
    });
        }

        function browseFile() {
    // Create an input element
    var input = document.createElement('input');
    input.type = 'file';

    // Listen for the file selection
    input.addEventListener('change', function () {
        var selectedFile = input.files[0];
        // Do something with the selected file, if needed
        console.log('Selected File:', selectedFile.name);

        // Set the selected file name in the csvFileName input field
        document.getElementById('csvFileName').value = selectedFile.name;

        // Remove the input element from the DOM
        document.body.removeChild(input);
    });

    // Append the input element to the body
    document.body.appendChild(input);

    // Trigger the file input
    input.click();
}

        
        

        function addDevice() {
            var ipAddress = document.getElementById("ipAddress").value;
            
            // Create an object with form data
            var addformData = {
                ipAddress: ipAddress,
            };

            // Send data to the server using AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "addDeviceData.php", true); //savedata.php
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                    // clear the form after successful submission
                    clearForm3();
                }
            };
            xhr.send(JSON.stringify(addformData));

           // clear the form after successful submission
           clearForm3(); 
        
        }
      
        function clearForm3() {
            document.getElementById("ipAddress").value = "";
        }

    