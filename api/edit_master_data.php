<?php
// edit_master_data.php
session_start();
include '../config/db_connection.php';

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the specific record to edit
    $query = "SELECT * FROM master_data WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        die("Record not found");
    }

    // Update the record if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $party_name = $_POST['party_name'];
        $party_type = $_POST['party_type'];
        $gst_no = $_POST['gst_no'];
        $pan_no = $_POST['pan_no'];

        $certificates = [];
        for ($i = 1; $i <= 6; $i++) {
            $certificate_field = "certificate_$i";
            if (!empty($_FILES[$certificate_field]['name'])) {
                $target_dir = "../uploads/";
                $target_file = $target_dir . basename($_FILES[$certificate_field]['name']);
                move_uploaded_file($_FILES[$certificate_field]['tmp_name'], $target_file);
                $certificates[$certificate_field] = basename($_FILES[$certificate_field]['name']);
            } else {
                $certificates[$certificate_field] = $data[$certificate_field];
            }
        }

        $update_query = "UPDATE master_data SET party_name = ?, party_type = ?, gst_no = ?, pan_no = ?, certificate_1 = ?, certificate_2 = ?, certificate_3 = ?, certificate_4 = ?, certificate_5 = ?, certificate_6 = ? WHERE id = ?";
        $stmt = $pdo->prepare($update_query);
        $stmt->execute([
            $party_name, $party_type, $gst_no, $pan_no,
            $certificates['certificate_1'], $certificates['certificate_2'], $certificates['certificate_3'],
            $certificates['certificate_4'], $certificates['certificate_5'], $certificates['certificate_6'],
            $id
        ]);

        header("Location: admin_master_data.php");
        exit;
    }
} else {
    die("Invalid request");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Master Data</title>
    <link rel="stylesheet" href="styles.css">
    <style>
         body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 60%;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        form input[type="text"],
        form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            display: block;
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #218838;
        }

        p {
            font-size: 14px;
            margin-top: -10px;
            color: #007bff;
        }

        p a {
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Master Data</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="party_name">Party Name</label>
            <input type="text" id="party_name" name="party_name" value="<?php echo htmlspecialchars($data['party_name']); ?>" required>

            <label for="party_type">Party Type</label>
            <input type="text" id="party_type" name="party_type" value="<?php echo htmlspecialchars($data['party_type']); ?>" required>

            <label for="gst_no">GST No</label>
            <input type="text" id="gst_no" name="gst_no" value="<?php echo htmlspecialchars($data['gst_no']); ?>" required>

            <label for="pan_no">PAN No</label>
            <input type="text" id="pan_no" name="pan_no" value="<?php echo htmlspecialchars($data['pan_no']); ?>" required>

            <?php for ($i = 1; $i <= 6; $i++): ?>
                <label for="certificate_<?php echo $i; ?>">Certificate <?php echo $i; ?></label>
                <input type="file" id="certificate_<?php echo $i; ?>" name="certificate_<?php echo $i; ?>">
                <?php if (!empty($data["certificate_$i"])): ?>
                    <p>Current File: <a href="./uploads/<?php echo htmlspecialchars($data["certificate_$i"]); ?>" target="_blank">View Certificate <?php echo $i; ?></a></p>
                <?php endif; ?>
            <?php endfor; ?>

            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
