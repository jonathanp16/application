<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Settings
            </h2>
        </template>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <app-name :settings="settings.app_name"/>
            <app-logo-form />
        </div>
    </app-layout>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import AppLayout from '@src/Layouts/AppLayout';
import AppName from "./AppName";
import AppLogoForm from "./AppLogo";

export default {
    components: {
        AppLayout,
        JetSectionBorder,
        AppLogoForm,
        AppName,
    },
    props: {
        settings: {
            type: Object,
            default: function () {
                return {
                    app_name: {},
                    app_logo: {}
                }
            },
        },
    },
}
</script>
