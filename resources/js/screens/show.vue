<template>
  <main class="inbox">
    <div v-for="showEmail in email" :key="showEmail.id">
      <div class="toolbar">
        <div class="btn-group me-2 mb-2 mb-sm-0">
          <a
            href="#"
            @click.prevent="unreadEmail(showEmail.id)"
            type="button"
            class="btn btn-purple-primary"
          >
            <span class="action" key="fa-envelope" v-if="showEmail.read"
              ><i class="fas fa-envelope"></i
            ></span>
            <span class="action" key="fa-envelope-open" v-else
              ><i class="fas fa-envelope-open"></i
            ></span>
          </a>
          <a
            href="#"
            @click.prevent="starEmail(showEmail.id)"
            type="button"
            class="btn btn-purple-primary"
          >
            <span class="action" key="fas" v-if="showEmail.starred"
              ><i class="fas fa-star"></i
            ></span>
            <span class="action" key="far" v-else
              ><i class="far fa-star"></i
            ></span>
          </a>
          <a
            href="#"
            @click.prevent="bookmarkEmail(showEmail.id)"
            type="button"
            class="btn btn-purple-primary"
          >
            <span class="action" key="fas" v-if="showEmail.starred"
              ><i class="fas fa-bookmark"></i
            ></span>
            <span class="action" key="far" v-else
              ><i class="far fa-bookmark"></i
            ></span>
          </a>
        </div>
        <div class="btn-group">
          <router-link
            :to="{ name: 'reply', params: { id: showEmail.id } }"
            type="button"
            class="btn btn-purple-primary"
            ><i class="fa fa-reply"></i
          ></router-link>
          <router-link
            :to="{ name: 'forward', params: { id: showEmail.id } }"
            type="button"
            class="btn btn-purple-primary"
            ><i class="fa fa-share"></i
          ></router-link>
        </div>
        <a
          type="button"
          href="#"
          @click.prevent="deleteEmail(showEmail.id)"
          class="btn btn-purple-primary"
        >
          <span class="fas fa-trash"></span>
        </a>
        <div class="btn-group">
          <a
            type="button"
            class="btn btn-purple-primary dropdown-toggle"
            data-toggle="dropdown"
          >
            <span class="fas fa-tags"></span>
            <span class="caret"></span>
          </a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="#"
              >add label <span class="badge badge-danger"> Home</span></a
            >
            <a class="dropdown-item" href="#"
              >add label <span class="badge badge-info"> Job</span></a
            >
            <a class="dropdown-item" href="#"
              >add label <span class="badge badge-success"> Clients</span></a
            >
            <a class="dropdown-item" href="#"
              >add label <span class="badge badge-warning"> News</span></a
            >
          </div>
        </div>
      </div>
      <div class="card-body">
        <!-- /.box-header -->
        <div class="box-body no-padding">
          <div class="d-flex mb-4">
            <div class="flex-shrink-0 me-3">
              <img
                class="rounded-circle avatar-sm"
                src="https://icons-for-free.com/iconfiles/png/512/avatar+person+profile+user+icon-1320166578424287581.png"
                alt="Generic placeholder image"
              />
            </div>
            <div class="flex-grow-1 align-self-center" :set="sender = showEmail.from.split('<')">
              <h4 class="font-size-14 m-0">{{ sender[0] }}</h4>
              <small v-if="showEmail.to.length<=1" class="text-muted">{{ sender[1] }}</small>
              <small v-else class="text-muted">{{ showEmail.to.toString() }}</small>
            </div>
          </div>
          <!-- <div class="mailbox-read-info">
            <h3>{{ showEmail.subject }}</h3>
            <h5>
              From: {{ showEmail.from }}
              <span class="mailbox-read-time pull-right">{{
                showEmail.date
              }}</span>
            </h5>
          </div> -->
          <!-- /.mailbox-read-info -->
          <div class="body-box">
            <div class="mailbox-read-message" v-html="showEmail.html"></div>
          </div>
          <!-- /.mailbox-read-message -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <ul class="mailbox-attachments clearfix">
            <li v-for="attachment in showEmail.attachments" :key="attachment.id">
              <span class="mailbox-attachment-icon"
                ><i class="fas fa-file-pdf"></i
              ></span>

              <div class="mailbox-attachment-info">
                <a href="#" class="mailbox-attachment-name"
                  ><i class="fa fa-paperclip"></i> {{ attachment.getFilename }}</a
                >
                <span class="mailbox-attachment-size">
                  1,245 KB
                  <!-- <a href="{{ attachment.getFilename }}"
                                      class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a> -->
                </span>
              </div>
            </li>
          </ul>
        </div>


        <!-- replies -->

            <div class="accordion-item" v-for="reply in email_replies" :key="reply.id">
              <div class="accordion-header">

                <button v-on:click="reply.open = !reply.open" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                  <div class="d-flex mb-4" :set="sender = reply.from.split('<')">
                    <div class="flex-shrink-0 me-3">
                      <img
                        class="rounded-circle avatar-sm"
                        src="https://icons-for-free.com/iconfiles/png/512/avatar+person+profile+user+icon-1320166578424287581.png"
                        alt="Generic placeholder image"
                      />
                    </div>
                    <div class="flex-grow-1 align-self-center" >
                      <h4 class="font-size-14 m-0">{{ sender[0] }}</h4>
                      <small class="text-muted">{{ sender[1] }}</small>
                    </div>
                  </div>
                </button>
              </div>
              
                <div v-if="reply.open == true" class="accordion-body" v-html="reply.body"></div>
              
            </div>

        <!-- end replies -->

        <!-- /.box-footer -->
        <div class="box-footer">
          <div class="pull-right">
            <router-link
              :to="{ name: 'reply', params: { id: showEmail.id } }"
              type="button"
              class="btn btn-default"
              ><i class="fa fa-reply"></i> Reply</router-link
            >
            <router-link
              :to="{ name: 'forward', params: { id: showEmail.id } }"
              type="button"
              class="btn btn-default"
              ><i class="fa fa-share"></i> Forward</router-link
            >
          </div>
          <a
            type="button"
            href="#"
            @click.prevent="deleteEmail(showEmail.id)"
            class="btn btn-default"
            ><i class="fas fa-trash"></i> Delete</a
          >
          <button type="button" class="btn btn-default">
            <i class="fa fa-print"></i> Print
          </button>
        </div>
      </div>

      <!-- /.box-footer -->
    </div>

  </main>
</template>

<script>
export default {
  data() {
    return {
      email: {},
      email_replies: {},
      id: 0,
      rows: {
        q1: {
          term: 'Term 1',
          details: 'Some text here...',
          open: false
        },
        q2: {
          term: 'Term 2',
          details: 'Some text here...',
          open: false
        },
        q3: {
          term: 'Term 3',
          details: 'Some text here...',
          open: false
        }
		  }
    };
  },
  methods: {
    loadEmail(id) {
      axios
        .get("/inbox/api/show/" + this.id)
        .then(({ data }) => {
          this.email = data.show
          this.email_replies = data.replies

        });
    },
    starEmail(id) {
      axios.get("/inbox/api/star/" + this.id).then(() => {
        this.loadEmail(this.id);
      });
    },
    bookmarkEmail(id) {
      axios.get("/inbox/api/bookmark/" + this.id).then(() => {
        this.loadEmail(this.id);
      });
    },
    unreadEmail(id) {
      axios.get("/inbox/api/unread/" + this.id).then(() => {
        this.loadEmail(this.id);
      });
    },
    deleteEmail(id) {
      axios.get("/inbox/api/delete/" + this.id).then(() => {
        this.$router.push("/dashboard");
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
    this.id = this.$route.params.id;
    this.loadEmail(this.$route.params.id);
  },
};
</script>

<style scoped>
.body-box {
  padding: 1.5rem;
  margin-right: 0;
  margin-left: 0;
  border-width: 0.2rem;
  position: relative;
  padding: 1rem;
  margin: 1rem -15px 0;
  border: solid #f8f9fa;
}
</style>