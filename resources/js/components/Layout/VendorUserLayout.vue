<template>
  <div>
    <v-app-bar
      class="app-main-bar">
      <v-app-bar-nav-icon @click="drawer = !drawer" v-if="drawer === false"> </v-app-bar-nav-icon>
      <v-spacer>
      </v-spacer>
      <div class="">
      <v-btn  :to="{name: 'AdminHome'}" color="primary" class="mx-2" outlined small>
        Manage
      </v-btn>
      <v-btn class="mx-2" small icon outlined>
        <v-icon size="15">mdi-bell-outline</v-icon>
      </v-btn>
      <v-btn class="mx-2" small icon outlined>
        <v-icon size="15">mdi-plus</v-icon>
      </v-btn>
    </div>
    </v-app-bar>
    <v-navigation-drawer dark app
    clipped v-model="drawer"
    :permanent="!activateDrawer"
    color="primary">
      <v-toolbar
      elevation="4"
      class="primary drawer-app-bar"
      >
        <!-- <v-toolbar-title>
          MegaMinds Inc
        </v-toolbar-title> -->
    <v-menu
      bottom
      transition="slide-y-transition"
    >
      <template v-slot:activator="{ on, attrs }">
        <div
          v-bind="attrs"
          v-on="on">
          <div>
            <label class="mb-0 font-weight-bold">Simple E-Commerce</label>
            <span>
              <v-icon color="#ffffff50">mdi-chevron-down</v-icon>
            </span>
          </div>
          <label class="small font-weight-light">
            Full Name
          </label>
        </div>
      </template>
      <v-list dense width="200">
        <v-list-item :to="{name: 'AdminHome'}">
          <v-row align="center" justify="center" class="text-center pb-4 pt-4">
            <v-col cols="12">
            <v-avatar size="100" rounded="sm" color="grey lighten-3">
              <div>
                <v-icon >mdi-image-size-select-large</v-icon>
              <div>
                <v-btn x-small plain>Add Logo</v-btn>
              </div>
              </div>
            </v-avatar>
            </v-col>
            <v-col cols="12" class="pt-0">
              <label>MegaMinds Inc.</label>
            </v-col>
          </v-row>
        </v-list-item>
        <v-list-item
        exact-path
        :to="{name: `${item.routeName}`}"
          v-for="(item, index) in adminLinks"
          :key="`admin_${index}`"
        >
          <v-list-item-title>{{ item.name }}</v-list-item-title>
        </v-list-item>
        <v-divider class="my-2"></v-divider>
        <v-list-item
        exact-path
        :to="{name: `${item.routeName}`}"
          v-for="(item, index) in userLinks"
          :key="`user_${index}`"
        >
          <v-list-item-title>{{ item.name }}</v-list-item-title>
        </v-list-item>
        <v-list-item @click="logout"
        >
          <v-list-item-title>Logout</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
        <v-spacer>
        </v-spacer>
        <v-avatar
        size="40"
        color="grey"
        class="font-weight-light"
        >
        PA
        </v-avatar>
      </v-toolbar>

      <!-- regular user menu  -->
      <v-list
      class="menu-items"
      dense
      >
      <!-- dahsboard  -->
      <v-list-item link
      exact-active-class="menu-active-class"
                   href="/system/users"
      >
          <v-icon class="pa-2" color="grey lighten-3">mdi-speedometer</v-icon>
        <v-list-item-title class="">
          Dashboard
          </v-list-item-title>
      </v-list-item>
      <!-- vehicle  -->
      <v-list-group
      active-class="menu-active-class"
      append-icon="mdi-chevron-down"
      >
        <template v-slot:activator>
          <v-icon class="pa-2" color="grey lighten-3">mdi-car</v-icon>
          <v-list-item-title>Vehicles</v-list-item-title>
        </template>
            <v-list-item active-class="menu-active-class" link >
              <v-list-item-icon>
              </v-list-item-icon>
              <v-list-item-content>
          <v-list-item-subtitle>
            Vehicle List
          </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-list-item link >
              <v-list-item-icon>
              </v-list-item-icon>
              <v-list-item-content class="pt-0 pb-0">
          <v-list-item-subtitle>
            Vehicle Assignments
          </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-list-item link>
              <v-list-item-icon>
              </v-list-item-icon>
              <v-list-item-content>
          <v-list-item-subtitle>
            Expense History
          </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
        </v-list-group>

      <!-- Inspection  -->
      <v-list-group
      active-class="menu-active-class"
      append-icon="mdi-chevron-down"
      >
        <template v-slot:activator>
          <v-icon class="pa-2" color="grey lighten-3">mdi-check-circle</v-icon>
          <v-list-item-title>Inspections</v-list-item-title>
        </template>
            <v-list-item link>
              <v-list-item-icon>
              </v-list-item-icon>
              <v-list-item-content>
          <v-list-item-subtitle>
            Inspection History
          </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-list-item link>
              <v-list-item-icon>
              </v-list-item-icon>
              <v-list-item-content>
          <v-list-item-subtitle>
            Forms
          </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
        </v-list-group>
      <!-- Issues  -->
      <v-list-group
      active-class="menu-active-class"
      append-icon="mdi-chevron-down"
      >
        <template v-slot:activator>
          <v-icon class="pa-2" color="grey lighten-3">mdi-alert-rhombus</v-icon>
          <v-list-item-title>Issues</v-list-item-title>
        </template>
            <v-list-item link>
              <v-list-item-icon>
              </v-list-item-icon>
              <v-list-item-content>
          <v-list-item-subtitle>
            Inspection History
          </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-list-item link>
              <v-list-item-icon>
              </v-list-item-icon>
              <v-list-item-content>
          <v-list-item-subtitle>
            Forms
          </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
        </v-list-group>
      <!-- Reminders  -->
      <v-list-group
      active-class="menu-active-class"
      append-icon="mdi-chevron-down"
      >
        <template v-slot:activator>
          <v-icon class="pa-2" color="grey lighten-3">mdi-alarm</v-icon>
          <v-list-item-title>Reminders</v-list-item-title>
        </template>
            <v-list-item link>
              <v-list-item-icon>
              </v-list-item-icon>
              <v-list-item-content>
          <v-list-item-subtitle>
            Inspection History
          </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-list-item link>
              <v-list-item-icon>
              </v-list-item-icon>
              <v-list-item-content>
          <v-list-item-subtitle>
            Forms
          </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
        </v-list-group>

      <!-- Service  -->
      <v-list-group
      active-class="menu-active-class"
      append-icon="mdi-chevron-down"
      >
        <template v-slot:activator>
          <v-icon class="pa-2" color="grey lighten-3">mdi-wrench</v-icon>
          <v-list-item-title>Service</v-list-item-title>
        </template>
            <v-list-item link>
              <v-list-item-icon>
              </v-list-item-icon>
              <v-list-item-content>
          <v-list-item-subtitle>
            Inspection History
          </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-list-item link>
              <v-list-item-icon>
              </v-list-item-icon>
              <v-list-item-content>
          <v-list-item-subtitle>
            Forms
          </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
        </v-list-group>

      <!-- personnel  -->
      <!-- <v-list-group
      active-class="menu-active-class"
      append-icon="mdi-chevron-down"
      >
        <template v-slot:activator>
          <v-icon class="pa-2" color="grey lighten-3">mdi-account-supervisor</v-icon>
          <v-list-item-title>Personnel</v-list-item-title>
        </template> -->
            <v-list-item>
          <v-icon class="pa-2" color="grey lighten-3">mdi-account-supervisor</v-icon>
          <v-list-item-title>Personnel</v-list-item-title>
            </v-list-item>
        <!-- </v-list-group> -->

      <!-- Vendors  -->
      <v-list-group
      active-class="menu-active-class"
      append-icon="mdi-chevron-down"
      >
        <template v-slot:activator>
          <v-icon class="pa-2" color="grey lighten-3">mdi-home</v-icon>
          <v-list-item-title>Vendors</v-list-item-title>
        </template>
            <v-list-item link>
              <v-list-item-icon>
              </v-list-item-icon>
              <v-list-item-content>
          <v-list-item-subtitle>
            Inspection History
          </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-list-item link>
              <v-list-item-icon>
              </v-list-item-icon>
              <v-list-item-content>
          <v-list-item-subtitle>
            Forms
          </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
        </v-list-group>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>

<script>
export default {
  name: 'RegularUserLayout',
  data () {
    return {
      mini: true,
      drawer: true,
      adminLinks: [
        {
          id: 1, name: 'Account Settings', routeName: 'AdminHome'
        },
        {
          id: 2, name: 'User Management', routeName: 'UsersList'
        }
      ],
      userLinks: [
        {
          id: 1, name: 'User Profile', routeName: 'UserProfile'
        },
        {
          id: 2, name: 'Change Password', routeName: 'ChangePassword'
        }
      ],
    }
  },
  computed: {
    //...mapGetters({ fullname: 'authentication/fullname' }),
    activateDrawer () {
      return this.$vuetify.breakpoint.width < '1000'
    }
  },
  methods: {
    logout () {

    },
    watchResize () {
      // alert(this.$vuetify.breakpoint.width)
      if (this.$vuetify.breakpoint.width < '986') {
        this.drawer = false
      } else {
        this.drawer = true
      }
    }
  },
  mounted () {
    this.watchResize()
  }
}
</script>

<style scoped>
.app-main-bar {
  position: sticky !important;
  position: -webkit-sticky !important;
  top: 0 !important;
}
.drawer-app-bar {
  position: sticky;
  position: -webkit-sticky;
  z-index: 1;
  top: 0;
}
.v-list-group--active {
  color: white !important;
}

</style>
