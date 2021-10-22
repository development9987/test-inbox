<template>
  <div>
    <router-link
      to="/mail-content"
      class="btn btn-purple-primary btn-block"
      >Back</router-link
    >
    <form @submit.prevent="addTemplate" @keydown="form.onKeydown($event)">
      <div class="box-body">
        <div class="form-group">
          <input
            name="subject"
            v-model="form.subject"
            class="form-control"
            placeholder="Subject:"
          />
        </div>
        <div class="form-group">
          <wysiwyg v-model="form.html" />
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <div class="">
          <button
            type="button"
            class="btn btn-success waves-effect waves-light me-1"
          >
            <i class="far fa-save"></i>
          </button>
          <button
            type="reset"
            class="btn btn-success waves-effect waves-light me-1"
          >
            <i class="far fa-trash-alt"></i>
          </button>
          <button
            type="submit"
            class="btn btn-primary waves-effect waves-light"
          >
            <span>Save</span> <i class="fab fa-telegram-plane ms-2"></i>
          </button>
        </div>
      </div>
      <!-- /.box-footer -->
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      id: 0,
      // Create a new form instance
      form: new Form({
        id: "",
        from: "",
        subject: "",
        html: "",
        attachments: "",
      }),
      // { [module]: boolean (set true to hide) }
      hideModules: { bold: true },
    };
  },
  methods: {
    addTemplate() {
      // Submit the form via a POST request
      this.form.post(Inbox.basePath + "/api/allmailscontent").then(({ data }) => {
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
          title: 'Saved!',
          text: 'Your record is saved',
          icon: 'success',
          confirmButtonText: 'OK'
          })
        //   alert('Sent!');
        }
      });
    },
    deleteTemplate() {
      // Submit the form via a POST request
      this.form.post(Inbox.basePath + "/api/allmailscontent").then(({ data }) => {
        console.log(data);
        if(data.status){
          Swal.fire({
          title: 'Error!',
          text: 'There might be issue while sending mail please check email addresses or check logs.',
          icon: 'error',
          confirmButtonText: 'OK'
          })  
        }else{
          Swal.fire({
          title: 'Sent!',
          text: 'Your Email is sent',
          icon: 'success',
          confirmButtonText: 'OK'
          })
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
    console.log("in all users");
  },
};
</script>