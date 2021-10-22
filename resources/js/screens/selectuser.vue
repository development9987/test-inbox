<template>
  <div>
    <router-link :to="{ name: 'compose-auto', params: { emails: userIds, template: template} }"  class="btn btn-purple-primary btn-block">Select Users</router-link>
    <table
      id="datatable-buttons"
      class="
        table table-bordered
        align-middle
        dt-responsive
        nowrap
        dataTable
        no-footer
        dtr-inline
      "
      style="border-collapse: collapse; border-spacing: 0px; width: 100%"
      role="grid"
    >
      <thead>
        <tr role="row">
          <th
            class="align-middle sorting_desc"
            rowspan="1"
            colspan="1"
            style="width: 5px"
            aria-label=""
          >
            <label class="form-check-label">
              <input
                type="checkbox"
                class="form-check-input"
                id="select-all-row"
                @click="selectAll" v-model="allSelected"
              /><span></span>
            </label>
          </th>
          <th
            class="sorting"
            tabindex="0"
            aria-controls="datatable-buttons"
            rowspan="1"
            colspan="1"
            style="width: 47px"
            aria-label="Name: activate to sort column ascending"
          >
            Name
          </th>
          <th
            class="sorting"
            tabindex="0"
            aria-controls="datatable-buttons"
            rowspan="1"
            colspan="1"
            style="width: 70px"
            aria-label="Number: activate to sort column ascending"
          >
            Number
          </th>
          <th
            class="sorting"
            tabindex="0"
            aria-controls="datatable-buttons"
            rowspan="1"
            colspan="1"
            style="width: 33px"
            aria-label="City: activate to sort column ascending"
          >
            City
          </th>
          <th
            class="sorting"
            tabindex="0"
            aria-controls="datatable-buttons"
            rowspan="1"
            colspan="1"
            style="width: 49px"
            aria-label="Group: activate to sort column ascending"
          >
            Group
          </th>
          <th
            class="sorting"
            tabindex="0"
            aria-controls="datatable-buttons"
            rowspan="1"
            colspan="1"
            style="width: 99px"
            aria-label="Email: activate to sort column ascending"
          >
            Email
          </th>
        </tr>
      </thead>
      <tbody>
        <tr role="row" class="odd" v-for="user in users" :key="user.id">
          <td class="sorting_1 dtr-control">
            <input type="checkbox" class="form-check-input" @click="select" v-model="userIds" :value="user.email" />
          </td>
          <td class="bg-light text-primary fw-bold">
            <a href="http://7-stock-admin.test/admin/edit_customer/1">{{ user.name }}</a>
          </td>
          <td>{{ user.phonenumber1 }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: {},
      id: 0,
      selected: [],
      template:{},
      allSelected: false,
      userIds: [],
    };
  },
  methods: {
    loadUsers() {
      axios
        .get(Inbox.basePath + "/api/allusers/")
        .then(({ data }) => {
            // alert(data.data);
            console.log(data.data);
            this.users = data.data;
        });
    },
    starEmail(id) {
      axios.get(Inbox.basePath + "/api/star/" + this.id).then(() => {
        this.loadEmail(this.id);
      });
    },
    bookmarkEmail(id) {
      axios.get(Inbox.basePath + "/api/bookmark/" + this.id).then(() => {
        this.loadEmail(this.id);
      });
    },
    unreadEmail(id) {
      axios.get(Inbox.basePath + "/api/unread/" + this.id).then(() => {
        this.loadEmail(this.id);
      });
    },
    deleteEmail(id) {
      axios.get(Inbox.basePath + "/api/delete/" + this.id).then(() => {
        this.$router.push("/dashboard");
      });
    },
    select(){
        this.allSelected = false;
    },
    selectAll(){
        this.userIds = [];
        var counter = 0;
        var user = '';
        if (!this.allSelected) {
            for (user in this.users) {
                this.userIds.push(this.users[counter].email.toString());
                counter++;
            }
        }
    }
    
  },
  filters: {
    striphtml(value) {
      var div = document.createElement("div");
      div.innerHTML = value;
      var text = div.textContent || div.innerText || "";
      return text;
    },
  },
  mounted() {
    this.template = this.$route.params.template;
    this.$route.params.id
    // alert("in all users");
    this.loadUsers();
  },
  computed: {
    
  }
};
</script>