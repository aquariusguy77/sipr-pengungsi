<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? $appConfig['systemName'] }}</title>
    @include('sipr.partials.styles')
</head>
<body>
    <div class="overlay" id="overlay"></div>
    <div class="app-shell">
        @include('sipr.partials.sidebar', ['appConfig' => $appConfig, 'menuItems' => $menuItems, 'iconMap' => $iconMap])

        <main class="main-content">
            @include('sipr.partials.topbar', ['iconMap' => $iconMap])
            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.getElementById("sidebar");
        const sidebarToggle = document.getElementById("sidebarToggle");
        const overlay = document.getElementById("overlay");

        if (sidebar && sidebarToggle && overlay) {
            const closeSidebar = () => {
                sidebar.classList.remove("open");
                overlay.classList.remove("visible");
            };

            sidebarToggle.addEventListener("click", () => {
                sidebar.classList.toggle("open");
                overlay.classList.toggle("visible");
            });

            overlay.addEventListener("click", closeSidebar);

            document.querySelectorAll('.menu-link[href^="#"]').forEach((link) => {
                link.addEventListener("click", () => {
                    if (window.innerWidth <= 980) {
                        closeSidebar();
                    }
                });
            });
        }
    </script>
</body>
</html>
