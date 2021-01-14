<template>
    <div v-if="$page.props.jetstream.hasTeamFeatures">
        <div class="border-t border-gray-200"></div>

        <div class="block px-4 py-2 text-xs text-gray-400">
            Manage Team
        </div>

        <!-- Team Settings -->
        <jet-responsive-nav-link :href="route('teams.show', $page.props.user.current_team)" :active="route().current('teams.show')">
            Team Settings
        </jet-responsive-nav-link>

        <jet-responsive-nav-link :href="route('teams.create')" :active="route().current('teams.create')">
            Create New Team
        </jet-responsive-nav-link>

        <div class="border-t border-gray-200"></div>

        <!-- Team Switcher -->
        <div class="block px-4 py-2 text-xs text-gray-400">
            Switch Teams
        </div>

        <template v-for="team in $page.props.user.all_teams">
            <form @submit.prevent="switchToTeam(team)" :key="team.id">
                <jet-responsive-nav-link as="button">
                    <div class="flex items-center">
                        <svg v-if="team.id === $page.props.user.current_team_id"
                             class="mr-2 h-5 w-5 text-green-400"
                             fill="none"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             stroke-width="2"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>{{ team.name }}</div>
                    </div>
                </jet-responsive-nav-link>
            </form>
        </template>
    </div>
</template>

<script>
import JetResponsiveNavLink from "@src/Jetstream/ResponsiveNavLink";

export default {
    components: {
        JetResponsiveNavLink,
    },
    methods: {
        switchToTeam(team) {
            this.$inertia.put(route('current-team.update'), {
                'team_id': team.id
            }, {
                preserveState: false
            })
        },
    }
}
</script>