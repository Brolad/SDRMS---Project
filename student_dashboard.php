<?php
include('db.php'); 
// session_start is in db.php

// 1. Security Guard: If not a student, send back to login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php?error=unauthorized");
    exit();
}

// 2. Get Student ID from the SESSION (Safe and Private)
$student_id = $_SESSION['user']['student_id']; 

// 3. Fetch Student Details
$res = mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$student_id'");
$student = mysqli_fetch_assoc($res);

if (!$student) {
    die("Student record not found.");
}

// 2. Fetch Infraction/Commendation History
$history_query = "SELECT infractions.*, staff.staff_name 
                  FROM infractions 
                  JOIN staff ON infractions.staff_id = staff.staff_id 
                  WHERE student_id = '$student_id' 
                  ORDER BY date_recorded DESC";
$history = mysqli_query($conn, $history_query);
?>


<!-- HTML  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>SDRMS | <?php echo $student['full_name']; ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border: 1px solid rgba(226, 232, 240, 0.8); }
        .bg-pattern { background-image: radial-gradient(#e2e8f0 0.5px, transparent 0.5px); background-size: 24px 24px; }
    </style>
</head>
<body class="bg-[#fcfdfe] bg-pattern min-h-screen text-slate-900">

    <nav class="sticky top-0 z-50 glass-card border-b border-slate-200/60 px-6 py-3">
        <div class="max-w-[90rem] mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white rotate-3 group-hover:rotate-0 transition-transform">
                    <i class="fa-solid fa-bolt-lightning text-sm"></i>
                </div>
                <div>
                    <span class="block text-sm font-extrabold tracking-tight text-slate-900">SDRMS</span>
                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">Institutional Portal</span>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="hidden md:block h-8 w-[1px] bg-slate-200"></div>
                <a href="index.php" class="text-xs font-bold text-slate-500 hover:text-red-500 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-power-off"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-[90rem] mx-auto px-6 py-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-12">
            
            <div class="lg:col-span-3 glass-card rounded-[2.5rem] p-8 flex flex-col md:flex-row items-center gap-8 shadow-sm">
                <div class="relative">
                    <div class="w-32 h-32 bg-slate-100 rounded-[2rem] flex items-center justify-center border-4 border-white shadow-inner">
                        <span class="text-5xl font-black text-slate-300"><?php echo strtoupper(substr($student['full_name'], 0, 1)); ?></span>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-10 h-10 <?php echo $student['behavior_points'] <= 0 ? 'bg-red-500' : 'bg-emerald-500'; ?> border-4 border-white rounded-2xl flex items-center justify-center text-white shadow-lg">
                        <i class="fa-solid <?php echo $student['behavior_points'] <= 0 ? 'fa-xmark' : 'fa-check'; ?> text-xs"></i>
                    </div>
                </div>

                <div class="text-center md:text-left flex-1">
                    <div class="flex flex-col md:flex-row md:items-center gap-3 mb-2">
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?php echo $student['full_name']; ?></h1>
                        <?php if ($student['behavior_points'] <= 0): ?>
                            <span class="inline-block px-3 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-bold uppercase tracking-widest border border-red-100 self-center">Status - Expelled</span>
                        <?php else: ?>
                            <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold uppercase tracking-widest border border-emerald-100 self-center">Status - Active</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-slate-600 font-medium text-sm mb-6 uppercase tracking-widest italic"><?php echo $student['matric_no']; ?></p>
                    
                    <div class="max-w-md">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[13px] font-bold text-slate-400 uppercase tracking-tighter">Behavioral Rating</span>
                            <span class="text-[13px] font-extrabold text-slate-900"><?php echo $student['behavior_points']; ?>/100</span>
                        </div>
                        <div class="h-2 w-full bg-slate-100 rounded-full p-0.5">
                            <div class="h-full rounded-full transition-all duration-1000 <?php echo $student['behavior_points'] < 40 ? 'bg-red-500' : 'bg-slate-900'; ?>" style="width: <?php echo $student['behavior_points']; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-[2.5rem] p-8 flex flex-col items-center justify-center text-center shadow-sm relative overflow-hidden">
                <div class="relative z-10">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-1">Current Points</span>
                    <span class="text-6xl font-black text-slate-900 tracking-tighter"><?php echo $student['behavior_points']; ?></span>
                </div>
                <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-slate-50 rounded-full mix-blend-multiply opacity-50"></div>
            </div>
        </div>

        <div class="flex items-center gap-4 mb-8">
            <h2 class="text-sm font-black text-slate-900 uppercase tracking-[0.2em]">Official Activity Ledger</h2>
            <div class="h-[1px] flex-1 bg-slate-200"></div>
            <span class="text-[10px] font-bold text-slate-400"><?php echo mysqli_num_rows($history); ?> Logged Events</span>
        </div>

        <div class="glass-card rounded-[2rem] overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200/60">
                        <th class="px-8 py-5 text-[12px] font-black text-slate-400 uppercase tracking-[0.2em]">Date</th>
                        <th class="px-8 py-5 text-[12px] font-black text-slate-400 uppercase tracking-[0.2em]">Classification</th>
                        <th class="px-8 py-5 text-[12px] font-black text-slate-400 uppercase tracking-[0.2em]">Record Description</th>
                        <th class="px-8 py-5 text-[12px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Impact</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(mysqli_num_rows($history) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($history)): ?>
                        <tr class="hover:bg-white transition-colors group">
                            <td class="px-8 py-7">
                                <span class="text-[13px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-md uppercase tracking-tighter">
                                    <?php echo date('M d, Y', strtotime($row['date_recorded'])); ?>
                                </span>
                            </td>
                            <td class="px-8 py-7">
                                <?php 
                                if ($row['points_deducted'] > 0) {
                                    echo '<span class="w-2 h-2 rounded-full bg-emerald-400 inline-block mr-2"></span> <span class="text-[13px] font-bold text-slate-500 uppercase tracking-widest">Commendation</span>'; 
                                } else {
                                    $pts = abs($row['points_deducted']);
                                    $color = $pts >= 50 ? 'bg-red-500' : ($pts >= 20 ? 'bg-slate-900' : 'bg-slate-300');
                                    $label = $pts >= 50 ? 'Critical' : ($pts >= 20 ? 'Major' : 'Minor');
                                    echo '<span class="w-2 h-2 rounded-full '.$color.' inline-block mr-2"></span> <span class="text-[13px] font-bold text-slate-500 uppercase tracking-widest">Infraction ('.$label.')</span>';
                                }
                                ?>
                            </td>
                            <td class="px-8 py-7">
                                <div class="text-[13px] font-bold text-slate-800 uppercase tracking-tight mb-1"><?php echo $row['offense_type']; ?></div>
                                <p class="text-[13px] text-slate-400 leading-relaxed italic">"<?php echo $row['description']; ?>"</p>
                                <div class="mt-2 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Validated by: <?php echo $row['staff_name']; ?></div>
                            </td>
                            <td class="px-8 py-7 text-right">
                                <span class="text-[16px] font-black <?php echo $row['points_deducted'] >= 0 ? 'text-emerald-500' : 'text-red-500'; ?>">
                                    <?php echo ($row['points_deducted'] > 0 ? '+' : '') . $row['points_deducted']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <i class="fa-solid fa-leaf text-slate-100 text-6xl mb-4"></i>
                                <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Your record is currently clean.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>