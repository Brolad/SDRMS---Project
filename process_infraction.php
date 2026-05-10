<?php
// 1. Error Reporting for Debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');

// 2. Process Form Submission
if (isset($_POST['submit_infraction'])) {
    
    // Sanitize basic inputs
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $offense = mysqli_real_escape_string($conn, $_POST['offense']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $staff_id = 1; // Temporary hardcode due to session bug

    // 3. Handle Point Inputs
    $deduct = isset($_POST['points_deduct']) ? (int)$_POST['points_deduct'] : 0;
    $add = isset($_POST['points_add']) ? (int)$_POST['points_add'] : 0;
    $net_change = $add - $deduct;

    // 4. Fetch Current Points to apply Guardrails (0-100)
    $query = mysqli_query($conn, "SELECT behavior_points FROM students WHERE student_id = '$student_id'");
    $student_data = mysqli_fetch_assoc($query);
    
    if (!$student_data) {
        die("Error: Student not found.");
    }

    $current_points = (int)$student_data['behavior_points'];

    // 5. Calculate New Total with Clamping
    $new_total = $current_points + $net_change;

    if ($new_total > 100) {
        $new_total = 100;
    } elseif ($new_total < 0) {
        $new_total = 0;
    }

    // 6. Calculate Actual change for History Log
    // (If student had 98 and you added 10, the log should only show +2)
    $actual_history_points = $new_total - $current_points;

    // 7. Execute Database Updates
    // Update History Log
    $sql1 = "INSERT INTO infractions (student_id, staff_id, offense_type, points_deducted, description) 
             VALUES ('$student_id', '$staff_id', '$offense', '$actual_history_points', '$desc')";
    
    if (!mysqli_query($conn, $sql1)) {
        die("Error in Log: " . mysqli_error($conn));
    }

    // Update Student Balance
    $sql2 = "UPDATE students SET behavior_points = $new_total WHERE student_id = '$student_id'";
    
    if (mysqli_query($conn, $sql2)) {
        // 8. Display Professional Success Page
        ?>


<!-- HTML  -->
    <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <script src="https://cdn.tailwindcss.com"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <title>Success - Action Recorded</title>
            <meta http-equiv="refresh" content="10;url=staff_dashboard.php?login=success">
        </head>
        <body class="bg-slate-50 flex items-center justify-center min-h-screen font-sans">
            <div class="bg-white p-10 rounded-3xl shadow-2xl border border-slate-100 text-center max-w-sm w-full mx-4">
                <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl animate-bounce">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h1 class="text-2xl font-bold text-slate-800 mb-2 text-balance">Action Successfully Recorded!</h1>
                <p class="text-slate-500 mb-6">Student points updated to <strong><?php echo $new_total; ?></strong>.</p>
                
                <div class="bg-slate-50 rounded-xl py-3 px-4 mb-6 border border-slate-100">
                    <div class="text-[10px] text-slate-400 uppercase font-black tracking-[0.2em] mb-1">Returning to Dashboard in</div>
                    <div class="text-2xl font-black text-indigo-600" id="timer">10</div>
                </div>
                
                <a href="staff_dashboard.php?login=success" class="inline-block w-full bg-indigo-600 text-white px-8 py-4 rounded-2xl font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                    Go Back Now
                </a>
            </div>

            <script>
                // UPDATED: JavaScript countdown starts at 10
                let seconds = 10;
                const timerDisplay = document.getElementById('timer');
                
                const countdown = setInterval(() => {
                    seconds--;
                    if(seconds >= 0) {
                        timerDisplay.innerText = seconds;
                    }
                    if(seconds <= 0) {
                        clearInterval(countdown);
                    }
                }, 1000);
            </script>
        </body>
        </html>
        <?php
        exit();
    } else {
        die("Error in Update: " . mysqli_error($conn));
    }

} else {
    echo "Direct access denied. Please use the report form.";
}
?>