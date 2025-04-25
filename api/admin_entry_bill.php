<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Entry Form</title>
    <style>
        /* General Body Styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e0f7fa;
            margin: 0;
            padding: 20px;
        }
        /* Centered and Styled Container */
        .container {
            width: 60%;
            max-width: 800px;
            margin: auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            border: 1px solid #cfd8dc;
        }
        /* Heading Styling */
        h2 {
            text-align: center;
            color:  #2c3e50;;
            font-size: 24px;
            margin-bottom: 20px;
            border-bottom: 2px solid  #2c3e50;;
            padding-bottom: 10px;
        }
        /* Label Styling */
        form label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #37474f;
        }
        /* Input, Select, and Textarea Styling */
        form input, form select, form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #cfd8dc;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
            background-color: #f7f9fa;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        /* Focus and Hover Effects */
        form input:focus, form select:focus, form textarea:focus {
            border-color: #00796b;
            box-shadow: 0 0 8px rgba(0, 121, 107, 0.2);
            outline: none;
        }
        /* Button Styling */
        button {
            width: 100%;
            background-color:  #2c3e50;;
            color: #ffffff;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        button:hover {
            background-color:  #2c3e50;;
            transform: translateY(-2px);
        }
        button:active {
            background-color: #003d33;
            transform: translateY(0);
        }
        /* Back Button */
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
            margin-left: 85%;
        }
        .back-button:hover {
            background-color:  #2c3e50;;
        }
        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="back-button-container">
            <a href="../admin_interface.php" class="back-button">← Back</a>
        </div>
        <h2>Bill Entry Form</h2> 
        <form id="billForm" action="admin_sumit_bill.php" method="POST" enctype="multipart/form-data">
            <label for="partyType">Party Type:</label>
            <select id="partyType" name="party_type" required>
                <option value="registered">Registered</option>
                <option value="non-registered">Non-Registered</option>
            </select>
            <label for="partyName">Party Name:</label>
            <select id="partyName" name="party_name" required>
                <option value="">Select Party</option>
            </select>
            <input type="text" id="manualPartyName" name="manual_party_name" placeholder="Enter Party Name" style="display:none;">
            <label for="gstNo">GST Number:</label>
            <input type="text" id="gstNo" name="gst_no" readonly required>
            <label for="panNo">PAN Number:</label>
            <input type="text" id="panNo" name="pan_no" readonly required>
            <input type="text" id="manualPanNo" name="manual_pan_no" placeholder="Enter PAN Number" style="display:none;">
            <label for="billType">Bill Type:</label>
            <select id="billType" name="bill_type" required>
                <option value="">Select a Bill Type</option>
                <option value="Tax Invoice">Tax Invoice</option>
                <option value="Bill of Supply">Bill of Supply</option>
                <option value="Worksheet">Worksheet</option>
                <option value="Purchase Order">Purchase Order</option>
            </select>
            <label for="billDate">Date:</label>
            <input type="date" id="billDate" name="bill_date" required>
            <label for="billNo">Bill Number:</label>
            <input type="text" id="billNo" name="bill_no" required>
            <label for="taxableAmount">Taxable Amount:</label>
            <input type="number" id="taxableAmount" name="taxable_amount" step="0.01" required>
            <label for="taxAmount">Tax Amount:</label>
            <input type="number" id="taxAmount" name="tax_amount" step="0.01" required>
            <label for="invoiceAmount">Invoice Amount:</label>
            <input type="number" id="invoiceAmount" name="invoice_amount" readonly>
            <label for="departmentName">Department Name:</label>
            <select id="departmentName" name="department_id" required>
                <option value="">Select a Department</option>
            </select>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
            <label for="authorizedPerson">Data Entry By:</label>
            <input type="text" id="authorizedPerson" name="authorized_person" value="Alok" readonly required>
            <label for="billUpload">Upload Bill (JPG/PDF):</label>
            <input type="file" id="billUpload" name="bill_upload" accept=".jpg,.jpeg,.pdf" required>
            <button type="submit" id="submitButton">Submit</button>
        </form>
       
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            // Fetch party names
            $.ajax({
                url: 'fetch_party_names.php',
                type: 'GET',
                success: function (response) {
                    var parties = JSON.parse(response);
                    parties.forEach(function (party) {
                        $('#partyName').append('<option value="' + party.id + '">' + party.name + '</option>');
                    });
                }
            });

            // Fetch department names
            $.ajax({
                url: 'fetch_department_names.php',
                type: 'GET',
                success: function (response) {
                    var departments = JSON.parse(response);
                    departments.forEach(function (department) {
                        $('#departmentName').append('<option value="' + department.id + '">' + department.name + '</option>');
                    });
                }
            });

            // Fetch GST and PAN details when a registered party is selected
            $('#partyName').change(function () {
                var partyId = $(this).val();
                if (partyId) {
                    $.ajax({
                        url: 'fetch_party_details.php',
                        type: 'GET',
                        data: { id: partyId },
                        success: function (response) {
                            var details = JSON.parse(response);
                            $('#gstNo').val(details.gst_no);
                            $('#panNo').val(details.pan_no);
                        }
                    });
                } else {
                    $('#gstNo').val('');
                    $('#panNo').val('');
                }
            });

            // Toggle input fields for non-registered parties
            $('#partyType').change(function () {
                var selectedType = $(this).val();
                if (selectedType === 'non-registered') {
                    $('#manualPartyName').show().prop('required', true);
                    $('#partyName').hide().prop('required', false);
                    $('#manualPanNo').show().prop('required', true);
                    $('#panNo').hide().prop('required', false);
                } else {
                    $('#manualPartyName').hide().val('').prop('required', false);
                    $('#partyName').show().prop('required', true);
                    $('#manualPanNo').hide().val('').prop('required', false);
                    $('#panNo').show().prop('required', true);
                }
            });

            // Calculate invoice amount on the form
            $('#taxableAmount, #taxAmount').on('input', function () {
                var taxableAmount = parseFloat($('#taxableAmount').val()) || 0;
                var taxAmount = parseFloat($('#taxAmount').val()) || 0;
                $('#invoiceAmount').val(taxableAmount + taxAmount);
            });
  
    // Confirm submission
    $('#billForm').on('submit', function (e) {
        var confirmed = confirm("Do you want to submit the bill?");
        if (!confirmed) {
            e.preventDefault();
        }
    });
});

</script>


</body>
</html>
