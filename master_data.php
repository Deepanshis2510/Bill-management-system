<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Master Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="file"], select {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[disabled] {
            background-color: #e9ecef;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #34495e;
        }
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2c3e50;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: 80%;
        }
        .back-button:hover {
            background-color: #34495e;
        }
    </style>
    <script>
        function handlePartyTypeChange() {
            const partyType = document.getElementById('party_type').value;
            const gstNoField = document.getElementById('gst_no');
            const gstCertField = document.getElementById('certificate_1');

            if (partyType === 'registered') {
                gstNoField.disabled = false;
                gstCertField.disabled = false;
            } else if (partyType === 'non-registered') {
                gstNoField.disabled = true;
                gstNoField.value = '';
                gstCertField.disabled = true;
            }
        }

        window.onload = function () {
            handlePartyTypeChange();
        };

        function validateForm(event) {
            const gstNo = document.getElementById('gst_no').value;
            const panNo = document.getElementById('pan_no').value;
            const partyType = document.getElementById('party_type').value;
            const gstCertField = document.getElementById('certificate_1');
            const panCertField = document.getElementById('certificate_2');

            if (partyType === 'registered' && (!gstNo || gstNo.length !== 15)) {
                alert('GST Number must be exactly 15 characters.');
                event.preventDefault();
                return false;
            }

            if (!panNo || panNo.length !== 10) {
                alert('PAN Number must be exactly 10 characters.');
                event.preventDefault();
                return false;
            }

            if (!panCertField.value) {
                alert('PAN Certificate is required.');
                event.preventDefault();
                return false;
            }

            return true;
        }
        window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        // If the status is 'success', display the alert
        if (status === 'success') {
            alert("Your data has been stored successfully!");
        }
    };
    </script>
</head>
<body>
    <div class="container">
        <div class="back-button-container">
            <a href="user_interface.php" class="back-button">← Back</a>
        </div>
        <h2>Add Master Data</h2>
        <form action="./api/add_master_data.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event)">
            <label for="party_name">Party Name:</label>
            <input type="text" id="party_name" name="party_name" required>

            <label for="party_email">Party Email</label>
            <input type="text" id="party_email" name="party_email" required>

            <label for="party_phn">Party Phone Number:</label>  
            <input type="text" id="party_phn" name="party_phn" required>
            
            <label for="party_type">Party Type:</label>
            <select id="party_type" name="party_type" onchange="handlePartyTypeChange()" required>
                <option value="registered">Registered</option>
                <option value="non-registered">Non-Registered</option>
            </select>

            <label for="gst_no">GST Number:</label>
            <input type="text" id="gst_no" name="gst_no" maxlength="15">

            <label for="pan_no">PAN Number:</label>
            <input type="text" id="pan_no" name="pan_no" maxlength="10">

            <label for="certificate_1">GST Certificate:</label>
            <input type="file" id="certificate_1" name="certificate_1" accept=".pdf,.jpg,.png">

            <label for="certificate_2">PAN Certificate:</label>
            <input type="file" id="certificate_2" name="certificate_2" accept=".pdf,.jpg,.png" required>


            <label for="certificate_3">Additional Certificate 1 (Optional):</label>
            <input type="file" id="certificate_3" name="certificate_3" accept=".pdf,.jpg,.png">

            <label for="certificate_4">Additional Certificate 2 (Optional):</label>
            <input type="file" id="certificate_4" name="certificate_4" accept=".pdf,.jpg,.png">

            <label for="certificate_5">Additional Certificate 3 (Optional):</label>
            <input type="file" id="certificate_5" name="certificate_5" accept=".pdf,.jpg,.png">

            <label for="certificate_6">Additional Certificate 4 (Optional):</label>
            <input type="file" id="addicertificate_6" name="certificate_6" accept=".pdf,.jpg,.png">

            <button type="submit">Add Master Data</button>
        </form>
    </div>
</body>
</html>
