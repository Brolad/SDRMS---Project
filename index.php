<?php
include('db.php'); 
// db.php handles session_start()

$message = "";

if(isset($_POST['login_btn'])) {
    $id_num = mysqli_real_escape_string($conn, trim($_POST['id_number']));
    $pass = mysqli_real_escape_string($conn, trim($_POST['password']));
    $role = $_POST['role'];

    if($role == "student") {
        $query = "SELECT * FROM students WHERE matric_no = '$id_num' AND password = '$pass'";
        $result = mysqli_query($conn, $query);
        
        if(mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            $_SESSION['user'] = $user_data;
            $_SESSION['role'] = 'student';
            header("Location: student_dashboard.php");
            exit();
        } else {
            $message = "
            <div class='flex items-center gap-3 bg-red-50 border border-red-100 text-red-600 p-4 rounded-2xl mb-6 shadow-sm'>
                <i class='fa-solid fa-shield-xmark'></i>
                <span class='text-[10px] font-black uppercase tracking-widest'>Invalid Student Credentials</span>
            </div>";
        }
    } else {
        $query = "SELECT * FROM staff WHERE staff_id_no = '$id_num' AND password = '$pass'";
        $result = mysqli_query($conn, $query);
        
        if(mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            $_SESSION['user'] = $user_data;
            $_SESSION['role'] = 'staff';
            header("Location: staff_dashboard.php");
            exit();
        } else {
            $message = "
            <div class='flex items-center gap-3 bg-red-50 border border-red-100 text-red-600 p-4 rounded-2xl mb-6 shadow-sm'>
                <i class='fa-solid fa-lock-keyhole-slash'></i>
                <span class='text-[10px] font-black uppercase tracking-widest'>Invalid Staff Authentication</span>
            </div>";
        }
    }
}
?>


<!-- HTML  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication | SDRMS</title>
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

    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <div class="inline-flex w-14 h-14 bg-slate-900 rounded-2xl items-center justify-center text-white shadow-2xl mb-4 -rotate-3 transition-transform hover:rotate-0">
                <i class="fa-solid fa-key text-xl"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900">Log back In</h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-2">Welcome back!</p>
        </div>

        <div class="glass-card p-10 rounded-[2.5rem] shadow-xl relative overflow-hidden">
            <div class="absolute -top-12 -left-12 w-32 h-32 bg-indigo-50 rounded-full opacity-40 blur-3xl"></div>
            
            <?php echo $message; ?>

            <form action="index.php" method="POST" class="space-y-6 relative z-10">
                
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 tracking-widest px-1">Identity Domain</label>
                    <div class="relative">
                        <i class="fa-solid fa-user-shield absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                        <select name="role" required
                            class="w-full pl-10 pr-4 py-3.5 bg-white/50 border border-slate-200 rounded-2xl outline-none transition-all input-focus text-sm font-semibold text-slate-700 appearance-none cursor-pointer">
                            <option value="student">Student Portal</option>
                            <option value="staff">Staff Portal</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 tracking-widest px-1">Credential ID</label>
                    <div class="relative">
                        <i class="fa-solid fa-id-card-clip absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                        <input type="text" name="id_number" required
                            class="w-full pl-10 pr-4 py-3.5 bg-white/50 border border-slate-200 rounded-2xl outline-none transition-all input-focus text-sm font-semibold text-slate-700 placeholder:text-slate-300"
                            placeholder="Enter Matric or Staff ID">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Password</label>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-vault absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                        <input type="password" id="loginPass" name="password" required
                            class="w-full pl-10 pr-12 py-3.5 bg-white/50 border border-slate-200 rounded-2xl outline-none transition-all input-focus text-sm font-semibold text-slate-700"
                            placeholder="••••••••">
                        <button type="button" onclick="toggleLoginPass()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-300 hover:text-slate-900 transition-colors">
                            <i class="fa-solid fa-eye text-xs" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" name="login_btn"
                    class="w-full bg-slate-900 hover:bg-slate-800 text-white font-black text-[11px] uppercase tracking-[0.2em] py-4 rounded-2xl shadow-lg shadow-slate-200 transition-all active:scale-[0.98] mt-4 flex items-center justify-center gap-2">
                    Sign In <i class="fa-solid fa-arrow-right-long text-[10px]"></i>
                </button>
            </form>

            <div class="mt-10 pt-6 border-t border-slate-100 text-center">
                <p class="text-[14px] font-bold text-slate-400 uppercase tracking-tight">
                    No Account? &nbsp; <a href="register.php" class="text-slate-900 hover:text-indigo-600 transition-colors underline decoration-slate-200 underline-offset-4 decoration-2">Create Account</a>
                </p>
            </div>
        </div>

        <footer class="mt-8 text-center">
            <p class="text-[9px] text-slate-300 font-bold uppercase tracking-[0.4em]">Welcome back dear member</p>
        </footer>
    </div>

    <script>
        function toggleLoginPass() {
            const passInput = document.getElementById('loginPass');
            const eye = document.getElementById('eyeIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eye.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passInput.type = 'password';
                eye.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>