<template>
    <div class="mt-2">
        <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'reportbooks.periods', params: {page: 7} }"><a href="#">Periode Rapor</a></router-link></li>
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'reportbooks.periods.students', params: {page: 7, period: period.id} }" class="router"><a href="#">{{period.title + ' - ' + period.school_year}}</a></router-link></li>
                <li class="breadcrumb-item active" aria-current="page" v-if="this.user.role === 'ADMIN'">{{studentGroup.name}}</li>
                <li class="breadcrumb-item active" aria-current="page" v-if="this.user.role === 'TEACHER'">Siswa Rayon</li>
            </ol>
        </nav>

        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>
        <h5 class="mt-5 title" v-if="this.user.role === 'ADMIN'">Absensi Rombel {{studentGroup.name}}</h5>
        <h5 class="mt-5 title" v-if="this.user.role === 'TEACHER'">Absensi Rayon</h5>
        <div class="table-responsive p-0 card-table mt-4">
            <table class="table table-bordered text-capitalize bg-white">
                <thead class="bg-muted text-left">
                    <tr>
                        <th class="text-center">No.</th>
                        <th>Nama</th>
                        <th>NIS</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alfa</th>
                        <th>Ubah</th>
                    </tr>
                </thead>
                <tbody style="border-top: 0;" v-if="students.length > 0">
                    <tr v-for="(student, index) in students" :key="index">
                        <td class="text-center">{{index+1}}</td>
                        <td>{{student.name}}</td>
                        <td>{{student.nis}}</td>
                        <td v-if="student.absences.length > 0">{{student.absences[0].sakit | absenCheck}}</td>
                        <td v-if="student.absences.length > 0">{{student.absences[0].izin | absenCheck}}</td>
                        <td v-if="student.absences.length > 0">{{student.absences[0].alpa | absenCheck}}</td>
                        <td v-if="student.absences.length < 1">-</td>
                        <td v-if="student.absences.length < 1">-</td>
                        <td v-if="student.absences.length < 1">-</td>
                        <td class="text-center"><span class="fas fa-edit cursor-pointer" @click="checkAbsen(student.id)"></span></td>
                    </tr>
                </tbody>
                <tbody style="border-top: 0;" v-else>
                    <tr>
                        <td colspan="7" class="pt-4">
                            <img src="/assets/img/sad.png" alt="not found" class="d-block img m-auto">
                            <h5 class="text-center mt-4">belum terdapat siswa di rombel ini.</h5>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- modal -->
        <modal v-if="modalUpdate" @close="modalUpdate = false" :action="updateAbsence">
            <h5 slot="header">Atur Kehadiran</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Sakit</label>
                    <input type="number" class="form-control" v-model="studentAbsencePayload.sakit">
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Izin</label>
                    <input type="number" class="form-control" v-model="studentAbsencePayload.izin">
                </div>
                <div class="form-group">
                    <label class="mb-2">Alfa</label>
                    <input type="number" class="form-control" v-model="studentAbsencePayload.alpa">
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
// modal
import modalComponent from '../../../components/Modal.vue';
export default {
    name: "absences",
    components: {
        "modal": modalComponent
    },
    data() {
        return {
            period: {},
            studentGroup: {},
            modalUpdate: false,
            students: [],
            studentAbsencePayload: {
                student_id: null,
                report_period_id: this.$route.params.period,
                alpa: 0,
                izin: 0,
                sakit: 0
            },
            status: '',
            user: {}
        }
    },
    created() {
        this.getPeriod(this.$route.params.period);
        this.getStudents();
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('reportPeriods', ['detail']),
        ...mapActions('studentGroups', ['detailStudentGroup']),
        ...mapActions('students', ['studentAbsence', 'studentRegionAbsence']),
        ...mapActions('studentAbsences', ['show', 'create', 'edit']),

        getPeriod(id) {
            this.detail(id).then((result) => {
                this.period = result;
            })
        },
        getStudents() {
            let user = JSON.parse(localStorage.getItem('user_data'));
            this.user = user;
            if (this.user.role === 'ADMIN') {
                this.getStudentGroup(this.$route.params.group);
                this.studentAbsence(this.$route.params.group).then((result) => {
                    this.students = result;
                })
            } else if (this.user.role === 'TEACHER') {
                this.studentRegionAbsence(this.$route.params.region).then((result) => {
                    this.students = result;
                })
            }
        },
        getStudentGroup(id) {
            this.detailStudentGroup(id).then((result) => {
                this.studentGroup = result;
            })
        },
        checkAbsen(id) {
            let payload = {studentId: id, reportPeriodId: this.$route.params.period};
            this.show(payload).then((result) => {
                if(result.length < 1) {
                    this.studentAbsencePayload.student_id = id;
                    this.studentAbsencePayload.alpa = 0;
                    this.studentAbsencePayload.izin = 0;
                    this.studentAbsencePayload.sakit = 0;
                    this.status = 'create';
                    this.modalUpdate = true;
                } else {
                    this.studentAbsencePayload = result[0];
                    this.status = 'update';
                    this.modalUpdate = true;
                }
            })
        },
        updateAbsence() {
            if(this.status === 'create') {
                this.create(this.studentAbsencePayload).then((result) => {
                    this.status = '';
                    this.getStudents();
                    this.modalUpdate = false;
                })
            }
            if(this.status === 'update') {
                let payload = {id: this.studentAbsencePayload.id, data: this.studentAbsencePayload};
                this.edit(payload).then((result) => {
                    this.status = '';
                    this.getStudents();
                    this.modalUpdate = false;
                })
            }
        }
    }
}
</script>

<style scoped>
h5.title {
    font-weight: 600 !important;
    font-size: 1.3rem;
    margin-top: 20px;
}

.cursor-pointer {
    cursor: pointer;
}

@media (max-width: 575px) {
    h5.title {
        font-size: 1rem;
    }
}
</style>