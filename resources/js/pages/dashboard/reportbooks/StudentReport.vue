<template>
    <div class="mt-2 pb-3">
        <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'reportbooks.periods', params: {page: 7} }"><a href="#">Periode Rapor</a></router-link></li>
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'reportbooks.periods.students', params: {page: 7, period: period.id} }" class="router"><a href="#">{{period.title + ' - ' + period.school_year}}</a></router-link></li>
                <li class="breadcrumb-item active" aria-current="page">{{student.name}}</li>
            </ol>
        </nav>

        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
            <div>
                <h5 class="text-capitalize title">{{student.name}}</h5>
                <p class="text-secondary">{{student.name}} - {{studentGroup.name}}</p>
            </div>
            <div class="pt-3">
                <button class="btn btn-white text-dark" v-if="student.prev"><span class="fas fa-arrow-left" @click="prevNext(student.prev)"></span>Sebelumnya</button>
                <button class="btn btn-white text-dark" v-if="!student.prev" disabled><span class="fas fa-arrow-left"></span>Sebelumnya</button>
                <button class="btn btn-white text-dark" v-if="student.next" @click="prevNext(student.next)">Selanjutnya <span class="fas fa-arrow-right"></span></button>
                <button class="btn btn-white text-dark" v-if="!student.next" disabled>Selanjutnya <span class="fas fa-arrow-right"></span></button>
            </div>
        </div>
        <div class="mt-3 d-flex justify-content-between">
            <div></div>
            <div>
                <a href="#" class="btn bg-blue1 text-white">Cetak Rapor</a>
            </div>
        </div>
        <h4 class="mt-4">A. Pengetahuan dan Keterampilan</h4>
        <div class="table-responsive p-0 card-table mt-4">
            <!-- <table class="table table-bordered text-capitalize bg-white">
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
                <tbody style="border-top: 0;">
                    <tr>
                        <td colspan="7" class="r-category">A. (Muatan Nasional)</td>
                    </tr>
                    <tr>
                        <td class="text-center">1</td>
                        <td>Bahasa Indonesia</td>
                        <td class="text-center">75.00</td>
                        <td class="text-center">80.00</td>
                        <td class="text-center">80.00</td>
                        <td class="text-center">80.00</td>
                        <td class="text-center">B</td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>Bahasa Inggris</td>
                        <td class="text-center">75.00</td>
                        <td class="text-center">80.00</td>
                        <td class="text-center">80.00</td>
                        <td class="text-center">80.00</td>
                        <td class="text-center">B</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="r-category">C. (Muatan Keahlian)</td>
                    </tr>
                    <tr>
                        <td class="text-center">1</td>
                        <td>Pemrograman Web</td>
                        <td class="text-center">75.00</td>
                        <td class="text-center">80.00</td>
                        <td class="text-center">80.00</td>
                        <td class="text-center">80.00</td>
                        <td class="text-center">B</td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>Pemrograman Dasar</td>
                        <td class="text-center">75.00</td>
                        <td class="text-center">80.00</td>
                        <td class="text-center">80.00</td>
                        <td class="text-center">80.00</td>
                        <td class="text-center">B</td>
                    </tr>
                </tbody>
            </table> -->
            <table class="table table-bordered text-capitalize bg-white">
                <thead class="bg-muted text-center">
                    <tr>
                        <th>No.</th>
                        <th>Mata Pelajaran</th>
                        <th>Skor Minimal</th>
                        <th>Nilai Akhir</th>
                        <th>Predikat</th>
                    </tr>
                </thead>
                <tbody style="border-top: 0;">
                    <tr>
                        <td colspan="5" class="r-category">A. (Muatan Nasional)</td>
                    </tr>
                    <tr>
                        <td class="text-center">1</td>
                        <td>Bahasa Indonesia</td>
                        <td class="text-center">75.00</td>
                        <td class="text-center">80.00</td>
                        <td class="text-center">B</td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>Bahasa Inggris</td>
                        <td class="text-center">75.00</td>
                        <td class="text-center">80.00</td>
                        <td class="text-center">B</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="r-category">C. (Muatan Keahlian)</td>
                    </tr>
                    <tr>
                        <td class="text-center">1</td>
                        <td>Pemrograman Web</td>
                        <td class="text-center">75.00</td>
                        <td class="text-center">80.00</td>
                        <td class="text-center">B</td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>Pemrograman Dasar</td>
                        <td class="text-center">75.00</td>
                        <td class="text-center">80.00</td>
                        <td class="text-center">B</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h4 class="mt-3">B. Ketidakhadiran</h4>
        <div class="table-responsive p-0 card-table mt-4">
            <table class="table table-bordered text-capitalize bg-white w-auto">
                <thead class="bg-muted">
                    <tr>
                        <td class="t-padding">Keterangan</td>
                        <td class="t-padding">Jumlah</td>
                        <td class="t-padding">Rincian</td>
                    </tr>
                </thead>
                <tbody style="border-top: 0;" class="text-center">
                    <tr>
                        <td>Sakit</td>
                        <td>2 hari</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Izin</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Alfa</td>
                        <td>-</td>
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
        <div class="card card-note w-100 bg-muted p-3">
            <p class="text-secondary">Buat cacatan akademik...</p>
        </div>

        <div class="d-flex justify-content-between mt-5 mb-3">
            <div></div>
            <div class="card w-auto p-4 bg-white">
                <p>Wali Kelas</p>
                <button class="btn btn-secondary">Beri Tanda Tangan</button>
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
                    <label class="mb-2">Catatan</label>
                    <textarea class="form-control" rows="4"></textarea>
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
    name: "studentReport",
    components: {
        "modal": modalComponent
    },
    data() {
        return {
            period: {},
            student: {},
            studentGroup: {},
            modalNote: false
        }
    },
    created() {
        this.getPeriod(this.$route.params.period);
        this.getStudentPrevNext(this.$route.params.student);
    },
    watch: {
        '$route.params.student': {
            handler: function() {
                this.getStudentPrevNext(this.$route.params.student);
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
        addNote() {
            console.log('add');
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

@media (max-width: 575px) {
    h5.title {
        font-size: 1rem;
    }
    h4 {
        font-size: 0.9rem;
    }
}
</style>