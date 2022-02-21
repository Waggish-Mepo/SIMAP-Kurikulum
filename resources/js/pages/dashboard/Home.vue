<template>
  <div>
    <div class="loader" v-if="isLoading"></div>
    <div class="alert alert-danger mb-3" v-if="errorMessage">
      {{ errorMessage }}
    </div>
    <h4 class="text-capitalize my-3 font-weight-bold">selamat datang, {{user.name}}</h4>
    <div class="d-flex flex-wrap justify-content-center mt-5">
      <div class="w-box mb-3 shadow">
        <div class="box bg-white">
          <div class="d-flex align-items-center">
            <div class="rounded-circle mr-3 ml-0 text-center d-flex align-items-center justify-content-center bg-green1 pale"> 
              <i class="fas fa-users"></i>
            </div>
            <div class="d-flex flex-column"> 
              <h3 class="text-capitalize text-muted">admin</h3>
              <p class="text-book font-weight-bold h3">{{accounts.admin | numFormatter}}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="w-box mb-3 shadow">
        <div class="box bg-white">
          <div class="d-flex align-items-center">
            <div class="rounded-circle mr-3 ml-0 text-center d-flex align-items-center justify-content-center bg-green1 pale"> 
              <i class="fas fa-users"></i>
            </div>
            <div class="d-flex flex-column"> 
              <h3 class="text-capitalize text-muted">guru</h3>
              <p class="text-book font-weight-bold h3">{{accounts.teachers | numFormatter}}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="w-box mb-3 shadow">
        <div class="box bg-white">
          <div class="d-flex align-items-center">
            <div class="rounded-circle mr-3 ml-0 text-center d-flex align-items-center justify-content-center bg-green1 pale"> 
              <i class="fas fa-users"></i>
            </div>
            <div class="d-flex flex-column"> 
              <h3 class="text-capitalize text-muted">peserta didik</h3>
              <p class="text-book font-weight-bold h3">{{accounts.students | numFormatter}}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
export default {
  name: "home",
  data() {
    return {
      user: {},
      accounts: {}
    }
  },
  created(){
    this.getUser();
    this.statistics();
  },
  computed: {
    ...mapState(['errorMessage', 'errors', 'isLoading']),
  },
  methods: {
    ...mapActions('auth', ['getMe']),
    ...mapActions('teachers', ['getStatistics']),

    getUser() {
      this.getMe().then((result) => {
        this.user = result.data;
      });
    },
    statistics() {
      this.getStatistics().then((result) => {
        this.accounts = result;
      })
    }
  }
}
</script>

<style scoped>
.w-box{
  width: 200px;
  margin-right: 25px;
}

.box {
  padding: 15px 20px;
  transition: all .4s ease-in-out;
  cursor: pointer;
}

.box:hover {
  box-shadow: 2px 2px 10px #a5a5a5;
  transform: scale(1.03);
}

.pale i {
  font-size: 20px;
  color: #fff;
}

p.text-book {
  margin: 0;
  font-size: 1.3rem;
}

h3.text-muted {
  font-size: 1rem;
}

.rounded-circle {
  width: 45px;
  height: 45px;
  margin-right: 15px;
}
</style>