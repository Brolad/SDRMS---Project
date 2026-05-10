<?php
include('db.php'); 
// session_start is in db.php

$student_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

// Fetch student details for the header
$res = mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$student_id'");
$student = mysqli_fetch_assoc($res); 

if (!$student) {
    header("Location: staff_dashboard.php");
    exit();
}

$message = "";

if (isset($_POST['submit_infraction'])) {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $offense = mysqli_real_escape_string($conn, $_POST['offense']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $deduct = (int)$_POST['points_deduct'];
    $add = (int)$_POST['points_add'];
    $net_change = $add - $deduct;
    
    if ($net_change == 0 && $add == 0 && $deduct == 0) {
        header("Location: staff_dashboard.php");
        exit();
    }

    // Get staff ID from session for true corporate accountability
    $staff_id = isset($_SESSION['user']['staff_id']) ? $_SESSION['user']['staff_id'] : 0;

    // 1. Log the History 
    $sql_log = "INSERT INTO infractions (student_id, staff_id, offense_type, points_deducted, description) 
                VALUES ('$student_id', '$staff_id', '$offense', '$net_change', '$description')";

    // 2. Update Student Balance
    $sql_update = "UPDATE students SET behavior_points = behavior_points + ($net_change) WHERE student_id = '$student_id'";

    if (mysqli_query($conn, $sql_log) && mysqli_query($conn, $sql_update)) {
        $message = "
        <div class='bg-slate-900 text-white p-8 rounded-[2rem] text-center shadow-2xl animate-in fade-in zoom-in duration-300'>
            <div class='w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4'>
                <i class='fa-solid fa-check text-2xl'></i>
            </div>
            <h2 class='text-xl font-black uppercase tracking-tighter'>Record Updated</h2>
            <p class='text-slate-400 text-xs mt-2'>The record has been updated and stored.</p>
            <a href='staff_dashboard.php' class='inline-block mt-6 px-6 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all'>Return to Dashboard</a>
        </div>";
    } else {
        $message = "<div class='p-4 bg-red-50 text-red-600 rounded-2xl text-xs font-bold'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>


<!-- HTML  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Filing | SDRMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border: 1px solid rgba(226, 232, 240, 0.8); }
        .bg-pattern { background-image: radial-gradient(#e2e8f0 0.5px, transparent 0.5px); background-size: 24px 24px; }
        .input-focus:focus { border-color: #0f172a; box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.05); }
    </style>
</head>
<body class="bg-[#fcfdfe] bg-pattern min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-xl">
        
        <?php if ($message != ""): ?>
            <?php echo $message; ?>
        <?php else: ?>

        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 pb-[2px] italic">Report or Update</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em]">Behavioral Adjustment Protocol</p>
            </div>
            <div class="text-right">
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Student:</span>
                <span class="text-sm font-bold text-slate-900"><?php echo $student['full_name']; ?></span>
            </div>
        </div>

        <div class="glass-card p-8 md:p-10 rounded-[3rem] shadow-xl relative overflow-hidden">
            <form action="" method="POST" class="space-y-6">
                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest">Violation Classification</label>
                    <select name="offense" required 
                            class="w-full px-5 py-4 bg-white/50 border border-slate-200 rounded-2xl outline-none transition-all input-focus text-sm font-bold text-slate-700 appearance-none cursor-pointer">
                        <option value="Minor Disruption">Minor Disruption (Category C)</option>
                        <option value="Major Misconduct">Major Misconduct (Category B)</option>
                        <option value="Severe Violation">Severe Violation (Category A)</option>
                        <option value="Exceptional Commendation">Exceptional Commendation (Positive)</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-5">
                        <label class="block text-[10px] font-black text-red-400 mb-3 uppercase tracking-widest">Points Removal</label>
                        <div class="relative">
                            <i class="fa-solid fa-minus absolute left-4 top-1/2 -translate-y-1/2 text-red-300"></i>
                            <input type="number" name="points_deduct" value="0" min="0"
                                   class="w-full pl-10 pr-4 py-3 bg-white border border-red-100 rounded-xl outline-none focus:ring-2 focus:ring-red-200 text-lg font-black text-red-600">
                        </div>
                    </div>

                    <div class="p-5">
                        <label class="block text-[10px] font-black text-emerald-400 mb-3 uppercase tracking-widest">Points Addition</label>
                        <div class="relative">
                            <i class="fa-solid fa-plus absolute left-4 top-1/2 -translate-y-1/2 text-emerald-300"></i>
                            <input type="number" name="points_add" value="0" min="0"
                                   class="w-full pl-10 pr-4 py-3 bg-white border border-emerald-100 rounded-xl outline-none focus:ring-2 focus:ring-emerald-200 text-lg font-black text-emerald-600">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest px-1">Description</label>
                    <textarea name="description" rows="4" required
                              class="w-full px-5 py-4 bg-white/50 border border-slate-200 rounded-2xl outline-none transition-all input-focus text-sm font-medium text-slate-600 placeholder:text-slate-300 italic" 
                              placeholder="Explain the reason for updating..."></textarea>
                </div>

                <div class="pt-4 flex flex-col md:flex-row gap-4">
                    <a href="staff_dashboard.php" class="flex-1 px-6 py-4 rounded-2xl border border-slate-200 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 hover:bg-slate-50 transition-all text-center">
                        Go back
                    </a>
                    <button type="submit" name="submit_infraction" 
                            class="flex-[2] bg-slate-900 hover:bg-red-600 text-white font-black text-[11px] uppercase tracking-[0.2em] py-4 rounded-2xl shadow-xl shadow-slate-200 transition-all active:scale-[0.98]">
                        Update Record
                    </button>
                </div>
            </form>
        </div>
        
        <?php endif; ?>

        <footer class="mt-10 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-slate-100 rounded-full">
                <div class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-pulse"></div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Authorized Staff Personnel Only</span>
            </div>
        </footer>
    </div>

</body>
</html>