<template>
    <div>
        <div class="loader" v-if="isLoading"></div>
        <h4 class="text-capitalize mt-3 mb-4 font-weight-bold">akun guru</h4>
        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>

        <div class="table-responsive p-0 card-table mt-4">
            <table class="table table-bordered text-capitalize bg-white">
                <thead class="bg-muted text-left">
                    <tr>
                        <th class="text-center">No.</th>
                        <th>Nama</th>
                        <th>Mata Pelajaran</th>
                        <th>Ubah</th>
                    </tr>
                </thead>
                <tbody style="border-top: 0;" v-if="teachers.length > 0">
                    <tr v-for="(teacher, index) in teachers" :key="index">
                        <td class="text-center">{{index+1}}</td>
                        <td>nama</td>
                        <td>mapel</td>
                        <td class="text-center"><span class="fas fa-edit cursor-pointer"></span></td>
                    </tr>
                </tbody>
                <tbody style="border-top: 0;" v-else>
                    <tr>
                        <td colspan="4" class="pt-4">
                            <img src="/assets/img/sad.png" alt="not found" class="d-block img m-auto">
                            <h5 class="text-center mt-4">belum terdapat siswa di rombel ini.</h5>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
export default {
    name: "teachers",
    data() {
        return {
            teachers: []
        }
    },
    created() {
        this.getTeachers();
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('teachers', ['withSubject']),

        getTeachers() {
            this.withSubject().then((result) => {
                // this.teachers = result;
            })
        }
    }
}
</script>

<style scoped>
h4 {
    font-weight: 600;
    font-size: 1rem;
}

.cursor-pointer {
    cursor: pointer;
}

@media (max-width: 575px) {
    h4 {
        font-size: 0.9rem;
    }
}
</style>