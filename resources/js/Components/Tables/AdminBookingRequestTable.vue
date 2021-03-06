<template>
  <div class="table-container">
    <div class="table-filter-container mb-12 flex flex-row">
      <div class="ml-3 mr-6">
        <h3 class="font-black">All Booking Requests</h3>
      </div>
      <div class="border shadow-md">
        <input type="text" v-model="filter" placeholder="Search requests..."/>
      </div>
      <div class="bg-yellow-300 shadow-md">
        <em class="fas fa-search m-2"></em>
      </div>


      <!--vertical line-->
      <div class="border mx-16">
      </div>
      <div class="mx-6">
        <h3 class="font-black">FILTERS</h3>
      </div>
      <div class="mx-2 border shadow-md bg-yellow-300 min-w-24">
        <button @click="toggleAdvancedFilters">
          <em class="fas fa-filter mx-2 pt-2 max-w"></em>
        </button>
      </div>
    </div>

    <table class="table-auto responsive-spaced">
      <caption></caption>
      <thead>
      <tr>
        <th  id="id_booking_id">Booking ID</th>
        <th  id="id_created_by">Created By</th>
        <th  id="id_assigned_to">Assigned To</th>
        <th  id="id_status">Status</th>
        <th  id="id_date_created">Created</th>
        <th  id="id_last_updated">Last Updated</th>
        <th  id="id_action">Action</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="booking in filteredBookingRequests" :key="booking.id">
        <td class="text-center">{{booking.id}}</td>
        <td class="text-center">{{booking.requester.name}}</td>
        <td class="text-center">Assigned to</td>
        <td class="text-center">{{ booking.status }}</td>
        <td class="text-center">Created {{ booking.created_diff }}</td>
        <td class="text-center">Last Updated {{ booking.updated_diff }}</td>
        <td>
          <jet-responsive-nav-link
            :href="'/bookings/'+booking.id+'/review'"
            class="cursor-pointer ml-3 text-sm text-blue-800 focus:outline-none"
          >
            Open Details
          </jet-responsive-nav-link>
        </td>
      </tr>
      </tbody>
    </table>

    <jet-dialog-modal :show="showFilterModal">
      <template #title>
        Additional Booking Request Filters
      </template>

      <template #content>
        <div class="overflow-y-auto h-48">
          <div class="flex flex-row">
            <div class="flex flex-col flex-1 py-2 px-3">
              <div><h2>Status</h2></div>
              <div class="flex flex-row">
                <div><input type="checkbox" v-model="jsonFilters.status.review"></div>
                <div class="text-sm text-gray-400 px-2">
                  Pending
                </div>
              </div>
              <div class="flex flex-row">
                <div><input type="checkbox" v-model="jsonFilters.status.approved"></div>
                <div class="text-sm text-gray-400 px-2">
                  Approved
                </div>
              </div>
              <div class="flex flex-row">
                <div><input type="checkbox"></div>
                <div class="text-sm text-gray-400 px-2">
                  Denied
                </div>
              </div>
            </div>
          </div>
        </div>

      </template>

      <template #footer>
        <jet-button
          class="ml-2"
          @click.native="advancedFilters()"
        >
          Filter
        </jet-button>
        <jet-secondary-button @click.native="toggleAdvancedFilters">
          Close
        </jet-secondary-button>
      </template>
    </jet-dialog-modal>
  </div>
</template>
<script>

import JetDialogModal from "@src/Jetstream/DialogModal";
import JetSecondaryButton from "@src/Jetstream/SecondaryButton";
import JetDropdown from "@src/Jetstream/Dropdown";
import JetDropdownLink from "@src/Jetstream/DropdownLink";
import JetButton from "@src/Jetstream/Button";

import JetResponsiveNavLink from "@src/Jetstream/ResponsiveNavLink"

export default {
  name: "AdminBookingRequestTable",
  props: {
    bookings: {
      type: Array,
      default: function () {
        return []
      },
    }
  },
  components: {
    JetResponsiveNavLink,
    JetDropdownLink,
    JetDropdown,
    JetButton,
    JetDialogModal,
    JetSecondaryButton
  },
  data() {
    return {
      filter: '',
      showFilterModal: false,
      jsonFilters: {
        status: {
          review: false,
          approved: false
        },
        date_range_start: null,
        date_range_end: null,
      }
    }
  },
  computed: {
    filteredBookingRequests() {
      return this.bookings.filter(booking => {
        const requesterName = booking.requester.name.toLowerCase();
        const id = booking.id.toString();
        const status = booking.status.toLowerCase();
        const searchTerm = this.filter.toLowerCase();

        return id.includes(searchTerm)||
          requesterName.includes(searchTerm) ||
          id.includes(searchTerm) ||
          status.includes(searchTerm);

      });
    },
    activeJsonFilters: function () {
      let activeJsonFilters = {};
      for (let key of Object.keys(this.jsonFilters)) {
        if(this.jsonFilters[key]){
          activeJsonFilters[key] = this.jsonFilters[key];
        }
      }
      return activeJsonFilters;
    }
  },
  methods: {
    advancedFilters(){
        this.$emit('filterBookingRequestsJson', this.activeJsonFilters);
        this.toggleAdvancedFilters();
    },
    toggleAdvancedFilters(){
      this.showFilterModal = !this.showFilterModal;
    },
  },

};
</script>
