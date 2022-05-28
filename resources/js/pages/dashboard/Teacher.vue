<template>
    <div>
        <div class="loader" v-if="isLoading"></div>
        <div v-if="isLoading" class="w-100 card-loading">
            <img src="/assets/img/loading.png" alt="loading" class="d-block m-auto">
        </div>
        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>

        <div v-if="!isLoading">
        <h4 class="text-capitalize mt-3 mb-4 font-weight-bold">akun guru</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="text" class="form-control input-text shadow-sm bg-white" placeholder="Cari Guru...." @keyup="getTeachers(payloadGet)" v-model="payloadGet.search">
                    <div class="input-group-append">
                        <a href="#" class="btn btn-outline-muted btn-lg shadow-sm bg-white" @click="getTeachers(payloadGet)"><i class="fa fa-search"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex">
                    <a href="#" class="btn btn-primary btn-block mt-md-1" @click="modalAdd = true">
                        <span class="fas fa-plus"></span> Tambah Guru
                    </a>
                    <a href="#" class="btn btn-secondary btn-block mt-md-1" @click="payloadGet.search = ''; getTeachers(payloadGet)">Refresh Data</a>
                </div>
            </div>
        </div>

        <div class="table-responsive p-0 card-table mt-4">
            <table class="table table-bordered text-capitalize bg-white">
                <thead class="bg-muted text-left">
                    <tr>
                        <th class="text-center">No.</th>
                        <th>Nama</th>
                        <th>Akun</th>
                        <th>NUPTK</th>
                        <th>Jenis Kelamin</th>
                        <th>Mata Pelajaran</th>
                        <th>Ubah</th>
                    </tr>
                </thead>
                <tbody style="border-top: 0;" v-if="teachers.length > 0">
                    <tr v-for="(teacher, index) in teachers" :key="index">
                        <td class="text-center">{{index+1}}</td>
                        <td>{{teacher.name}}</td>
                        <td v-if="teacher.user">{{teacher.user['username']}}</td>
                        <td v-else>-</td>
                        <td>{{teacher.nuptk}}</td>
                        <td>{{teacher.jk}}</td>
                        <td v-if="teacher.teacher_details_string">{{teacher.teacher_details_string}}</td>
                        <td v-else>-</td>
                        <td class="text-center"><span class="fas fa-edit cursor-pointer" @click="showTeacher(teacher.id)"></span></td>
                    </tr>
                </tbody>
                <tbody style="border-top: 0;" v-else>
                    <tr>
                        <td colspan="6" class="pt-4">
                            <img src="/assets/img/sad.png" alt="not found" class="d-block img m-auto">
                            <h5 class="text-center mt-4">belum terdapat siswa di rombel ini.</h5>
                        </td>
                    </tr>
                </tbody>
            </table>

            <pagination class="mt-3" :pagination="pages" @paginate="getTeachers" :offset="2" :data="payloadGet"></pagination>
        </div>

        <!-- modal -->
        <modal v-if="modalAdd" @close="modalAdd = false" :action="addTeacher">
            <h5 slot="header">Tambah Guru</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group mb-2">
                    <label class="mb-2">Nama</label>
                    <input type="text" class="form-control" v-model="teacherAddForm.name" :class="{'is-invalid': errors.name}">
                    <div class="invalid-feedback" v-if="errors.name">
                        {{ errors.name[0] }}
                    </div>
                </div>
                <div class="form-group mb-2">
                    <label class="mb-2">NUPTK</label>
                    <input type="text" class="form-control" v-model="teacherAddForm.nuptk" :class="{'is-invalid': errors.nuptk}">
                    <div class="invalid-feedback" v-if="errors.nuptk">
                        {{ errors.nuptk[0] }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-2">Jenis Kelamin</label>
                    <small class="text-danger" v-if="errors.jk">
                        {{ errors.jk[0] }}
                    </small>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="Laki-Laki" v-model="teacherAddForm.jk" id="male">
                        <label class="form-check-label text-capitalize" for="male">Laki-Laki</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="Perempuan" v-model="teacherAddForm.jk" id="female">
                        <label class="form-check-label text-capitalize" for="female">Perempuan</label>
                    </div>
                </div>
            </div>
        </modal>

        <modal v-if="modalEdit" @close="modalEdit = false" :action="editTeacher">
            <h5 slot="header">Edit Guru</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="alert alert-info mb-3">Untuk ubah <strong>mata pelajaran</strong> yang diampu guru dapat dilakukan di <strong>menu mata pelajaran</strong></div>
                <div class="form-group mb-2">
                    <label class="mb-2">Nama</label>
                    <input type="text" class="form-control" v-model="teacher.name" :class="{'is-invalid': errors.name}">
                    <div class="invalid-feedback" v-if="errors.name">
                        {{ errors.name[0] }}
                    </div>
                </div>
                <div class="form-group mb-2">
                    <label class="mb-2">NUPTK</label>
                    <input type="text" class="form-control" v-model="teacher.nuptk" :class="{'is-invalid': errors.nuptk}">
                    <div class="invalid-feedback" v-if="errors.nuptk">
                        {{ errors.nuptk[0] }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-2">Jenis Kelamin</label>
                    <small class="text-danger" v-if="errors.jk">
                        {{ errors.jk[0] }}
                    </small>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="Laki-Laki" v-model="teacher.jk" id="male">
                        <label class="form-check-label text-capitalize" for="male">Laki-Laki</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="Perempuan" v-model="teacher.jk" id="female">
                        <label class="form-check-label text-capitalize" for="female">Perempuan</label>
                    </div>
                </div>
            </div>
        </modal>
        </div>
    </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
// modal
import modalComponent from '../../components/Modal.vue';
// pagination
import paginateComponent from '../../components/Pagination.vue';
export default {
    name: "teachers",
    components: {
        "modal": modalComponent,
        "pagination": paginateComponent
    },
    data() {
        return {
            teachers: [],
            teacher: {},
            teacherAddForm: {
                name: '',
                nuptk: null,
                jk: ''
            },
            modalAdd: false,
            modalEdit: false,
            pages: {
                total: 0,
                per_page: 20,
                from: 1,
                to: 0,
                current_page: 1,
                last_page: 1,
            },
            payloadGet: {
                search: '',
                page: 1,
                per_page: 20
            },
        }
    },
    created() {
        this.getTeachers(this.payloadGet);
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('teachers', ['withSubject', 'detail', 'create', 'update']),

        getTeachers(payload) {
            this.withSubject(payload).then((result) => {
                this.teachers = result.data;
                this.pages.total = result.total;
                this.pages.per_page = result.per_page;
                this.pages.from = result.from;
                this.pages.to = result.to;
                this.pages.current_page = result.current_page;
                this.pages.last_page = result.last_page;
            })
        },
        addTeacher() {
            this.create(this.teacherAddForm).then((result) => {
                this.modalAdd = false;
                this.teacherAddForm.name = null;
                this.teacherAddForm.nuptk = null;
                this.teacherAddForm.jk = null;
                this.getTeachers(this.payloadGet);
            })
        },
        showTeacher(id) {
            this.detail(id).then((result) => {
                this.teacher = result;
                this.modalEdit = true;
            })
        },
        editTeacher() {
            let payload = {id: this.teacher.id, data: this.teacher};
            this.update(payload).then((result) => {
                this.modalEdit = false;
                this.getTeachers(this.payloadGet);
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

.btn-outline-muted {
    color: #535353;
    border-color: #B4ADAD;
    border-radius: 0px !important;
}

.btn-secondary {
    margin-left: 10px !important;
}

@media (max-width: 575px) {
    h4 {
        font-size: 0.9rem;
    }
}
</style>