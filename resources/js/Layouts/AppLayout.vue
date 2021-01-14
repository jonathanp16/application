<template>
    <div>
        <jet-banner />

        <div class="min-h-screen bg-gray-100">
            <!-- Navigation Bar -->
            <app-navigation />
            <Navbar />

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header"></slot>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot></slot>
            </main>

            <!-- Modal Portal -->
            <portal-target name="modal" multiple />

            <!-- Footer Section -->
            <app-footer />
        </div>
    </div>
</template>

<script>
    import JetBanner from '@src/Jetstream/Banner'
    import AppNavigation from '@src/Layouts/Partials/Navigation';
    import AppFooter from '@src/Layouts/Partials/Footer';
    import Navbar from '@src/Components/Navbar/Navbar';

    export default {
        components: {
            JetBanner,
            AppNavigation,
            AppFooter,
            Navbar
        },
    }
</script>
