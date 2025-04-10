<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automated LMS</title>
    <!-- <title>Automated Library Email Reminder</title> -->
</head>
<body>
    <!-- <h2>Library Email Reminder System Running...</h2> -->
    <!-- <p>This page will automatically refresh every hour to check for upcoming return dates.</p> -->

    <?php include 'send_reminder.php'; ?>

    <script>
        // Refresh every 1 hour (3600000 ms)
        setInterval(function() {
            window.location.reload();
        }, 3600000);
    </script>
</body>
</html>
