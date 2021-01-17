<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Users
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <create-user-form />

                <div v-if="users.length > 0">
                    <jet-section-border />

                    <users-list :users="users" :roles="roles" />
                </div>
            </div>
        </div>

    </app-layout>
</template>

<script>
import JetSectionBorder from '@src/Jetstream/SectionBorder'
import CreateUserForm from './CreateUserForm';
import UsersList from './UsersList';
import AppLayout from '@src/Layouts/AppLayout';

export default {
    components: {
        CreateUserForm,
        AppLayout,
        UsersList,
        JetSectionBorder,
    },
    props: {
        users: {
            type: Array,
            default: function () {
                return []
            },
        },
        roles: {
            type: Array,
            default: function () {
                return []
            },
        }
    }
}
</script>
