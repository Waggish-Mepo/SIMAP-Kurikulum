<template>
    <div class="mt-2">
        <div class="loader" v-if="isLoading"></div>
        <div v-if="isLoading" class="w-100 card-loading">
            <img src="/assets/img/loading.png" alt="loading" class="d-block m-auto">
        </div>
        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>

        <div v-if="!isLoading">
        <nav aria-label="breadcrumb" class="mb-5">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'courses', params: {page: 5} }"><a href="#">Pelajaran</a></router-link></li>
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'courses.students', params: {page: 5, course: $route.params.course} }" class="router">{{course.caption}} Kelas {{course.entry_year_with_class}}</router-link></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Siswa Terdaftar</li>
            </ol>
        </nav>

        <div class="alert alert-info mb-3">mohon untuk menekan tombol <strong>tambahkan</strong> pada tiap-tiap table rombel, apabila telah memilih siswa dan ingin mendaftarkannya</div>
        
        <div class="col-12 mt-3">
            <div v-for="(sc, index) in studentCourses" :key="index" class="mb-2">
                <div class="card card-course w-100 shadow-sm bg-white p-3" data-bs-toggle="collapse" aria-expanded="false" @click="showPanelCollapse(sc.id, index)">
                    <div class="d-flex justify-content-between text-capitalize">
                        <div><span class="fas fa-book"></span>
                        {{sc.name}}</div>
                        <div>
                            <i class="fas fa-arrow-down" :id="index"></i>
                        </div>
                    </div>
                </div>
                <div class="collapse" :id="sc.id">
                    <div class="card-table w-100">
                        <div class="card-body">
                            <h4 v-if="sc.data.length > 0" class="card-title">Data siswa rombel {{sc.name}}</h4>
                            <p v-if="sc.data.length > 0" class="card-description text-capitalize">{{course.caption}} Kelas {{course.entry_year_with_class}}</p>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr v-if="sc.data.length > 0">
                                            <vue-good-table
                                                :columns="columns"
                                                :rows="sc.data"
                                                :pagination-options="{ enabled: false }"
                                                :sort-options="sortOpts"
                                                :fixed-header="true"
                                                :line-numbers="true"
                                                @on-selected-rows-change="selectionChanged"
                                                :select-options="{ enabled: true }"
                                                max-height="800px"
                                                styleClass="vgt-table condensed striped"
                                            >
                                            <div slot="selected-row-actions">
                                                <a href="#" class="btn btn-sm btn-success" @click="getSelected">Tambahkan</a>
                                            </div>
                                            </vue-good-table>
                                        </tr>
                                        <tr v-else>
                                            <span class="text-left">
                                                Data siswa tidak tersedia atau sudah terdaftar pada pelajaran. Silahkan cek laman sebelumnya untuk melihat daftar siswa {{sc.name}} yang telah terdaftar pada pelajaran. Jika siswa tetap tidak ada, silahkan cek pada menu "data siswa" pastikan siswa telah terdaftar pada rombel {{sc.name}}
                                            </span>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- if data null -->
        <div v-if="studentCourses.length < 1" class="w-100 card-not-found">
            <img src="/assets/img/sad.png" alt="not found" class="d-block img m-auto">
            <h5 class="text-center text-capitalize mt-4">data terkait tidak ditemukan</h5>
        </div>
        </div>
    </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
export default {
    name: "studentCourseAdd",
    data() {
        return {
            course: {},
            studentCourses: [],
            columns: [
                {
                    label: "NIS",
                    field: "nis",
                    filterOptions: { enabled: true },
                },
                {
                    label: "Nama",
                    field: "name",
                    filterOptions: { enabled: true },
                },
                {
                    label: "Jenis Kelamin",
                    field: "jk",
                    filterOptions: { enabled: true },
                },
            ],
            sortOpts: { enabled: true },
            addStudentData: [],
            finalStudentId: []
        }
    },
    created() {
        this.getCourse(this.$route.params.course);
        this.getStudentCourses(this.$route.params.course);
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('studentCourses', ['showSelected', 'create']),
        ...mapActions('courses', ['show']),

        getCourse(id) {
            this.show(id).then((result) => {
                this.course = result;
            })
        },
        getStudentCourses(id) {
            this.showSelected(id).then((result) => {
                this.studentCourses = result;
            });
        },
        showPanelCollapse(PBody, PTitle) {
            let panelBody = document.getElementById(PBody);
            panelBody.classList.toggle('show');
            let iconTitle = document.getElementById(PTitle);
            iconTitle.classList.toggle('fa-arrow-down');
            iconTitle.classList.toggle('fa-arrow-up');
        },
        selectionChanged(e) {
            this.addStudentData = e.selectedRows;
        },
        getSelected() {
            for (let i = 0; i < this.addStudentData.length; i++) {
                if (!this.finalStudentId.includes(this.addStudentData[i].id)) {
                    this.finalStudentId.push(this.addStudentData[i].id);
                }
            }
            let payload = {course_id: this.$route.params.course, payload: this.finalStudentId};
            this.create(payload).then((result) => {
                this.getStudentCourses(this.$route.params.course);
            })
        }
    }
}
</script>

<style scoped>
a:hover {
    text-decoration: none;
}

.card {
    border: 0 !important;
}

.card-course {
    cursor: pointer;
}

span.fas.fa-book {
    margin-right: 5px;
}

.form-group {
    margin-bottom: 15px;
}

label.mb-2 {
    font-weight: 600;
}

/* table */
.flex {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
}

.card-table {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    border: none;
    border-radius: 0;
    background-color: #eaeaea;
}

.card-table .card-title {
    color: #000;
    margin-bottom: 10px;
    text-transform: capitalize;
    font-size: 1rem;
    font-weight: 600;
}

.card-table .card-description {
    color: #000;
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
}

@media (max-width: 470px) {
    .btn {
        font-size: 0.8 !important;
        padding: 0.2rem 0.5rem !important;
    }

    i {
        font-size: 1rem;
    }

    .list-group-item {
        font-size: 0.8rem;
    }
}
</style>