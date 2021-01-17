<template>
  <div>
    <header>
       <div class="flex-shrink-0 flex items-center" style ="margin-left: 50px">
        <a :href="route('dashboard')">
          <jet-application-mark class="block h-15 w-auto"/>
        </a>
        <pre> CSU booking platform </pre>
      </div>
    </header>

    <nav class="bg-white border-b border-gray-100 top-nav" aria-label="Main navBar">
      <!-- Primary Navigation Menu -->
      <div>
        <div class="flex justify-between h-16">
          <div class="flex">
            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex ">
              <jet-nav-link
                :href="route('dashboard')"
                :active="route().current('dashboard')"
              >
                Home
              </jet-nav-link>
              <jet-nav-link
                v-if="$page.props.user.can.includes('bookings')"
                :href="route('rooms.index')"
                :active="route().current('rooms.index')"
              >
                Booking Management
              </jet-nav-link>
              <jet-nav-link
                v-if="$page.props.user.can.includes('users')"
                :href="route('users.index')"
                :active="route().current('users.index')"
              >
                User Management
              </jet-nav-link>
            </div>
          </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">

                <!-- Teams Dropdown -->
                <jet-nav-teams-dropdown />

                <!-- Settings Dropdown -->
                <div class="ml-3 relative">
                    <jet-dropdown align="right" width="48">
                        <template #trigger>
                            <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                                <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                            </button>

                            <span v-else class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                {{ $page.props.user.name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                        </template>

                        <template #content>
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Manage Account
                            </div>

                            <jet-dropdown-link :href="route('profile.show')">
                                Profile
                            </jet-dropdown-link>

                            <jet-dropdown-link :href="route('api-tokens.index')" v-if="$page.props.jetstream.hasApiFeatures">
                                API Tokens
                            </jet-dropdown-link>

                            <div class="border-t border-gray-100"></div>

                            <!-- Authentication -->
                            <form @submit.prevent="logout">
                                <jet-dropdown-link as="button">
                                    Logout
                                </jet-dropdown-link>
                            </form>
                        </template>
                    </jet-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
      </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <jet-responsive-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                    Dashboard
                </jet-responsive-nav-link>
                <jet-responsive-nav-link :href="route('bookings.index')" :active="route().current('bookings.index')">
                    Bookings
                </jet-responsive-nav-link>
                <jet-responsive-nav-link :href="route('rooms.index')" :active="route().current('rooms.index')">
                    Rooms
                </jet-responsive-nav-link>
                <jet-responsive-nav-link :href="route('users.index')" :active="route().current('users.index')">
                    Users
                </jet-responsive-nav-link>
                <jet-responsive-nav-link :href="route('roles.index')" :active="route().current('roles.index')">
                    Roles
                </jet-responsive-nav-link>
                <jet-responsive-nav-link :href="route('settings.index')" :active="route().current('settings.index')">
                    Settings
                </jet-responsive-nav-link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div v-if="$page.props.jetstream.managesProfilePhotos" class="flex-shrink-0 mr-3" >
                        <img class="h-10 w-10 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                    </div>

                    <div>
                        <div class="font-medium text-base text-gray-800">{{ $page.props.user.name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ $page.props.user.email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <jet-responsive-nav-link :href="route('profile.show')" :active="route().current('profile.show')">
                        Profile
                    </jet-responsive-nav-link>

                    <jet-responsive-nav-link :href="route('api-tokens.index')" :active="route().current('api-tokens.index')" v-if="$page.props.jetstream.hasApiFeatures">
                        API Tokens
                    </jet-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" @submit.prevent="logout">
                        <jet-responsive-nav-link as="button">
                            Logout
                        </jet-responsive-nav-link>
                    </form>

                    <!-- Team Management -->
                    <jet-responsive-nav-team-links />
                </div>
            </div>
        </div>
    </nav>
  <nav class="sub-nav" aria-label="SubnavBar">
    <Subnavbar v-if="route().current('dashboard')">
      <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
        <jet-nav-sub :href="route('dashboard')"
          :active="route().current('dashboard')"
        >
          Dashboard
        </jet-nav-sub>
      </div>
    </Subnavbar>
    <Subnavbar v-else-if="route().current('rooms.index')">
      <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex ">
        <jet-nav-sub :href="route('rooms.index')"
          :active="route().current('rooms.index')"
        >
          Rooms
        </jet-nav-sub>
      </div>
    </Subnavbar>
    <Subnavbar v-else-if="route().current('users.index')">
      <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex ">
        <jet-nav-sub :href="route('users.index')"
          :active="route().current('users.index')"
        >
          Users
        </jet-nav-sub>
        <jet-nav-sub :href="route('roles.index')"
          :active="route().current('roles.index')"
        >
          Roles
        </jet-nav-sub>
      </div>
    </Subnavbar>
    <Subnavbar v-else-if="route().current('roles.index')">
      <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
       <jet-nav-sub
          href="/users"
          :active="route().current('users.index')"
        >
          Users
        </jet-nav-sub>
        <jet-nav-sub :href="route('roles.index')"
          :active="route().current('roles.index')"
        >
          Roles
        </jet-nav-sub>
      </div>
    </Subnavbar>
  </nav>
  </div>
</template>

<script>
import JetApplicationLogo from "@src/Jetstream/ApplicationLogo";
import JetApplicationMark from "@src/Jetstream/ApplicationMark";
import JetDropdown from "@src/Jetstream/Dropdown";
import JetDropdownLink from "@src/Jetstream/DropdownLink";
import JetNavLink from "@src/Jetstream/NavLink";
import JetResponsiveNavLink from "@src/Jetstream/ResponsiveNavLink";
import Subnavbar from "@src/Components/Navbar/Subnavbar";
import JetNavSub from "@src/Jetstream/SubNavLink";
import JetResponsiveNavTeamLinks from "@src/Jetstream/ResponsiveNavTeamLinks";
import JetNavTeamsDropdown from "@src/Jetstream/NavTeamsDropdown";


export default {
    components: {
        JetApplicationLogo,
        JetApplicationMark,
        JetDropdown,
        JetDropdownLink,
        JetNavLink,
        JetResponsiveNavLink,
        Subnavbar,
        JetNavSub,
        JetResponsiveNavTeamLinks,
        JetNavTeamsDropdown,
    },

    data() {
        return {
            showingNavigationDropdown: false,
        };
    },
    methods: {
        logout() {
            this.$inertia.post(route('logout'));
        },
    },
    computed: {
        path() {
            return window.location.pathname;
        },
    },
};
</script>
