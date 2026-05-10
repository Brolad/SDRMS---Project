<?php
include('db.php'); 

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: index.php?error=unauthorized");
    exit();
}

// Fetch all students to display in the table
$query = "SELECT * FROM students";
$result = mysqli_query($conn, $query);

// 1. Get Total Students
$total_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM students");
$total_count = mysqli_fetch_assoc($total_res)['total'];

// 2. Get Average Score
$avg_res = mysqli_query($conn, "SELECT AVG(behavior_points) as average FROM students");
$avg_score = round(mysqli_fetch_assoc($avg_res)['average']);

// 3. Get Critical Students (Points below 40)
$crit_res = mysqli_query($conn, "SELECT COUNT(*) as critical FROM students WHERE behavior_points < 40");
$crit_count = mysqli_fetch_assoc($crit_res)['critical'];

// 4. Function to get "Status Label"
function getStatus($points) {
    if ($points >= 90) return ['label' => 'Model Student', 'color' => 'bg-emerald-50 text-emerald-600 border-emerald-100'];
    if ($points >= 60) return ['label' => 'Good Standing', 'color' => 'bg-slate-50 text-slate-600 border-slate-100'];
    if ($points >= 40) return ['label' => 'Warning Phase', 'color' => 'bg-amber-50 text-amber-600 border-amber-100'];
    if ($points > 0)   return ['label' => 'Probational', 'color' => 'bg-orange-50 text-orange-600 border-orange-100'];
    return ['label' => 'EXPELLED', 'color' => 'bg-red-600 text-white border-red-600 animate-pulse']; 
}

$staff_id = isset($_SESSION['user']['staff_id']) ? $_SESSION['user']['staff_id'] : 0;
$display_name = isset($_SESSION['user']['staff_name']) ? $_SESSION['user']['staff_name'] : "Staff Member";

// Fetch Recent Activity
if ($staff_id > 0) {
    $recent_actions = mysqli_query($conn, "SELECT infractions.*, students.full_name 
                                           FROM infractions 
                                           JOIN students ON infractions.student_id = students.student_id 
                                           WHERE staff_id = '$staff_id' 
                                           ORDER BY date_recorded DESC LIMIT 5");
} else {
    $recent_actions = false; 
}
?>


<!-- HTML  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Staff Command | SDRMS</title>
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
                <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </div>
                <div>
                    <span class="block text-sm font-extrabold tracking-tight text-slate-900">SDRMS</span>
                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">Staff Command</span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs font-bold text-slate-500 hidden sm:block">Officer: <?php echo $display_name; ?></span>
                <div class="h-6 w-[1px] bg-slate-200 mx-2"></div>
                <a href="index.php" class="text-xs font-black text-red-500 hover:text-red-600 uppercase tracking-widest">Logout</a>
            </div>
        </div>
    </nav>

    <main class="max-w-[90rem] mx-auto px-6 py-10">
        
        <div class="mb-10">
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter mb-2">Staff Dashboard</h1>
            <p class="text-slate-500 text-sm font-medium tracking-wide">Manage student behavioral records</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="glass-card p-8 rounded-[2rem] shadow-sm relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Enrolled Students</p>
                    <h3 class="text-4xl font-black text-slate-900"><?php echo $total_count; ?></h3>
                </div>
                <i class="fa-solid fa-users absolute -bottom-4 -right-4 text-7xl text-slate-100 group-hover:text-indigo-50 transition-colors"></i>
            </div>

            <div class="glass-card p-8 rounded-[2rem] shadow-sm relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">System Avg Score</p>
                    <h3 class="text-4xl font-black text-slate-900"><?php echo $avg_score; ?>%</h3>
                </div>
                <div class="absolute bottom-6 right-8 w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500" style="width: <?php echo $avg_score; ?>%"></div>
                </div>
            </div>

            <div class="glass-card p-8 rounded-[2rem] shadow-sm relative overflow-hidden group border-red-100 bg-red-50/10">
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-4">Critical Status</p>
                    <h3 class="text-4xl font-black text-red-600"><?php echo $crit_count; ?></h3>
                </div>
                <i class="fa-solid fa-triangle-exclamation absolute -bottom-4 -right-4 text-7xl text-red-500 opacity-5"></i>
            </div>
        </div>

        <div class="glass-card p-3 rounded-2xl mb-8 flex flex-col sm:flex-row items-center gap-4">
            <div class="relative flex-1 w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" id="studentSearch" placeholder="Search identity or matric number..." 
                       class="w-full pl-12 pr-6 py-4 bg-transparent border-none rounded-xl focus:ring-0 text-sm font-semibold text-slate-700 placeholder:text-slate-300">
            </div>
            <div class="px-6 py-2 bg-slate-900 rounded-xl">
                <span class="text-[10px] font-black text-white uppercase tracking-widest">Live Registry</span>
            </div>
        </div>

        <div class="glass-card rounded-[2.5rem] overflow-hidden shadow-sm mb-12">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200/60">
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Full Name</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Identification</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Behavior Metric</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Official Standing</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Administrative</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="hover:bg-slate-50/30 transition-all">
                        <td class="px-8 py-6 font-bold text-slate-800 tracking-tight"><?php echo $row['full_name']; ?></td>
                        <td class="px-8 py-6 text-xs font-mono text-slate-400"><?php echo $row['matric_no']; ?></td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-black <?php echo $row['behavior_points'] < 40 ? 'text-red-500' : 'text-slate-900'; ?>">
                                    <?php echo $row['behavior_points']; ?>
                                </span>
                                <div class="w-12 h-1 bg-slate-100 rounded-full hidden lg:block">
                                    <div class="h-full <?php echo $row['behavior_points'] < 40 ? 'bg-red-500' : 'bg-slate-900'; ?>" style="width: <?php echo $row['behavior_points']; ?>%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <?php $status = getStatus($row['behavior_points']); ?>
                            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border <?php echo $status['color']; ?>">
                                <?php echo $status['label']; ?>
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="record_infraction.php?id=<?php echo $row['student_id']; ?>" 
                               class="inline-flex items-center gap-2 bg-slate-900 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-sm">
                                <i class="fa-solid fa-plus-circle text-xs"></i> Record Action
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="bg-slate-900 rounded-[3rem] p-10 text-white relative overflow-hidden shadow-2xl">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-black uppercase tracking-tight italic">My Recent Administrative Actions</h3>
                    <i class="fa-solid fa-clock-rotate-left text-slate-700 text-2xl"></i>
                </div>
                <div class="space-y-4">
                    <?php if($recent_actions && mysqli_num_rows($recent_actions) > 0): ?>
                        <?php while($act = mysqli_fetch_assoc($recent_actions)): ?>
                            <div class="flex justify-between items-center bg-white/5 p-4 rounded-2xl border border-white/5 hover:border-white/10 transition-all">
                                <div>
                                    <div class="text-xs font-black uppercase text-indigo-400 mb-1"><?php echo $act['full_name']; ?></div>
                                    <div class="text-[10px] text-slate-400 font-medium uppercase tracking-widest"><?php echo $act['offense_type']; ?></div>
                                </div>
                                <div class="text-right">
                                    <div class="font-black text-lg <?php echo $act['points_deducted'] >= 0 ? 'text-emerald-400' : 'text-red-400'; ?>">
                                        <?php echo ($act['points_deducted'] > 0 ? '+' : '') . $act['points_deducted']; ?>
                                    </div>
                                    <div class="text-[8px] font-bold text-slate-600 uppercase"><?php echo date('H:i', strtotime($act['date_recorded'])); ?> HRS</div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest text-center py-4">No recent activity found in your log.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </main>

    <script>
    document.getElementById('studentSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            let name = row.cells[0] ? row.cells[0].textContent.toLowerCase() : "";
            let matric = row.cells[1] ? row.cells[1].textContent.toLowerCase() : "";
            row.style.display = (name.includes(filter) || matric.includes(filter)) ? "" : "none";
        });
    });
    </script>
</body>
</html>