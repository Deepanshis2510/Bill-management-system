<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Cheque</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="file"]:focus,
        select:focus,
        textarea:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color:  #2c3e50;;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color:  #2c3e50;;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color:  #2c3e50;;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: 75%;
        }

        .back-button:hover {
            background-color:  #2c3e50;;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-button-container">
            <a href="superadmin_portal.php" class="back-button">← Back</a>
        </div>
        <h2>Add Cheque</h2>
        <form action="./api/add_cheque_super.php" method="POST" enctype="multipart/form-data">
            <label for="cheque_number">Cheque Number:</label>
            <input type="text" id="cheque_number" name="cheque_number" required>

            <label for="cheque_to">Cheque To:</label>
            <input type="text" id="cheque_to" name="cheque_to" required>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="bank">Select Bank:</label>
            <select id="bank" name="bank" required>
                <option value="">--Select Bank--</option>
                <option value="ICICI Bank">ICICI Bank</option>
                <option value="HDFC Bank">HDFC Bank</option>
                <option value="Bank of Baroda">Bank of Baroda</option>
                <option value="Punjab National Bank">Punjab National Bank</option>
            </select>

            <label for="comments">Comments:</label>
            <textarea id="comments" name="comments" rows="4" placeholder="Optional comments..."></textarea>

            <label for="cheque_file">Upload Cheque (JPG, PNG, or PDF):</label>
            <input type="file" id="cheque_file" name="cheque_file" accept=".jpg,.jpeg,.png,.pdf" required>

            <button type="submit">Add Cheque</button>
        </form>
    </div>
</body>
</html>
