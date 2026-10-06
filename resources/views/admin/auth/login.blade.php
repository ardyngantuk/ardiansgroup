<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Admin | Ardians Store</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#11100E',
                        dark: '#181613',
                        gold: '#C7A15A',
                        goldLight: '#E1C47B',
                        cream: '#F2EEE5',
                        muted: '#99938A',
                        line: '#302C25'
                    }
                }
            }
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="admin-login-page min-h-screen antialiased">

    <main class="min-h-screen lg:grid lg:grid-cols-[1.05fr_.95fr]">

        <!-- =====================================================
             LEFT BRAND PANEL
        ====================================================== -->
        <section
            class="relative hidden min-h-screen overflow-hidden
                   border-r border-[#302C25] bg-[#181613]
                   lg:flex lg:flex-col lg:justify-between"
        >

            <!-- subtle texture -->
            <div class="grain absolute inset-0 opacity-30"></div>

            <!-- gold ambient -->
            <div
                class="absolute -left-40 -top-40 h-[500px] w-[500px]
                       rounded-full bg-[#C7A15A]/[0.06] blur-3xl"
            ></div>

            <div class="relative z-10 p-12 xl:p-16">

                <!-- Logo -->
                <div>

                    <p class="text-[10px] font-semibold
                              tracking-[0.42em] text-[#C7A15A]">
                        ARDIANS
                    </p>

                    <p class="mt-1 font-serif text-[30px]
                              leading-none tracking-tight text-[#F2EEE5]">
                        STORE<span class="text-[#C7A15A]">.</span>
                    </p>

                </div>


                <!-- Main copy -->
                <div class="mt-[22vh] max-w-xl">

                    <div class="mb-7 flex items-center gap-3">

                        <span class="h-px w-10 bg-[#C7A15A]"></span>

                        <p class="text-[10px] font-semibold uppercase
                                  tracking-[0.3em] text-[#C7A15A]">
                            Ruang Admin
                        </p>

                    </div>


                    <h1
                        class="font-serif text-6xl font-normal
                               leading-[0.96] tracking-[-0.03em]
                               text-[#F2EEE5] xl:text-7xl"
                    >
                        Kelola toko,
                        <br>
                        <span class="italic text-[#C7A15A]">
                            tanpa ribet.
                        </span>
                    </h1>


                    <p
                        class="mt-8 max-w-md text-sm leading-7
                               text-[#99938A]"
                    >
                        Kelola produk, pantau stok, dan proses pesanan
                        Ardians Store dari satu ruang kerja yang sederhana.
                    </p>

                </div>

            </div>


            <!-- Bottom information -->
            <div class="relative z-10 p-12 xl:p-16">

                <div class="mb-5 h-px w-full bg-[#302C25]"></div>

                <div class="flex items-center justify-between">

                    <p class="text-[10px] uppercase tracking-[0.2em]
                              text-[#706A60]">
                        Admin Panel
                    </p>

                    <p class="text-[10px] text-[#706A60]">
                        © 2026 Ardians Store
                    </p>

                </div>

            </div>

        </section>


        <!-- =====================================================
             RIGHT LOGIN
        ====================================================== -->
        <section
            class="relative flex min-h-screen items-center
                   justify-center bg-[#11100E] px-6 py-12
                   sm:px-10"
        >

            <!-- Mobile logo -->
            <div class="absolute left-6 top-7 sm:left-10 lg:hidden">

                <p class="text-[9px] font-semibold
                          tracking-[0.38em] text-[#C7A15A]">
                    ARDIANS
                </p>

                <p class="font-serif text-[25px] leading-none
                          text-[#F2EEE5]">
                    STORE<span class="text-[#C7A15A]">.</span>
                </p>

            </div>


            <div class="w-full max-w-[420px]">

                <!-- Heading -->
                <div class="mb-9">

                    <p class="mb-4 text-[10px] font-semibold uppercase
                              tracking-[0.3em] text-[#C7A15A]">
                        Admin Access
                    </p>

                    <h2
                        class="font-serif text-4xl font-normal
                               tracking-tight text-[#F2EEE5]
                               sm:text-5xl"
                    >
                        Selamat datang
                        <br>
                        kembali.
                    </h2>

                    <p class="mt-4 text-sm leading-6 text-[#99938A]">
                        Masuk untuk mengelola toko Anda.
                    </p>

                </div>


                <!-- Error -->
                @if ($errors->any())

                    <div
                        class="mb-6 border border-red-400/20
                               bg-red-400/[0.06] px-4 py-3"
                    >

                        <div class="flex gap-3">

                            <span class="mt-1 h-1.5 w-1.5
                                         shrink-0 rounded-full
                                         bg-red-400">
                            </span>

                            <p class="text-xs leading-5 text-red-300">
                                {{ $errors->first() }}
                            </p>

                        </div>

                    </div>

                @endif


                <!-- Login Form -->
                <form
                    method="POST"
                    action="{{ route('admin.login.store') }}"
                    class="space-y-6"
                >

                    @csrf


                    <!-- Email -->
                    <div>

                        <label
                            for="email"
                            class="mb-2 block text-[11px]
                                   font-medium uppercase
                                   tracking-[0.12em] text-[#99938A]"
                        >
                            Email
                        </label>

                        <div class="relative">

                            <span
                                class="pointer-events-none absolute
                                       left-4 top-1/2 -translate-y-1/2
                                       text-[#706A60]"
                            >

                                <svg
                                    width="17"
                                    height="17"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                >
                                    <rect
                                        x="3"
                                        y="5"
                                        width="18"
                                        height="14"
                                        rx="2"
                                    />
                                    <path d="m3 7 9 6 9-6"/>
                                </svg>

                            </span>


                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="admin@ardiansstore.com"
                                class="h-13 w-full rounded-none
                                       border border-[#302C25]
                                       bg-[#181613]
                                       py-3.5 pl-11 pr-4
                                       text-sm text-[#F2EEE5]
                                       placeholder:text-[#5F5A52]
                                       outline-none transition
                                       focus:border-[#C7A15A]
                                       focus:ring-1
                                       focus:ring-[#C7A15A]/20"
                            >

                        </div>

                    </div>


                    <!-- Password -->
                    <div>

                        <div class="mb-2 flex items-center justify-between">

                            <label
                                for="password"
                                class="text-[11px] font-medium
                                       uppercase tracking-[0.12em]
                                       text-[#99938A]"
                            >
                                Password
                            </label>

                        </div>


                        <div class="relative">

                            <span
                                class="pointer-events-none absolute
                                       left-4 top-1/2 -translate-y-1/2
                                       text-[#706A60]"
                            >

                                <svg
                                    width="17"
                                    height="17"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                >
                                    <rect
                                        x="4"
                                        y="10"
                                        width="16"
                                        height="11"
                                        rx="2"
                                    />
                                    <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                                </svg>

                            </span>


                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan password"
                                class="h-13 w-full rounded-none
                                       border border-[#302C25]
                                       bg-[#181613]
                                       py-3.5 pl-11 pr-12
                                       text-sm text-[#F2EEE5]
                                       placeholder:text-[#5F5A52]
                                       outline-none transition
                                       focus:border-[#C7A15A]
                                       focus:ring-1
                                       focus:ring-[#C7A15A]/20"
                            >


                            <button
                                type="button"
                                onclick="togglePassword()"
                                class="absolute right-0 top-0
                                       flex h-full w-12 items-center
                                       justify-center text-[#706A60]
                                       transition hover:text-[#C7A15A]"
                                aria-label="Tampilkan password"
                            >

                                <svg
                                    id="eyeOpen"
                                    width="17"
                                    height="17"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                >
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>


                                <svg
                                    id="eyeClosed"
                                    class="hidden"
                                    width="17"
                                    height="17"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                >
                                    <path d="m3 3 18 18"/>
                                    <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                                    <path d="M9.9 4.2A10.9 10.9 0 0 1 12 4c6.5 0 10 8 10 8a17 17 0 0 1-3.1 4.2"/>
                                    <path d="M6.6 6.6C3.5 8.5 2 12 2 12s3.5 8 10 8c1.5 0 2.8-.3 4-.8"/>
                                </svg>

                            </button>

                        </div>

                    </div>


                    <!-- Submit -->
                    <button
                        type="submit"
                        class="group relative flex h-13 w-full
                               items-center justify-center
                               overflow-hidden bg-[#C7A15A]
                               px-5 text-sm font-semibold
                               text-[#11100E]
                               transition
                               hover:bg-[#D5B66F]
                               active:scale-[0.99]"
                    >

                        <span>
                            Masuk ke admin
                        </span>

                        <svg
                            class="ml-3 transition-transform
                                   group-hover:translate-x-1"
                            width="17"
                            height="17"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M5 12h14"/>
                            <path d="m13 6 6 6-6 6"/>
                        </svg>

                    </button>

                </form>


                <!-- Divider -->
                <div class="my-8 flex items-center gap-4">

                    <div class="h-px flex-1 bg-[#302C25]"></div>

                    <span class="text-[9px] uppercase
                                 tracking-[0.2em] text-[#5F5A52]">
                        Ardians Store
                    </span>

                    <div class="h-px flex-1 bg-[#302C25]"></div>

                </div>


                <!-- Back -->
                <a
                    href="{{ route('products.index') }}"
                    class="group flex items-center justify-center
                           gap-2 text-xs text-[#706A60]
                           transition hover:text-[#C7A15A]"
                >

                    <svg
                        class="transition-transform
                               group-hover:-translate-x-1"
                        width="15"
                        height="15"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path d="M19 12H5"/>
                        <path d="m12 19-7-7 7-7"/>
                    </svg>

                    Kembali ke toko

                </a>


                <!-- Security note -->
                <div class="mt-10 flex items-center justify-center gap-2">

                    <svg
                        width="13"
                        height="13"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        class="text-[#5F5A52]"
                    >
                        <rect
                            x="4"
                            y="10"
                            width="16"
                            height="11"
                            rx="2"
                        />
                        <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                    </svg>

                    <p class="text-[10px] text-[#5F5A52]">
                        Halaman khusus administrator
                    </p>

                </div>

            </div>

        </section>

    </main>


    <script>

        function togglePassword() {

            const password = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            if (password.type === 'password') {

                password.type = 'text';

                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');

            } else {

                password.type = 'password';

                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');

            }
        }

    </script>

</body>
</html>