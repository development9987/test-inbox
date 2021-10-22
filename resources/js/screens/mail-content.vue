<template>
  <div>
    <router-link to="/mail-content-add"  class="col-2 btn btn-purple-primary btn-block" style="float: right;">Add</router-link>
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
      <!-- <thead>
        <tr role="row">
          <th
            class="sorting"
            tabindex="0"
            aria-controls="datatable-buttons"
            rowspan="1"
            colspan="1"
            style="width: 47px"
            aria-label="Name: activate to sort column ascending"
          >
            Name 2
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
      </thead> -->
      <tbody>
        <tr role="row" class="odd" v-for="(template, index) in templates" :key="template.id">
          <td>{{ template.subject }}</td>
          <td>{{ template.body }}</td>
          <td><router-link :to="{ name: 'compose-auto', params: { template: templates[index] } }"  class="btn btn-purple-primary btn-block">Use</router-link></td>
          <td><router-link :to="{ name: 'mailcontentedit', params: { id: template.id } }"  class="btn btn-purple-primary btn-block">Edit</router-link></td>
          <td><a href="#" @click="deleteTemplate(template.id)">Delete</a></td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      templates: {},
      id: 0,
      selected: [],
      allSelected: false,
      userIds: [],
    };
  },
  methods: {
    loadUsers() {
      axios
        .get(Inbox.basePath + "/api/allmailscontent/")
        .then(({ data }) => {
            // alert('in mailcontent');
            console.log(data.data);
            this.templates = data.data;
        });
    },
    deleteTemplate(id) {
        // alert('in deleteTemplate');
      // Submit the form via a POST request
      axios.get(Inbox.basePath + "/api/deleteMailContent/"+id).then(({ data }) => {
        console.log(data);
        if(data.status){
          Swal.fire({
          title: 'Error!',
          text: 'There might be issue check logs.',
          icon: 'error',
          confirmButtonText: 'OK'
          })  
        }else{
          Swal.fire({
          title: 'deleted!',
          text: 'Your record is deleted',
          icon: 'success',
          confirmButtonText: 'OK'
          })
          this.loadUsers();
          alert('Sent!');
        }
      });
    },
    
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
    this.$route.params.id
    // alert("in all users");
    this.loadUsers();
  },
};
</script>