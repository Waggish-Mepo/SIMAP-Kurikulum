<template>
    <div style="margin-top: 70px">
        <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb" class="nav-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'gradebooks.period', params: {page: 6} }"><a href="#">Buku Nilai</a></router-link></li>
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'gradebooks.course', params: {page: 6, period: period.id} }" class="router">Buku Nilai {{period.title}}</router-link></li>
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'gradebooks.course.detail', params: {page: 6, period: period.id, course: course.id, gb: $route.params.gb} }" class="router">Rapor {{period.title}} - {{course.caption}} Kelas {{course.entry_year_with_class}}</router-link></li>
                <li class="breadcrumb-item active" aria-current="page">Rapor Siswa</li>
            </ol>
        </nav>

        <div class="alert alert-danger my-3" v-if="errorMessage">
            {{ errorMessage }}
        </div>

        <h5 class="text-capitalize">rapor siswa (pengetahuan dan keterampilan)</h5>
        <div class="d-flex flex-column mt-3 text-capitalize">
            <b>Keterangan</b>
            <span><b>PH = </b> penilaian harian;  <b class="ml-2">P = </b> pengetahuan; <b class="ml-2">K = </b> keterampilan; <b class="ml-2">teks bercetak merah = </b> remedial </span>
        </div>

        <div class="row mt-4 d-flex justify-content-center">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">RPL XII-2</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="text-capitalize text-center bg-muted">
                                    <tr>
                                        <th rowspan="2" style="vertical-align : middle;">no</th>
                                        <th rowspan="2" style="vertical-align : middle;">nama siswa</th>
                                        <th rowspan="2" style="vertical-align : middle;">NIS</th>
                                        <th rowspan="2" style="vertical-align : middle;">predikat</th>
                                        <th rowspan="2" style="vertical-align : middle;">nilai akhir</th>
                                        <th colspan="2" style="vertical-align : middle;">nilai rapot</th>
                                        <th colspan="2" style="vertical-align : middle;">PH 1</th>
                                        <th colspan="2" style="vertical-align : middle;">PH 2</th>
                                        <th colspan="2" style="vertical-align : middle;">PH 3</th>
                                        <th colspan="2" style="vertical-align : middle;">UTS</th>
                                        <th colspan="2" style="vertical-align : middle;">PAS</th>
                                    </tr>
                                    <tr>
                                        <th>p</th>
                                        <th>k</th>
                                        <th>p</th>
                                        <th>k</th>
                                        <th>p</th>
                                        <th>k</th>
                                        <th>p</th>
                                        <th>k</th>
                                        <th>p</th>
                                        <th>k</th>
                                        <th>p</th>
                                        <th>k</th>
                                    </tr>
                                </thead>
                                <tbody class="text-capitalize" style="border-top: none !important;">
                                    <tr>
                                        <td>1</td>
                                        <td>fema flamelina putri</td>
                                        <td>11907154</td>
                                        <td>A</td>
                                        <td>92</td>
                                        <td>88.00</td>
                                        <td>90.00</td>
                                        <td @click="modalScore= true">100.00</td>
                                        <td>90.00</td>
                                        <td>100.00</td>
                                        <td>90.00</td>
                                        <td>98.00</td>
                                        <td>92.00</td>
                                        <td>90.00</td>
                                        <td>94.00</td>
                                        <td>92.00</td>
                                        <td>92.00</td>
                                    </tr>
                                    <tr class="bg-muted2">
                                        <td>2</td>
                                        <td>levi ackerman
                                            <br>
                                            <span class="text-secondary">siswa ini sudah tidak terdaftar pada mata pelajaran ini</span>
                                        </td>
                                        <td>12345678</td>
                                        <td>A</td>
                                        <td>92</td>
                                        <td>88.00</td>
                                        <td>90.00</td>
                                        <td>100.00</td>
                                        <td>90.00</td>
                                        <td>100.00</td>
                                        <td>90.00</td>
                                        <td>98.00</td>
                                        <td>92.00</td>
                                        <td>90.00</td>
                                        <td>94.00</td>
                                        <td>92.00</td>
                                        <td>92.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal -->
        <modal v-if="modalScore" @close="modalScore = false" :action="updateScore">
            <div slot="header">
                <h5 class="text-capitalize"><b>Fema Flamelina Putri</b></h5>
                <span class="text-capitalize">penilaian harian 1</span>
            </div>
            <div slot="body" class="mt-4">
                <!-- <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div> -->
                <div class="form-group">
                    <label class="mb-2">Nilai Pengetahuan</label>
                    <input type="number" placeholder="Contoh: 90" class="form-control">
                    <!-- :class="{'is-invalid': errors.title}" -->
                    <!-- <div class="invalid-feedback" v-if="errors.title">
                        {{ errors.title[0] }}
                    </div> -->
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
import { mapActions, mapMutations, mapGetters, mapState } from "vuex";
// modal
import modalComponent from '../../../components/Modal.vue';
export default {
    name: "scorecardStudents",
    components: {
        "modal": modalComponent
    },
    data() {
        return {
            period: {},
            course: {},
            modalScore: false
        }
    },
    created() {
        this.getPeriod(this.$route.params.period);
        this.getCourse(this.$route.params.course);
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('reportPeriods', ['detail']),
        ...mapActions('courses', ['show']),

        getPeriod(id) {
            this.detail(id).then((result) => {
                this.period = result;
            })
        },
        getCourse(id) {
            this.show(id).then((result) => {
                this.course = result;
            })
        },
        updateScore() {
            console.log('add');
        }
    }
}
</script>

<style scoped>
.nav-breadcrumb {
    margin-bottom: 20px;
}

.breadcrumb-item {
    font-size: 1rem;
}

h5 {
    font-weight: 600;
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    background-color: #fff;
    border: 1px solid #d2d2dc;
    border-radius: 0;
}

.card .card-title {
    color: #000;
    margin-bottom: 10px;
    text-transform: capitalize;
    font-size: 1rem;
    font-weight: 600;
}

.bg-muted {
    background-color: #f3f3f3;
}

.bg-muted2 {
    background-color: #dadada;
}

.table-bordered {
    border-color: #333 !important;
}
</style>