<template>
  <div class="table-container">
    <div class="table-filter-container pb-10">
      <input type="text"
          placeholder="Search Rooms Table"
          v-model="filter"
          />
    </div>
    <table class="table-auto responsive-spaced">
      <caption></caption>
      <thead>
        <tr>
          <th class="lt-grey p-3" id="id_role_name">Role Name</th>
          <th class="lt-grey p-3" id="id_role_desc">Description</th>
          <th class="lt-grey p-3" id="id_role_set">Role Set</th>
          <th class="lt-grey p-3" id="id_role_action">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="role in roles" :key="role.id">
          <td class="text-center lt-grey p-3">{{role.name}}</td>
          <td class="text-center lt-grey p-3">jj</td>
          <td class="text-center lt-grey p-3">jj</td>          
          <td class="lt-grey p-3">
            <div class="text-md mx-2">
              <jet-dropdown width="48">
                <template #trigger>
                  <button
                  class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out mx-auto"
                  >
                  <span>. . .</span>
                  </button>
                </template>

                <template #content>
                  <div class="text-md mx-3">
                    <button
                        class="cursor-pointer text-sm text-blue-800 focus:outline-none"
                        @click="roleBeingUpdated = role"
                    >
                        Update
                    </button>
                  </div>
                  <div class="text-md mx-3">
                    <button
                        class="cursor-pointer text-sm text-red-800 focus:outline-none"
                        @click="roleBeingDeleted = role"
                    >
                        Delete
                    </button>
                  </div>
                </template>
              </jet-dropdown>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <jet-confirmation-modal :show="roleBeingDeleted" @close="roleBeingDeleted = null">
        <template #title>
            Delete Role
        </template>

        <template #content>
            Are you sure you would like to delete this role?
        </template>

        <template #footer>
            <jet-secondary-button @click.native="roleBeingDeleted = null">
                Nevermind
            </jet-secondary-button>

            <jet-danger-button class="ml-2" @click.native="deleteRole"
                                :class="{ 'opacity-25': deleteRoleForm.processing }"
                                :disabled="deleteRoleForm.processing">
                Delete
            </jet-danger-button>
        </template>
    </jet-confirmation-modal>

    <update-role-form :permissions="permissions"
                              :role="roleBeingUpdated"
                              @close="roleBeingUpdated = null"></update-role-form>


  </div>

</template>

<script>

import JetSectionBorder from '@src/Jetstream/SectionBorder'
import JetActionSection from '@src/Jetstream/ActionSection'
import JetConfirmationModal from '@src/Jetstream/ConfirmationModal'
import JetSecondaryButton from '@src/Jetstream/SecondaryButton'
import JetDangerButton from '@src/Jetstream/DangerButton'
import JetButton from '@src/Jetstream/Button'
import JetModal from '@src/Jetstream/Modal'
import Input from "@src/Jetstream/Input";
import Dropdown from "@src/Jetstream/Dropdown";
import JetInput from "@src/Jetstream/Input"
import JetInputError from "@src/Jetstream/InputError"
import JetLabel from "@src/Jetstream/Label"
import UpdateRoomForm from "@src/Pages/Admin/Rooms/UpdateRoomForm";
import Label from "@src/Jetstream/Label";
import JetDropdown from "@src/Jetstream/Dropdown";
import JetDropdownLink from "@src/Jetstream/DropdownLink";
import UpdateRoleForm from "@src/Pages/Admin/Roles/UpdateRoleForm";

export default {
  name: "RoleTable",
  props: {

    roles: {
      type: Array,
      default: [],
      required: true
    },
    permissions: {
      type: Array,
      default: [],
      required: true
    },
  },
  components: {
        Label,
        Dropdown,
        Input,
        JetSectionBorder,
        JetActionSection,
        JetButton,
        JetDangerButton,
        JetSecondaryButton,
        JetConfirmationModal,
        JetModal,
        JetInput,
        JetLabel,
        JetInputError,
        UpdateRoomForm,
        JetDropdown,
        JetDropdownLink,
        UpdateRoleForm
  },
  data() {
      return {
        deleteRoleForm: this.$inertia.form(),
        roleBeingUpdated: null,
        roleBeingDeleted: null,
        filter: '',
      }
  },

  methods: {
    deleteRole() {
        this.deleteRoomForm.delete('/admin/roles/' + this.roleBeingDeleted.id, {
            preserveScroll: true,
            preserveState: true,
        }).then(() => {
            this.roleBeingDeleted = null
        })
    },

    openEditModal(room) {
        this.setSelectedRestrictions(room)
        this.roomRestBeingUpdated = room;
    },

    setSelectedRestrictions(room) {
        this.updateRoomRestForm.restrictions = this.mapRoomRestrictions(room.restrictions)
    },

    mapRoomRestrictions(restrictions) {
        return restrictions.map((o) => {
            return o.id;
        });
    },
    updateRestrictions() {
      this.updateRoomRestForm.put('/admin/rooms/' + this.roomRestBeingUpdated.id + '/restrictions/', {
        preserveScroll: true,
        preserveState: true,
      }).then(() => {
        if (this.updateRoomRestForm.successful) {
          this.roomRestBeingUpdated = null;
        }
      });
    },

  },

  computed: {
    filterRooms() {
        return this.rooms.filter(room => {

            const building = room.building.toLowerCase();
            const status = room.status.toLowerCase();
            const name = room.name.toLowerCase();
            const floor = room.floor.toString();
            const number = room.number.toLowerCase();
            const id = room.id.toString();

            const search = this.filter.toLowerCase();

            return building.includes(search) ||
                    floor.includes(search) ||
                    name.includes(search) ||
                    status.includes(search) ||
                    number.includes(search) ||
                    id.includes(search);

        });
    }
  },
};
</script>
