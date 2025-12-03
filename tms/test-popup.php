<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popup Test</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Popup Notification CSS -->
    <link href="assets/css/components/popup-notifications.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Popup Notification Test</h1>
        <button class="btn btn-success" onclick="showSuccess()">Show Success</button>
        <button class="btn btn-danger" onclick="showError()">Show Error</button>
        <button class="btn btn-info" onclick="showInfo()">Show Info</button>
    </div>

    <!-- Popup Notification JS -->
    <script src="assets/js/components/popup-notifications.js"></script>
    <script>
        function showSuccess() {
            PopupNotification.success("This is a success message!");
        }
        
        function showError() {
            PopupNotification.error("This is an error message!");
        }
        
        function showInfo() {
            PopupNotification.info("This is an info message!");
        }
    </script>
</body>
</html>