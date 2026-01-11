<!DOCTYPE html>
<html lang="tr"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>ÖPSP - Öğrenci Sunum Platformu</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"/>
<script>
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              primary: "#FFD600", // Vibrant Yellow from screenshot
              "background-light": "#FFFFFF",
              "background-dark": "#0F172A", // Deep slate for dark mode
              "text-light": "#1F2937",
              "text-dark": "#F3F4F6",
            },
            fontFamily: {
              display: ["Inter", "sans-serif"],
              sans: ["Inter", "sans-serif"],
            },
            borderRadius: {
              DEFAULT: "0.75rem",
              'xl': '1rem',
              '2xl': '1.5rem',
            },
          },
        },
      };
    </script>
<style>html { scroll-behavior: smooth; }.bg-gradient-blur {
            position: absolute;
            top: -10%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 214, 0, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            filter: blur(40px);
            z-index: 0;
            pointer-events: none;
        }
        .dark .bg-gradient-blur {
            background: radial-gradient(circle, rgba(255, 214, 0, 0.1) 0%, rgba(15, 23, 42, 0) 70%);
        }
    </style>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
  </head>
<body class="bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark font-sans antialiased transition-colors duration-300 min-h-screen flex flex-col relative overflow-x-hidden">
    <div class="bg-gradient-blur"></div>
    <div class="bg-gradient-blur" style="top: auto; bottom: -5%; left: -10%; right: auto; width: 250px; height: 250px;"></div>
    <header class="w-full px-6 py-5 flex justify-between items-center z-10">
        <div class="flex items-center gap-2">
            <span class="text-xl bg-primary rounded-lg p-1 font-bold tracking-tight">ÖPSP</span>
        </div>
        <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors focus:outline-none">

            <span class="text-xl bg-primary rounded-lg p-1 font-bold tracking-tight"><a href="login.php">Giriş Yap</a></span>
        
            
            <a href="register.php">Üye Ol</a>
       
        </button>
    </header>
    <main class="flex-grow flex flex-col justify-center items-center px-6 pt-8 pb-12 z-10 text-center max-w-md mx-auto w-full">
        <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 mb-8 animate-fade-in-up">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                            Öğrenci Sunumlarının Geleceği
            </span>
        </div>
        <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight mb-6 text-gray-900 dark:text-white">
                    Çalışmalarınızı <br/>Sergileyin,
                    <span class="block text-primary mt-1">Dünyaya İlham Verin</span>
        </h1>
        <p class="text-base sm:text-lg text-gray-600 dark:text-gray-400 mb-10 leading-relaxed max-w-sm mx-auto">
                Öğrencilerin projelerini etkileyici videolar, etkileşimli slaytlar ve sürükleyici galeriler aracılığıyla yüklemeleri, sunmaları ve paylaşmaları için en iyi platform.
        </p>
        <div class="w-full space-y-4">
            <button class="w-full bg-primary hover:bg-yellow-400 text-gray-900 font-bold py-4 px-6 rounded-full shadow-lg shadow-yellow-500/20 transform transition hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 text-lg">
                <span>Ücretsiz Sunuma Başlayın</span>
                <span class="material-icons-round text-xl">arrow_forward</span>
            </button>
            <button class="w-full bg-transparent hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-900 dark:text-white font-semibold py-4 px-6 rounded-full border-2 border-gray-200 dark:border-gray-700 transition-colors flex items-center justify-center gap-2 text-lg">
                <span class="material-icons-round text-xl">play_circle_outline</span>
                <span>Demoyu İzle</span>
            </button>
        </div>
        <div class="mt-12 flex items-center justify-center gap-4 opacity-60">
            <div class="flex -space-x-3">
                <img alt="User Avatar" class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-900 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC35D05vMtVn7DNrbqBC9BxfaG7ph2XlOtnuwPz5QWTRadeL02kgW8Kljrbf8LNoJl4PsZmCuWyb--ClKszSGb-BHxP-Mqy25FykTXiGTUqEkRArqWcsz4-hGJ0C7-RqX8phmuv1EQUcwChlhXLCJa_U7DsZ7Lr2JuoFIeKSwaL93i9zoQJjN58XAMu3qujIPtC5BXEBRxmMryRihOHFkFjhB4U_bXx8BAKS28Z5bH1cbVhJe0D4qH4n-uMQ6y8aPw31WolwhlpM7He"/>
                <img alt="User Avatar" class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-900 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCNb3KxsK4U5Sto85NC4enjk8PEsOXKi0z1cvveFpM0ivBfcVekLU-cVjqxwR1wtmBqaV1L6NaNK1QNUKRbBOspqZudslXrH5_xMjRJkAVOELgFLKLjZS3zoMBi9ozpBS6Qd79We7SfWRcgq7SDjcUKcHsBqJPRvIQfy_k3cD7dl6oSqdRMvw3gURmMH4AbevZJW9C7ND-z6wJonxJ2yLCjWQdaeOoHiqe42MSYVmmLfoiX89AiJ_xgbW9WC6OFHoy2PARBN0bB8xKP"/>
                <img alt="User Avatar" class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-900 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCEodUTnbyy_0Vf4e_Tr8Y3e0a0uq-_pVCnr_8Xo5-YK2hdk_taeW46q2vGI6WbQcCoVC3zEew_YijnD9M-Yn0lSQ3Q7Z_ZrYgco6S7Dbg7LaPP3kCPzqQOxHMnXozceO6bjntL-gFSv_hONOlPA_jtVCtH4JO9BDii3EMx5kXDmhgLViGgkmwNZ-vAuqKcdJbhGixB9hflArTF5uG73yD0s0RHg6djKXnqiYsn_gWbNvanRJBXqQqhZLuzA8Bee5FwAtBLMvHe6tAj"/>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                <span class="font-bold text-gray-800 dark:text-gray-200">1000+</span> Öğrenci katıldı
            </p>
        </div>
    </main>
    <footer class="w-full text-center py-6 border-t border-gray-100 dark:border-gray-800 z-10">
        <p class="text-xs text-gray-400 dark:text-gray-600">© 2025 ÖPSP Inc. Tüm hakları saklıdır.</p>
    </footer>

</body>
</html>