<template>
  <div>
    <v-app-bar
      class="app-main-bar"
      style="z-index:10"
    >
      <v-app-bar-nav-icon @click="drawer = !drawer" v-if="drawer === false"> </v-app-bar-nav-icon>
      <v-spacer>
      </v-spacer>
      <div class="">
       <v-form :action="logoutUrl" method="POST">
           <input type="hidden" name="_token" :value="csrf_token" />
           <v-btn class="mx-2" small icon outlined>
               <v-icon size="15">mdi-bell-outline</v-icon>
           </v-btn>
      <v-btn class="mx-2" small icon outlined type="submit" ref="logoutButton">
        <v-icon size="15">mdi-power</v-icon>
      </v-btn>
      </v-form>
    </div>
    </v-app-bar>
    <v-navigation-drawer dark app
    clipped v-model="drawer"
    :permanent="!activateDrawer"
     style="z-index:20"
    color="primary">
      <v-toolbar
      elevation="4"
      class="primary drawer-app-bar"
      >
          <v-menu
              bottom
              offset-y
              style="z-index: 30"
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
                          {{authUser.name}}
                      </label>
                  </div>
              </template>
              <v-list dense width="200">
                  <v-list-item
                      :href="userProfile"
                  >
                      <v-list-item-title>User Profile</v-list-item-title>
                  </v-list-item>
                  <v-divider class="my-0"></v-divider>
                  <v-list-item
                      :href="ChangePassword"
                  >
                      <v-list-item-title>Change Password</v-list-item-title>
                  </v-list-item>
                  <v-divider class="my-0"></v-divider>
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
        {{authUser.initials}}
        </v-avatar>
      </v-toolbar>

      <!-- regular user menu  -->
      <v-list
      class="menu-items"
      dense
      >
          <template v-for="(routeLink, i) in routeLinks">
              <template v-if="routeLink.hasSub" >
                  <v-list-group
                      :key="i+'_main-link'"
                      append-icon="mdi-chevron-down"
                  >
                      <template v-slot:activator class="text-white">
                          <v-icon class="pa-2" color="grey lighten-3">{{routeLink.icon}}</v-icon>
                          <v-list-item-title class="text-white">{{routeLink.name}}</v-list-item-title>
                      </template>
                      <v-list-item :href="subLink.link" link v-for="(subLink, a) in routeLink.subLinks" :key="a+'_sub-link'">
                          <v-list-item-icon>
                              <!--<v-icon class="pa-2" color="grey lighten-3">{{subLink.icon}}</v-icon>-->
                          </v-list-item-icon>
                          <v-list-item-content>
                              <v-list-item-subtitle>
                                  {{subLink.name}}
                              </v-list-item-subtitle>
                          </v-list-item-content>
                      </v-list-item>
                  </v-list-group>
              </template>
              <template v-else>
                  <v-list-item link  :key="i+'_main-link'"
                               exact-active-class="menu-active-class"
                               :href="routeLink.link"
                  >
                      <v-icon class="pa-2" color="grey lighten-3">{{routeLink.icon}}</v-icon>
                      <v-list-item-title class="">
                          {{routeLink.name}}
                      </v-list-item-title>
                  </v-list-item>
              </template>
          </template>
      <!--&lt;!&ndash; dahsboard  &ndash;&gt;
      <v-list-item link
      exact-active-class="menu-active-class"
                   href="/home"
      >
          <v-icon class="pa-2" color="grey lighten-3">mdi-store</v-icon>
        <v-list-item-title class="">
          Market Place
          </v-list-item-title>
      </v-list-item>
      &lt;!&ndash; vehicle  &ndash;&gt;
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

      &lt;!&ndash; Inspection  &ndash;&gt;
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
      &lt;!&ndash; Issues  &ndash;&gt;
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
      &lt;!&ndash; Reminders  &ndash;&gt;
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

      &lt;!&ndash; Service  &ndash;&gt;
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

      &lt;!&ndash; personnel  &ndash;&gt;
      &lt;!&ndash; <v-list-group
      active-class="menu-active-class"
      append-icon="mdi-chevron-down"
      >
        <template v-slot:activator>
          <v-icon class="pa-2" color="grey lighten-3">mdi-account-supervisor</v-icon>
          <v-list-item-title>Personnel</v-list-item-title>
        </template> &ndash;&gt;
            <v-list-item>
          <v-icon class="pa-2" color="grey lighten-3">mdi-account-supervisor</v-icon>
          <v-list-item-title>Personnel</v-list-item-title>
            </v-list-item>
        &lt;!&ndash; </v-list-group> &ndash;&gt;

      &lt;!&ndash; Vendors  &ndash;&gt;
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
        </v-list-group>-->
      </v-list>
    </v-navigation-drawer>
      <v-main>
          <slot name="message" class="align-content-between"></slot>
          <slot name="main"></slot>
      </v-main>
  </div>
</template>

<script>
export default {
  name: 'RegularUserLayout',
    props:{
        logoutUrl: {
          type: String,
          default: ''
      },
        userProfile: {
          type: String,
            default: ''
        },
        ChangePassword: {
          type: String,
            default: ''
        },
        routeLinks: Array
    },
  data () {
    return {
        items: [
            { title: 'Click Me' },
            { title: 'Click Me' },
            { title: 'Click Me' },
            { title: 'Click Me 2' },
        ],
      mini: true,
      drawer: true,
    }
  },
  computed: {
    //...mapGetters({ fullname: 'authentication/fullname' }),
    activateDrawer () {
      return this.$vuetify.breakpoint.width < '1000'
    },
    csrf_token(){
        return this.$root.csrf
    },
      authUser(){
        return this.$root.authUser
    },

  },
  methods: {
    logout () {
        this.$refs.logoutButton.$el.click();
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
.v-list .v-list-item--active, .v-list .v-list-item--active .v-icon {
     color: white !important;
}

</style>
