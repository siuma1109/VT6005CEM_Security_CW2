<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com; style-src 'self' 'unsafe-inline';">
    <title>HKID Card Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Fallback styles in case Tailwind fails to load */
        :root {
            --primary-black: #000000;
            --secondary-black: #333333;
            --gov-gray: #1F2937;
            --gov-light-gray: #374151;
            --gov-text: #F3F4F6;
            --gov-text-secondary: #D1D5DB;
        }

        /* Light theme */
        .light {
            --primary-black: #FFFFFF;
            --secondary-black: #E5E7EB;
            --gov-gray: #E5E7EB;
            --gov-light-gray: #F3F4F6;
            --gov-text: #1F2937;
            --gov-text-secondary: #4B5563;
        }

        /* Dark theme */
        .dark {
            --primary-black: #000000;
            --secondary-black: #333333;
            --gov-gray: #1F2937;
            --gov-light-gray: #374151;
            --gov-text: #F3F4F6;
            --gov-text-secondary: #D1D5DB;
        }

        .bg-primary-black {
            background-color: var(--primary-black);
        }

        .bg-secondary-black {
            background-color: var(--secondary-black);
        }

        .bg-gov-gray {
            background-color: var(--gov-gray);
        }

        .bg-gov-light-gray {
            background-color: var(--gov-light-gray);
        }

        .text-gov-text {
            color: var(--gov-text);
        }

        .text-gov-text-secondary {
            color: var(--gov-text-secondary);
        }

        /* Hover styles */
        .hover-light {
            transition: background-color 0.3s ease;
        }

        .hover-light:hover {
            background-color: #CBD5E1 !important;
        }

        .dark .hover-light:hover {
            background-color: #4B5563 !important;
        }
    </style>
</head>

<body class="bg-primary-black text-gov-text min-h-screen">
    <?php include 'components/header.php'; ?>

    <main class="container mx-auto px-4 py-8">
        <?=$content?>
    </main>

    <?php include 'components/footer.php'; ?>

    <!-- Security Scripts -->
    <script src="/resources/js/base.js"></script>
</body>

</html>