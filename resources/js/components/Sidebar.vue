<template>
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div> 
                <a href="#" class="nav_logo"><i class="fas fa-user nav_logo-icon"></i> <span class="nav_logo-name">{{user.name}}</span></a>
                <hr>
                <div class="nav_list" v-if="user.role === 'ADMIN'"> 
                    <router-link v-bind:to="{ name: 'dashboard' }">
                    <a href="#" class="nav_link" :class="{active: !$route.params.page}"> 
                        <i class="fas fa-home nav_icon" title="Dashboard"></i> 
                        <span class="nav_name">Dashboard</span> 
                    </a> 
                    </router-link>
                    <router-link v-bind:to="{ name: 'teachers', params: {page: 1} }">
                    <a href="#" class="nav_link" :class="{active: $route.params.page == 1}"> 
                        <i class="fas fa-chalkboard-teacher nav_icon" title="Guru"></i> 
                        <span class="nav_name">Guru</span> 
                    </a> 
                    </router-link>
                    <router-link v-bind:to="{ name: 'regions', params: {page: 8} }">
                    <a href="#" class="nav_link" :class="{active: $route.params.page == 8}"> 
                        <i class="far fa-map nav_icon" title="Rayon"></i> 
                        <span class="nav_name">Rayon</span> 
                    </a> 
                    </router-link>
                    <router-link v-bind:to="{ name: 'mata_pelajaran', params: {page: 2} }">
                    <a href="#" class="nav_link" :class="{active: $route.params.page == 2}"> 
                        <i class="fas fa-stream nav_icon" title="Mata Pelajaran"></i> 
                        <span class="nav_name">Mata Pelajaran</span> 
                    </a> 
                    </router-link>
                    <router-link v-bind:to="{ name: 'periode_rapor', params: {page: 3} }">
                    <a href="#" class="nav_link" :class="{active: $route.params.page == 3}"> 
                        <i class="fas fa-book-open nav_icon" title="Periode Rapor"></i> 
                        <span class="nav_name">Periode Rapor</span>
                    </a>
                    </router-link>
                    <router-link v-bind:to="{ name: 'batches', params: {page: 4} }">
                    <a href="#" class="nav_link" :class="{active: $route.params.page == 4}"> 
                        <i class="fas fa-users nav_icon" title="Data Siswa"></i> 
                        <span class="nav_name">Data Siswa</span>
                    </a>
                    </router-link>
                    <router-link v-bind:to="{ name: 'courses', params: {page: 5} }">
                    <a href="#" class="nav_link" :class="{active: $route.params.page == 5}"> 
                        <i class="fas fa-inbox nav_icon" title="Pelajaran"></i> 
                        <span class="nav_name">Pelajaran</span>
                    </a>
                    </router-link>
                    <router-link v-bind:to="{ name: 'gradebooks.period', params: {page: 6} }">
                    <a href="#" class="nav_link" :class="{active: $route.params.page == 6}"> 
                        <i class="fas fa-book nav_icon" title="Buku Nilai"></i> 
                        <span class="nav_name">Buku Nilai</span>
                    </a>
                    </router-link>
                    <router-link v-bind:to="{ name: 'reportbooks.periods', params: {page: 7} }">
                    <a href="#" class="nav_link" :class="{active: $route.params.page == 7}"> 
                        <i class="fas fa-copy nav_icon" title="Rapor Siswa"></i> 
                        <span class="nav_name">Rapor Siswa</span>
                    </a>
                    </router-link>
                </div> 

                <div class="nav_list" v-if="user.role === 'TEACHER'">
                    <router-link v-bind:to="{ name: 'dashboard' }">
                    <a href="#" class="nav_link" :class="{active: !$route.params.page}"> 
                        <i class="fas fa-home nav_icon" title="Dashboard"></i> 
                        <span class="nav_name">Dashboard</span> 
                    </a> 
                    </router-link>
                    <router-link v-bind:to="{ name: 'courses.teacher-role', params: {page: 5} }">
                    <a href="#" class="nav_link" :class="{active: $route.params.page == 5}"> 
                        <i class="fas fa-inbox nav_icon" title="Pelajaran"></i> 
                        <span class="nav_name">Pelajaran</span>
                    </a>
                    </router-link>
                    <router-link v-bind:to="{ name: 'gradebooks.period', params: {page: 6} }">
                    <a href="#" class="nav_link" :class="{active: $route.params.page == 6}"> 
                        <i class="fas fa-book nav_icon" title="Buku Nilai"></i> 
                        <span class="nav_name">Buku Nilai</span>
                    </a>
                    </router-link>
                </div>
            </div>
            <a href="#" class="nav_link" @click="modalShow = true">
                <i class="fas fa-power-off nav_icon"></i> 
                <span class="nav_name">LogOut</span> 
            </a>
        </nav>

        <!-- modal -->
        <modal v-if="modalShow" @close="modalShow = false" :logout="handleLogout">
            <h5 slot="header">Logout</h5>
            <span slot="body">Yakin untuk <b>logout</b>? anda tidak akan dapat mengakses data setelah logout, pastikan untuk <b>login</b> kembali untuk mengakses data.</span>
        </modal>
    </div>
</template>

<script>
// modal
import modalComponent from './Modal.vue';
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
export default {
    name: "Sidebar",
    components: {
        "modal": modalComponent
    },
    data() {
        return {
            modalShow: false,
            user: {}
        }
    },
    created() {
        this.getUser();
    },
    methods: {
        ...mapActions('auth', ['logout', 'getMe']),

        handleLogout() {
            this.logout().then(() => { });
        },
        getUser() {
            this.getMe().then((result) => {
                this.user = result.data;
            });
        },
    }
}
</script>

<style scoped>
a:hover {
    text-decoration: none;
}

.l-navbar {
    position: fixed;
    top: 0;
    left: -30%;
    width: 48px;
    height: 100vh;
    background-color: #182A36;
    padding: .5rem 0 0 0;
    transition: .5s;
    z-index: 100;
}

.nav {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
}

hr {
    color: #B4ADAD;
    margin: 0.9rem 0 1rem 0 !important;
}

.nav_logo,
.nav_link {
    display: grid;
    grid-template-columns: max-content max-content;
    align-items: center;
    column-gap: 1rem;
    padding: .3rem 1rem .3rem 1rem;
}

.nav_logo-icon {
    font-size: 0.8rem;
    color: #fff;
}

.nav_logo-name {
    color: #fff;
    font-weight: 700;
    margin-left: 10px;
}

.nav_name {
    margin-left: 3px;
}

.nav_link {
    position: relative;
    color: #B4ADAD;
    margin-bottom: 0.4rem;
    transition: .3s;
}

.nav_link:hover {
    color: #fff;
}

.nav_icon {
    font-size: 0.8rem;
}

.show {
    left: 0;
}

.active {
    color: #fff;
}

.active::before {
    content: '';
    position: absolute;
    left: 0;
    width: 2px;
    height: 32px;
    background-color: #fff;
}

.height-100 {
    height: 100vh;
}

@media screen and (min-width: 768px) {
    .l-navbar {
        left: 0;
        padding: 1rem 0 0 0;
        width: 50px;
    }

    .show {
        width: calc(48px + 156px)
    }

    .nav_logo-icon, .nav_icon {
        font-size: 1rem;
    }

    .nav_link {
        margin-bottom: 0.6rem;
    }
}
</style>