<template>
  <div style="padding: 10px;">
    <div>
      <h2 style="display:inline;">Mail Content</h2>
      <router-link to="/mail-content-add"  class="col-2 btn btn-purple-primary btn-block" style="float: right; padding: 1px">Add</router-link>
    </div>
    <div style="padding: 12px; border-bottom: 1px solid #c4c4c4;" v-for="(template, index) in templates" :key="template.id">
      <div style="color:#5B626B;"><strong>{{ template.subject }}</strong></div>
      <div>{{ 
        template.body.length > 250 ?
        template.body.substring(0, 250 - 3) + "..." :
        template.body
       }}

      </div>
      <div style="display: flex; justify-content: end;">
        <router-link :to="{ name: 'compose-auto', params: { template: templates[index] } }" style="width:16%; padding:1px 1px; margin-top: 8px; margin-left: 5px;"  class="btn btn-gray-primary btn-block">Use</router-link>
        <router-link :to="{ name: 'mailcontentedit', params: { id: template.id } }" style="width:16%; padding:1px 1px; margin-left: 5px;"  class="btn btn-gray-primary btn-block">Edit</router-link>
        <a href="#" style="width:16%; padding:1px 1px; margin-left: 5px;" class="btn btn-gray-primary btn-block router-link-active" @click="deleteTemplate(template.id)">Delete</a>
      </div>
    </div>  
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
        .get("/inbox/api/allmailscontent/")
        .then(({ data }) => {
            // alert('in mailcontent');
            console.log(data.data);
            this.templates = data.data;
        });
    },
    deleteTemplate(id) {
        // alert('in deleteTemplate');
      // Submit the form via a POST request
      axios.get("/inbox/api/deleteMailContent/"+id).then(({ data }) => {
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
          alert('are you sure!');
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