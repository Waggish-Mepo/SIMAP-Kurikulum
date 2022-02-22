<template>
    <div class="container-fluid">
        <div class="row no-gutter content">
            <div class="col-md-6 d-none d-md-flex bg-blue1 wave">
                <img src="assets/img/login.svg" alt="login" class="img-login">
            </div>
            <div class="col-md-6 bg-light">
                <div class="loader" v-if="isLoading"></div>
                <div class="d-flex align-items-center py-5">
                    <div class="container p-sm-5 p-3">
                        <form action="#" @submit.prevent="handleLogin">
                            <div class="mt-4 text-center">
                                <div class="alert alert-danger mb-3" v-if="errorMessage">
                                    {{ errorMessage }} <span v-if="errorMessage == 'Unauthenticated.'" class="font-weight-bold">Silahkan login kembali!</span>
                                </div>
                                <h1 class="text-blue1 text-capitalize font-weight-bold">welcome</h1>
                                <p class="text-dark-gray mb-5">Login to your account to continue</p>
                                <div class="mt-3 inputbox">
                                    <input type="text" class="form-control" v-model="formData.username" placeholder="Username" :class="{'is-invalid': errors.username}"><i class="fa fa-user"></i>
                                    <div class="invalid-feedback" v-if="errors.username">
                                        {{ errors.username[0] }}
                                    </div>
                                </div>
                                <div class="inputbox">
                                    <input type="password" class="form-control" v-model="formData.password"  placeholder="Password" id="myInput" :class="{'is-invalid': errors.password}"><i class="fa fa-lock"></i>
                                    <i class="fas fa-eye" @click="seePassword"></i>
                                    <div class="invalid-feedback" v-if="errors.password">
                                        {{ errors.password[0] }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between position-relative pb-5">
                                <div></div>
                                <div>
                                    <a href="#" class="forgot position-absolute">For assistance, contact Admin here</a>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">Log In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
export default {
    name: "login",
    data() {
        return {
            formData: {
                username: '',
                password: ''
            }
        }
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
       ...mapActions('auth', ['login']),

        seePassword() {
            var x = document.getElementById("myInput");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        },
        handleLogin() {
            this.login(this.formData).then((result) => {})
        },
        // testToken() {
        //     axios.get('/api/user').then(response => {
        //         console.log(response);
        //     });
        // }
    }
}
</script>

<style scoped>
.container {
  margin-top: 6%;
}

.img-login {
  max-width: 350px;
  width: 100%;
  display: block;
  margin: auto;
}

.content {
  min-height: 100vh;
}

.form-control {
  text-indent: 18px;
  padding: 8px 5px 8px 20px;
  background: #dadada;
  border: none;
  margin-bottom: 10px;
}

.form-control:focus {
  color: #495057;
  background: #dadada;
  border-color: #182A36;
  outline: 0;
  box-shadow: none;
}

.inputbox {
  position: relative;
  width: 80%;
  margin: auto;
}

.inputbox i {
  position: absolute;
  left: 12px;
  top: 12px;
  color: #495057;
  margin-right: 15px;
}

.inputbox i.fa-eye {
  left: 90%;
  cursor: pointer;
}

.forgot {
  font-size: 12px;
  text-decoration: none;
  color: #182A36;
  top: 10px;
  right: 50px;
}

.forgot:hover {
  text-decoration: underline;
}

.btn-primary {
  color: #fff;
  background-color: #182A36;
  border-color: #182A36;
  display: block;
  margin: auto;
  padding: 5px 45px;
  border-radius: 50px;
}

@media (max-width: 850px) {
  .form-control {
    padding: 8px 5px 8px 12px;
  }

  .inputbox i {
    left: 10px;
  }

  .inputbox i.fa-eye {
    left: 88%;
  }
}

@media (max-width: 770px) {
  .inputbox{
    width: 100%;
  }

  .inputbox i.fa-eye {
    left: 92%;
  }

  .forgot {
    right: 0;
  }
}

@media (max-width: 450px) {
  .form-control {
    text-indent: 12px;
  }

  .inputbox i {
    font-size: 12px;
    left: 8px;
  }

  .inputbox i.fa-eye {
    left: 90%;
  }
}
</style>
