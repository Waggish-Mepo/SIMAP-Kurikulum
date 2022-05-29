<template>
    <div class="mt-2 pb-3">
        <div class="loader" v-if="isLoading"></div>
        <div v-if="isLoading" class="w-100 card-loading">
            <img src="/assets/img/loading.png" alt="loading" class="d-block m-auto">
        </div>
        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>

        <div v-if="!isLoading">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'reportbooks.periods', params: {page: 7} }"><a href="#">Periode Rapor</a></router-link></li>
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'reportbooks.periods.students', params: {page: 7, period: period.id} }" class="router"><a href="#">{{period.title + ' - ' + period.school_year}}</a></router-link></li>
                <li class="breadcrumb-item active" aria-current="page">{{student.name}}</li>
            </ol>
        </nav>

        <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
            <div>
                <h5 class="text-capitalize title">{{student.name}}</h5>
                <p class="text-secondary">{{student.name}} - {{studentGroup.name}}</p>
            </div>
            <div class="pt-3" v-if="this.roleAccount === 'ADMIN'">
                <button class="btn btn-white text-dark" v-if="student.prev" @click="prevNext(student.prev)"><span class="fas fa-arrow-left"></span>Sebelumnya</button>
                <button class="btn btn-white text-dark" v-if="!student.prev" disabled><span class="fas fa-arrow-left"></span>Sebelumnya</button>
                <button class="btn btn-white text-dark" v-if="student.next" @click="prevNext(student.next)">Selanjutnya <span class="fas fa-arrow-right"></span></button>
                <button class="btn btn-white text-dark" v-if="!student.next" disabled>Selanjutnya <span class="fas fa-arrow-right"></span></button>
            </div>
            <div class="pt-3" v-if="this.roleAccount === 'TEACHER'">
                <button class="btn btn-white text-dark" v-if="student.prev_in_region" @click="prevNext(student.prev_in_region)"><span class="fas fa-arrow-left"></span>Sebelumnya</button>
                <button class="btn btn-white text-dark" v-if="!student.prev_in_region" disabled><span class="fas fa-arrow-left"></span>Sebelumnya</button>
                <button class="btn btn-white text-dark" v-if="student.next_in_region" @click="prevNext(student.next_in_region)">Selanjutnya <span class="fas fa-arrow-right"></span></button>
                <button class="btn btn-white text-dark" v-if="!student.next_in_region" disabled>Selanjutnya <span class="fas fa-arrow-right"></span></button>
            </div>
        </div>
        <div class="mt-3 d-flex justify-content-between" v-if="subjectGroups.length >= 1">
            <div></div>
            <div>
                <a href="#" class="btn bg-blue1 text-white" @click="printReport">Cetak Rapor</a>
            </div>
        </div>
        <div v-if="subjectGroups.length < 1" class="w-100 card-not-found mt-5">
            <img src="/assets/img/sad.png" alt="not found" class="d-block img m-auto">
            <h5 class="text-center mt-4">siswa belum terdaftar dipelajaran apapun.</h5>
        </div>
        <div v-else>
        <h4 class="mt-4">A. Pengetahuan dan Keterampilan</h4>
        <div class="table-responsive p-0 card-table mt-4">
            <table class="table table-bordered text-capitalize bg-white" v-if="reportbook.curriculum === 'K21 | Sekolah Penggerak'">
                <thead class="bg-muted text-center">
                    <tr>
                        <th>No.</th>
                        <th>Mata Pelajaran</th>
                        <th>Skor Minimal</th>
                        <th>Nilai Akhir</th>
                        <th>Predikat</th>
                    </tr>
                </thead>
                <tbody style="border-top: 0;" v-for="(group, index) in subjectGroups" :key="index">
                    <tr>
                        <td colspan="5" class="r-category">{{group | groupSubject}}</td>
                    </tr>
                    <tr v-for="(scorecard,index) in filterSubjects(group)" :key="index">
                        <td class="text-center">{{index + 1}}</td>
                        <td>{{scorecard.gradebook.course.subject['name']}}</td>
                        <td class="text-center">{{scorecard.gradebook.scorebar}}</td>
                        <td class="text-center">
                            <span class="text-dark" v-if="scorecard.final_score == 100">{{scorecard.final_score | scoreCheck}}</span>
                            <span class="text-danger" v-else-if="scorecard.final_score < scorecard.gradebook.scorebar && scorecard.final_score !== null">{{scorecard.final_score | scoreCheck}}</span>
                            <span class="text-dark" v-else>{{scorecard.final_score | scoreCheck}}</span>
                        </td>
                        <td class="text-center">{{scorecard.predicate_desc['letter']}}</td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered text-capitalize bg-white" v-else>
                <thead class="text-center bg-muted">
                    <tr>
                        <th rowspan="2" style="vertical-align : middle;">No.</th>
                        <th rowspan="2" style="vertical-align : middle;">Mata Pelajaran</th>
                        <th rowspan="2" style="vertical-align : middle;">Skor Minimal</th>
                        <th colspan="2">Nilai Rapor</th>
                        <th rowspan="2" style="vertical-align : middle;">Nilai Akhir</th>
                        <th rowspan="2" style="vertical-align : middle;">Predikat</th>
                    </tr>
                    <tr>
                        <th>P</th>
                        <th>K</th>
                    </tr>
                </thead>
                <tbody style="border-top: 0;" v-for="(group, index) in subjectGroups" :key="index">
                    <tr>
                        <td colspan="7" class="r-category">{{group | groupSubject}}</td>
                    </tr>
                    <tr v-for="(scorecard,index) in filterSubjects(group)" :key="index">
                        <td class="text-center">{{index+1}}</td>
                        <td>{{scorecard.gradebook.course.subject['name']}}</td>
                        <td class="text-center">{{scorecard.gradebook.scorebar}}</td>
                        <td class="text-center">
                            <span class="text-dark" v-if="scorecard.knowledge_score == 100">{{scorecard.knowledge_score | scoreCheck}}</span>
                            <span class="text-danger" v-else-if="scorecard.knowledge_score < scorecard.gradebook.scorebar && scorecard.knowledge_score !== null">{{scorecard.knowledge_score | scoreCheck}}</span>
                            <span class="text-dark" v-else>{{scorecard.knowledge_score | scoreCheck}}</span>
                        </td>
                        <td class="text-center">
                            <span class="text-dark" v-if="scorecard.skill_score == 100">{{scorecard.skill_score | scoreCheck}}</span>
                            <span class="text-danger" v-else-if="scorecard.skill_score < scorecard.gradebook.scorebar && scorecard.skill_score !== null">{{scorecard.skill_score | scoreCheck}}</span>
                            <span class="text-dark" v-else>{{scorecard.skill_score | scoreCheck}}</span>
                        </td>
                        <td class="text-center">
                            <span class="text-dark" v-if="scorecard.final_score == 100">{{scorecard.final_score | scoreCheck}}</span>
                            <span class="text-danger" v-else-if="scorecard.final_score < scorecard.gradebook.scorebar && scorecard.final_score !== null">{{scorecard.final_score | scoreCheck}}</span>
                            <span class="text-dark" v-else>{{scorecard.final_score | scoreCheck}}</span>
                        </td>
                        <td class="text-center">{{scorecard.predicate_desc['letter']}}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h4 class="mt-3">B. Ketidakhadiran</h4>
        <div class="alert alert-warning mb-3 mt-2" v-if="reportbook.absences == null">Data kehadiran <b>{{student.name}}</b> belum diatur. Silahkan mengatur data kehadiran siswa terlebih dahulu.</div>
        <div class="table-responsive p-0 card-table mt-4" v-else>
            <table class="table table-bordered text-capitalize bg-white w-auto">
                <thead class="bg-muted">
                    <tr>
                        <th class="t-padding">Keterangan</th>
                        <th class="t-padding">Jumlah</th>
                        <th class="t-padding">Rincian</th>
                    </tr>
                </thead>
                <tbody style="border-top: 0;" class="text-center">
                    <tr>
                        <td>Sakit</td>
                        <td>{{reportbook.absences.sakit | absenCheck}}</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Ijin</td>
                        <td>{{reportbook.absences.izin | absenCheck}}</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Tanpa Keterangan</td>
                        <td>{{reportbook.absences.alpa | absenCheck}}</td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h4 class="mt-3">C. Catatan Akademik</h4>
        <div class="d-flex justify-content-between">
            <div></div>
            <button class="btn btn-white text-blue1 mb-2" @click="modalNote = true"><span class="fas fa-pen"></span>Edit</button>
        </div>
        <div class="card card-note w-100 bg-muted p-3 mb-5">
            <p class="text-secondary" v-if="!reportbook.notes">Buat cacatan akademik...</p>
            <p class="text-capitalize" v-if="reportbook.notes">{{reportbook.notes}}</p>
        </div>

        <div v-if="reportbook.curriculum !== 'K21 | Sekolah Penggerak'">
            <h4 class="mt-3">D. Deskripsi Perkembangan Karakter</h4>
            <div class="table-responsive p-0 card-table mt-4">
                <table class="table table-bordered bg-white text-center">
                    <thead class="bg-muted">
                        <tr class="text-capitalize">
                            <th>karakter yang dibangun</th>
                            <th>predikat</th>
                            <th>deskripsi</th>
                        </tr>
                    </thead>
                    <tbody style="border-top: 0;">
                        <tr v-for="(attitude, index) in attitudes" :key="index">
                            <td class="text-capitalize" style="vertical-align : middle;">{{attitude.name}}</td>
                            <td>
                                <select class="form-select btn bg-white border-none text-dark w-100" :id="attitude.id" @change="updateStudentAttitude">
                                    <option hidden>Pilih Predikat</option>
                                    <option v-for="(predicate,index) in attitude.attitude_predicates" :key="index" :value="predicate.id" :selected="studentAttitudeIds.includes(predicate.id)">{{predicate.predicate}}</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-select btn bg-white border-none text-dark w-100 disabled" disabled>
                                    <option hidden>-</option>
                                    <option v-for="(predicate,index) in attitude.attitude_predicates" :key="index" :value="predicate.id" :selected="studentAttitudeIds.includes(predicate.id)">{{predicate.description}}</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h4 class="mt-3">E. Catatan Perkembangan Karakter</h4>
            <div class="d-flex justify-content-between">
                <div></div>
                <button class="btn btn-white text-blue1 mb-2" @click="modalAttitudeNote = true"><span class="fas fa-pen"></span>Edit</button>
            </div>
            <div class="card card-note w-100 bg-muted p-3 mb-5">
                <p class="text-secondary" v-if="!reportbook.attitude_notes">Buat cacatan perkembangan karakter...</p>
                <p class="text-capitalize" v-if="reportbook.attitude_notes">{{reportbook.attitude_notes}}</p>
            </div>
        </div>

        <div class="d-none justify-content-between mt-5 mb-3">
            <div></div>
            <div class="card w-auto p-4 bg-white">
                <p>Wali Kelas</p>
                <button class="btn btn-secondary">Beri Tanda Tangan</button>
            </div>
        </div>
        </div>

        <!-- modal -->
        <modal v-if="modalNote" @close="modalNote = false" :action="addNote">
            <h5 slot="header">Tambah Catatan Akademik</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group">
                    <label class="mb-2">Catatan Akademik</label>
                    <textarea class="form-control" rows="4" v-model="reportbook.notes"></textarea>
                </div>
            </div>
        </modal>

        <modal v-if="modalAttitudeNote" @close="modalAttitudeNote = false" :action="addAttitudeNote">
            <h5 slot="header">Tambah Catatan Perkembangan Karakter</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group">
                    <label class="mb-2">Catatan Perkembangan Karakter</label>
                    <textarea class="form-control" rows="4" v-model="reportbook.attitude_notes"></textarea>
                </div>
            </div>
        </modal>

        <modal v-if="modalPrint" @close="modalPrint = false">
            <h5 slot="header">Gagal!</h5>
            <div slot="body">
                <p>Data tidak ditemukan! <b>Tidak dapat mengunduh rapor siswa {{student.name}}.</b> Silahkan untuk mengatur nilai pada siswa terlebih dahulu.</p>
            </div>
        </modal>
        </div>
    </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
// modal
import modalComponent from '../../../components/Modal.vue';
export default {
    name: "studentReport",
    components: {
        "modal": modalComponent
    },
    data() {
        return {
            roleAccount: null,
            period: {},
            student: {},
            studentGroup: {},
            modalNote: false,
            modalAttitudeNote: false,
            modalPrint: false,
            subjectGroups: [],
            reportbook: {},
            attitudes: [],
            studentAttitudeIds: [],
            studentAttitudePayload: {
                attitude_predicate_id: null,
                student_id: this.$route.params.student,
                reportbook_id: null
            },
            attitudeIds: []
        }
    },
    created() {
        this.getRole();
        this.getPeriod(this.$route.params.period);
        this.getStudentPrevNext(this.$route.params.student);
        this.getReportDetail();
        this.getAttitudes();
        this.getStudentAttitudes();
    },
    watch: {
        '$route.params.student': {
            handler: function() {
                this.getStudentPrevNext(this.$route.params.student);
                this.getReportDetail();
            },
            deep: true,
            immediate: true
        }
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('reportPeriods', ['detail']),
        ...mapActions('students', ['withPrevNext']),
        ...mapActions('studentGroups', ['detailStudentGroup']),
        ...mapActions('reportbooks', ['reportbookStudent', 'editNote', 'print', 'printAttitude']),
        ...mapActions('attitudes', ['index']),
        ...mapActions('studentAttitudes', ['studentAttitudes','createStudentAttitude', 'getStudentAttitudeId', 'editStudentAttitude']),

        getRole() {
            let user = JSON.parse(localStorage.getItem('user_data'));
            this.roleAccount = user.role;
        },
        getPeriod(id) {
            this.detail(id).then((result) => {
                this.period = result;
            })
        },
        getStudentPrevNext(id) {
            this.withPrevNext(id).then((result) => {
                this.student = result;
                this.detailStudentGroup(result.student_group_id).then((result) => {
                    this.studentGroup = result;
                })
            })
        },
        prevNext(id) {
            this.$router.push({ name: 'reportbooks.periods.students.report', params: {page: 7, period: this.period.id, student: id} });
        },
        getReportDetail() {
            let payload = {reportPeriodId: this.$route.params.period, studentId: this.$route.params.student};
            this.reportbookStudent(payload).then((result) => {
                this.reportbook = result;
                this.subjectGroups = result.subjectGroups;
                if (this.reportbook.attitude_config.length >= 1) {
                    this.reportbook.attitudes.forEach(attitude => {
                        if (!this.attitudeIds.includes(attitude['attitude_predicate']['attitude']['id'])) {
                            this.attitudeIds.push(attitude['attitude_predicate']['attitude']['id']);   
                        }
                    });
                } else {
                    this.attitudeIds = [];
                }
            })
        },
        filterSubjects(group) {
            return this.reportbook.scorecard.filter(function(sc) {
                return sc.gradebook.course.subject['group'] == group;
            });
        },
        addNote() {
            let payload = {id: this.reportbook.id, data: {notes: this.reportbook.notes}};
            this.editNote(payload).then((result) => {
                this.modalNote = false;
                this.getReportDetail();
            })
        },
        getAttitudes() {
            let payload = {report_period_id: this.$route.params.period};
            this.index(payload).then((result) => {
                this.attitudes = result['CHARACTER'];
            })
        },
        getStudentAttitudes() {
            this.studentAttitudes(this.$route.params.student).then((result) => {
                this.studentAttitudeIds = result;
            });
        },
        updateStudentAttitude(e) {
            let attitudeId = e.target.id;
            let attitudePredicateId = e.target.value;
            if (this.attitudeIds.includes(attitudeId)) {
                let payload = {attitude_id: attitudeId, student_id: this.$route.params.student};
                this.getStudentAttitudeId(payload).then((result) => {
                    let editPayload = {id: result, data: {attitude_predicate_id: attitudePredicateId, reportbook_id: this.reportbook.id}};
                    this.editStudentAttitude(editPayload).then((result) => {
                        location.reload();
                    })
                })
                // console.log('update');
            } else {
                this.studentAttitudePayload.attitude_predicate_id = attitudePredicateId;
                this.studentAttitudePayload.reportbook_id = this.reportbook.id;
                this.createStudentAttitude(this.studentAttitudePayload).then((result) => {
                    location.reload();
                })
                // console.log('create');
            }
        },
        addAttitudeNote() {
            let payload = {id: this.reportbook.id, data: {attitude_notes: this.reportbook.attitude_notes}};
            this.editNote(payload).then((result) => {
                this.modalAttitudeNote = false;
                this.getReportDetail();
            })
        },
        printReport() {
            let payload = {reportPeriodId: this.$route.params.period, studentId: this.$route.params.student, studentName: this.student.name, studentNIS: this.student.nis};
            if (this.subjectGroups.length < 1) {
                this.modalPrint = true;
            } else {
                this.print(payload).then(() => {});
                if (this.reportbook.curriculum !== 'K21 | Sekolah Penggerak' && this.reportbook.attitude_config.length >= 1) {
                    this.printAttitude(payload).then(() => {})   
                }
            }
        }
    }
}
</script>

<style scoped>
a.router {
    text-decoration: none !important;
    color: #000 !important;
}

h5.title {
    font-weight: 600 !important;
    font-size: 1.3rem;
    margin-top: 20px;
}

h4 {
    font-weight: 600;
    font-size: 1rem;
}

span.fas.fa-arrow-right {
    margin-left: 5px;
}

span.fas.fa-arrow-left,
span.fas.fa-pen {
    margin-right: 5px;
}

.bg-muted {
    background-color: #f3f3f3;
}

.r-category {
    padding-left: 1rem;
}

.t-padding {
    padding: 5px 35px !important;
}

.card-note {
    min-height: 50px;
}

.form-select.btn:disabled {
    opacity: 1 !important;
}

.form-select.disabled {
    background-image: none !important;
}

@media (max-width: 575px) {
    h5.title {
        font-size: 1rem;
    }
    h4 {
        font-size: 0.9rem;
    }
}
</style>