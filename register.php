<?php
include('db.php'); 
// session_start is in db.php

$message = ""; 

if(isset($_POST['register_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $role = $_POST['role'];
    $id_num = mysqli_real_escape_string($conn, $_POST['id_number']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']); 

    if($role == "student") {
        $sql = "INSERT INTO students (full_name, matric_no, password) VALUES ('$name', '$id_num', '$pass')";
    } else {
        $sql = "INSERT INTO staff (staff_name, staff_id_no, password) VALUES ('$name', '$id_num', '$pass')";
    }

    if(mysqli_query($conn, $sql)) {
        $message = "
        <div class='flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-600 p-4 rounded-2xl mb-6 shadow-sm animate-pulse'>
            <i class='fa-solid fa-circle-check'></i>
            <span class='text-xs font-black uppercase tracking-widest'>Identity Verified. Redirecting to Terminal...</span>
        </div>";
        header("refresh:2;url=index.php"); 
    } else {
        $message = "
        <div class='flex items-center gap-3 bg-red-50 border border-red-100 text-red-600 p-4 rounded-2xl mb-6 shadow-sm'>
            <i class='fa-solid fa-triangle-exclamation'></i>
            <span class='text-xs font-black uppercase tracking-widest italic'>Registry Error: " . mysqli_error($conn) . "</span>
        </div>";
    }
}
?>

<!-- HTML  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registry | SDRMS</title>
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
        <div class="text-center mb-8">
            <div class="inline-flex w-16 h-16 bg-slate-900 rounded-[2rem] items-center justify-center text-white shadow-2xl mb-4 rotate-3">
                <i class="fa-solid fa-fingerprint text-2xl"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tighter">New Identity</h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1">Registry Enrollment Office</p>
        </div>

        <div class="glass-card p-8 rounded-[2.5rem] shadow-xl relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-slate-50 rounded-full opacity-50"></div>
            
            <?php echo $message; ?>

            <form action="register.php" method="POST" class="space-y-5 relative z-10">
                
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 tracking-widest">Full Legal Name</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                        <input type="text" name="full_name" required
                            class="w-full pl-10 pr-4 py-3.5 bg-white/50 border border-slate-200 rounded-2xl outline-none transition-all input-focus text-sm font-semibold text-slate-700"
                            placeholder="Full Name">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 tracking-widest">Access Classification</label>
                    <div class="relative">
                        <i class="fa-solid fa-id-badge absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                        <select name="role" required
                            class="w-full pl-10 pr-4 py-3.5 bg-white/50 border border-slate-200 rounded-2xl outline-none transition-all input-focus text-sm font-semibold text-slate-700 appearance-none">
                            <option value="">Select Role</option>
                            <option value="student">Student</option>
                            <option value="staff">Staff/Faculty</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 tracking-widest">ID / Matriculation Number</label>
                    <div class="relative">
                        <i class="fa-solid fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                        <input type="text" name="id_number" required
                            class="w-full pl-10 pr-4 py-3.5 bg-white/50 border border-slate-200 rounded-2xl outline-none transition-all input-focus font-mono text-sm font-semibold text-slate-700"
                            placeholder="UNI/24/XXXX">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 tracking-widest">Access Key (Password)</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                        <input type="password" name="password" id="passwordInput" required
                            class="w-full pl-10 pr-12 py-3.5 bg-white/50 border border-slate-200 rounded-2xl outline-none transition-all input-focus text-sm font-semibold text-slate-700"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-300 hover:text-slate-900 transition-colors">
                            <i class="fa-solid fa-eye text-xs" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" name="register_btn"
                    class="w-full bg-slate-900 hover:bg-indigo-600 text-white font-black text-[11px] uppercase tracking-[0.2em] py-4 rounded-2xl shadow-lg shadow-slate-200 transition-all active:scale-[0.98] mt-4">
                    Create Account
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <p class="text-[16px] font-bold text-slate-400">
                    Have an account? <a href="index.php" class="text-slate-900 hover:text-indigo-600 transition-colors underline decoration-slate-200 underline-offset-4">Log in</a>
                </p>
            </div>
        </div>
        
        <footer class="mt-8 text-center">
            <p class="text-[9px] text-slate-300 font-bold uppercase tracking-[0.4em]">Welcome to Our University</p>
        </footer>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

</body>
</html>