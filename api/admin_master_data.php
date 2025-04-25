<?php
// view_master_data.php
session_start(); // Ensure session is started
include '../config/db_connection.php'; // Include your database connection file

// Fetch all records from the master_data table
$query = "SELECT * FROM master_data"; // Query to fetch all master_data
$stmt = $pdo->prepare($query);
$stmt->execute();

// Check if there are any records
$master_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Master Data</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
   /* Your existing CSS styles */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #eef2f3;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 95%;
        margin: 30px auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .container:hover {
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
    }

    h1 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 20px;
        font-size: 28px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 15px;
        text-align: left;
    }

    th {
        background-color: #2c3e50;
        color: #ffffff;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #d1e7dd;
    }

    .no-data {
        text-align: center;
        color: #888;
        font-style: italic;
        padding: 20px;
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
        margin-left: 92%;
    }

    .back-button:hover {
        background-color: #2c3e50;
    }

    .edit-button {
        padding: 8px 16px;
        background-color: #f39c12;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .edit-button:hover {
        background-color: #e67e22;
    }

    .image-cell img {
        width: 100px;
        height: auto;
        margin: 5px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .image-cell img:hover {
        transform: scale(1.1);
    }

    /* Modal Container */
    .modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    padding-top: 60px;
    text-align: center;
    overflow: auto; /* Allow scrolling if the content overflows */
}

/* Modal Content (the enlarged image) */
/* Modal Content (the enlarged image) */
.modal-content {
    margin: auto;
    display: block;
    max-width: 80%;
    max-height: 80%;
    width: auto;
    height: auto;
    transition: transform 0.3s ease; /* Smooth zooming effect */
    cursor: pointer; /* Make it clear that the image can be interacted with */
}

/* Close Button Style */
.close {
    color: white;
    font-size: 40px;
    font-weight: bold;
    position: absolute;
    top: 10px;
    right: 25px;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: #f39c12;
    text-decoration: none;
    cursor: pointer;
}

.modal-navigation {
    position: absolute;
    top: 50%;
    width: 100%;
    display: flex;
    justify-content: space-between;
    padding: 0 20px;
}

.prev, .next {
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    border: none;
    font-size: 20px;
    padding: 10px;
    cursor: pointer;
    border-radius: 50%;
}

.prev:hover, .next:hover {
    background-color: rgba(0, 0, 0, 0.9);
}
    </style>
</head>
<body>
    <div class="container">
        <div class="back-button-container">
            <a href="../admin_interface.php" class="back-button">← Back</a>
        </div>
        <h1>Master Data Details</h1>
        <?php if (count($master_data) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>PARTY NAME</th>
                    <th>PARTY TYPE</th>
                    <th>GST No</th>
                    <th>PAN NO</th>
                    <th>GST Certificate</th>
                    <th>PAN Certificate</th>
                    <th>Other Certificates 1</th>
                    <th>Other Certificates 2</th>
                    <th>Other Certificates 3</th>
                    <th>Other Certificates 4</th>
                    <th>Edit</th> <!-- Add Edit column -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($master_data as $data): ?>
                <tr>
                
                    <td><?php echo htmlspecialchars($data['party_name']); ?></td>
                    <td><?php echo htmlspecialchars($data['party_type']); ?></td>
                    <td><?php echo htmlspecialchars($data['gst_no']); ?></td>
                    <td><?php echo htmlspecialchars($data['pan_no']); ?></td>
                    <td class="image-cell">
    <?php if (!empty($data['certificate_1'])): ?>
        <img src="../uploads/<?php echo htmlspecialchars($data['certificate_1']); ?>" alt="GST Certificate" onclick="openModal(this)">
        <!-- Modal for the image -->
        <div id="myModal1" class="modal">
            <span class="close" onclick="closeModal('myModal1')">&times;</span>
            <img class="modal-content" id="img1">
        </div>
    <?php else: ?>
        N/A
    <?php endif; ?>
</td>

<td class="image-cell">
    <?php if (!empty($data['certificate_2'])): ?>
        <img src="../uploads/<?php echo htmlspecialchars($data['certificate_2']); ?>" alt="GST Certificate" onclick="openModal(this)">
        <!-- Modal for the image -->
        <div id="myModal1" class="modal">
            <span class="close" onclick="closeModal('myModal1')">&times;</span>
            <img class="modal-content" id="img2">
        </div>
    <?php else: ?>
        N/A
    <?php endif; ?>
</td>
<td class="image-cell">
    <?php if (!empty($data['certificate_3'])): ?>
        <img src="../uploads/<?php echo htmlspecialchars($data['certificate_3']); ?>" alt="GST Certificate" onclick="openModal(this)">
        <!-- Modal for the image -->
        <div id="myModal1" class="modal">
            <span class="close" onclick="closeModal('myModal1')">&times;</span>
            <img class="modal-content" id="img1">
        </div>
    <?php else: ?>
        N/A
    <?php endif; ?>
</td>
<td class="image-cell">
    <?php if (!empty($data['certificate_4'])): ?>
        <img src="../uploads/<?php echo htmlspecialchars($data['certificate_4']); ?>" alt="GST Certificate" onclick="openModal(this)">
        <!-- Modal for the image -->
        <div id="myModal1" class="modal">
            <span class="close" onclick="closeModal('myModal1')">&times;</span>
            <img class="modal-content" id="img1">
        </div>
    <?php else: ?>
        N/A
    <?php endif; ?>
</td>
<td class="image-cell">
    <?php if (!empty($data['certificate_5'])): ?>
        <img src="../uploads/<?php echo htmlspecialchars($data['certificate_5']); ?>" alt="GST Certificate" onclick="openModal(this)">
        <!-- Modal for the image -->
        <div id="myModal1" class="modal">
            <span class="close" onclick="closeModal('myModal1')">&times;</span>
            <img class="modal-content" id="img1">
        </div>
    <?php else: ?>
        N/A
    <?php endif; ?>
</td>
<td class="image-cell">
    <?php if (!empty($data['certificate_6'])): ?>
        <img src="../uploads/<?php echo htmlspecialchars($data['certificate_6']); ?>" alt="GST Certificate" onclick="openModal(this)">
        <!-- Modal for the image -->
        <div id="myModal1" class="modal">
            <span class="close" onclick="closeModal('myModal1')">&times;</span>
            <img class="modal-content" id="img1">
        </div>
    <?php else: ?>
        N/A
    <?php endif; ?>
</td>
                    <td>
                        <a href="edit_master_data.php?id=<?php echo $data['id']; ?>" class="edit-button">Edit</a> <!-- Edit button -->
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p class="no-data">No records found.</p>
        <?php endif; ?>
    </div>
    <script>
     // Open Modal
function openModal(img) {
    var modal = document.createElement('div');
    modal.classList.add('modal');
    modal.innerHTML = `
        <span class="close" onclick="closeModal(this)">&times;</span>
        <img class="modal-content" src="${img.src}" alt="Enlarged Image" onclick="zoomImage(this)" ondblclick="zoomImage(this)">
    `;
    document.body.appendChild(modal);
    modal.style.display = "block"; // Show the modal
}

// Close Modal
function closeModal(modalElement) {
    modalElement.parentElement.style.display = "none";
    modalElement.parentElement.remove(); // Remove the modal from DOM
}

// Zoom In/Out on Double Click
function zoomImage(img) {
    if (img.style.transform === 'scale(2)') {
        // Zoom Out
        img.style.transform = 'scale(1)';
        img.style.transition = 'transform 0.3s ease';
    } else {
        // Zoom In
        img.style.transform = 'scale(2)';
        img.style.transition = 'transform 0.3s ease';
    }
}


    </script>
</body>
</html>
