<?php
include("../config/connection.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $amount = mysqli_real_escape_string($conn, $_POST['amount_bdt']);
    $amount_myr = mysqli_real_escape_string($conn, $_POST['amount_myr']);
    $currency = mysqli_real_escape_string($conn, $_POST['currency']);
    $payment_date = mysqli_real_escape_string($conn, $_POST['payment_date']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $reference_no = mysqli_real_escape_string($conn, $_POST['reference_no']);

    // Handle missing reference number
    if (empty($reference_no)) {
        $reference_no = "Not Given";
    }

    $receipt_image = null;

    // Handle optional image upload
    if (isset($_FILES['receipt_image']) && $_FILES['receipt_image']['error'] == 0) {
        $file = $_FILES['receipt_image'];
        $image_info = getimagesize($file['tmp_name']);
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_file_size = 2 * 1024 * 1024; // 2 MB

        if ($image_info !== false && in_array($image_info['mime'], $allowed_mime_types) && $file['size'] <= $max_file_size) {
            $image_data = file_get_contents($file['tmp_name']);
            $receipt_image = "data:" . $image_info['mime'] . ";base64," . base64_encode($image_data);
        } else {
            $_SESSION['error'] = "Invalid image file or file size exceeds limit";
            header("Location: ../views/user/user-deposit-money.php");
            exit();
        }
    }

    // Insert into database (Prepared Statement)
    $sql = "INSERT INTO deposits (username, amount, amountMYR, currency, payment_method, reference_no, receipt_image, payment_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sddsssss", $username, $amount, $amount_myr, $currency, $payment_method, $reference_no, $receipt_image, $payment_date);

    if ($stmt->execute()) {

        $depositID = $stmt->insert_id;

        
        $_SESSION['temp_depositID'] = $depositID;

        //adding to db
        $sql = "INSERT INTO admin_deposit_notifications (username, depositID, amount, reference_no, payment_method) 
        VALUES ('$username', '$depositID', '$amount', '$reference_no', '$payment_method')";
        mysqli_query($conn, $sql);


        //send notification to admin
        $sql = "SELECT token FROM notification_tokens WHERE username = 'admin'";
        $result = mysqli_query($conn, $sql) or die("Query Failed.");
        if (mysqli_num_rows($result) > 0){
            while ($row = mysqli_fetch_assoc($result)){
                $parameters = array(
                    "pvKey" => "pvKey.json",
                    "project_name" => "testapp-db6f5",
                    "token" => $row['token'],
                    "notification_title" => "PayBird: Deposit Money.",
                    "notification_body" => "User: ".$username."\nAmount : ".$amount." TK. Status : Pending",
                    "notification_image" => "https://img.icons8.com/?size=100&id=DDLGU9cwERZ7&format=png&color=000000", //success.png
                    "notification_link" => "https://paybird.online/views/admin/admin-deposit-details.php?id=".$depositID
                );
                
                include ("notification_sender/send.php");

            }
        }


        header("Location: ../views/user/deposit-success.php?");
        exit();
    } else {
        $_SESSION['error'] = "Database insert error: " . $stmt->error;
        header("Location: ../views/user/user-deposit-money.php");
        exit();
    }
}

// If not a POST request
header("Location: ../views/user/user-deposit-money.php");
exit();
?>
