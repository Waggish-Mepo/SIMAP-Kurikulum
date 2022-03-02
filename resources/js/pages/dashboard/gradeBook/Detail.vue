<template>
<div class="mt-3">
    <div class="loader" v-if="isLoading"></div>
    <nav aria-label="breadcrumb" class="nav-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'gradebooks.period', params: {page: 6} }"><a href="#">Buku Nilai</a></router-link></li>
            <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'gradebooks.course', params: {page: 6, period: period.id} }" class="router">Buku Nilai {{period.title}}</router-link></li>
            <li class="breadcrumb-item active" aria-current="page">Rapor {{period.title}} - {{course.caption}} Kelas {{course.entry_year_with_class}}</li>
        </ol>
    </nav>

    <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
    </div>
    <div class="d-flex justify-content-between flex-wrap mb-3">
        <div>
            <h5 class="text-capitalize">pengaturan dan komponen nilai pada rapor</h5>
            <p class="text-secondary">Bobot nilai dibawah akan menjadi acuan dalam perhitungan pada nilai akhir rapor</p>
        </div>
        <div>
            <a href="#" class="btn btn-white text-primary btn-lg" @click="modalEditWeights = true"><span class="fas fa-pen"></span>Edit</a>
        </div>
    </div>
    <div class="row top-section mt-3" v-if="course.curriculum !== 'K21 | Sekolah Penggerak'">
        <div class="col-4 p-2 border">
            <p class="text-secondary text-capitalize">bobot nilai pengetahuan</p>
            <h5 class="text-center" v-if="gradebookData.weights">{{gradebookData.weights.knowledge}}%</h5>
        </div>
        <div class="col-4 p-2 border">
            <p class="text-secondary text-capitalize">bobot nilai keterampilan</p>
            <h5 class="text-center" v-if="gradebookData.weights">{{gradebookData.weights.skill}}%</h5>
        </div>
        <div class="col-4 p-2 border">
            <p class="text-secondary text-capitalize">skor ketuntasan minimal</p>
            <h5 class="text-center">{{gradebookData.scorebar}}</h5>
        </div>
    </div>

    <div class="row top-section mt-3" v-if="course.curriculum === 'K21 | Sekolah Penggerak'">
        <div class="col-6 p-2 border">
            <p class="text-secondary text-capitalize">bobot nilai umum</p>
            <h5 class="text-center">100%</h5>
        </div>
        <div class="col-6 p-2 border">
            <p class="text-secondary text-capitalize">skor ketuntasan minimal</p>
            <h5 class="text-center">{{gradebookData.scorebar}}</h5>
        </div>
    </div>

    <h5 class="text-capitalize mt-5">pengaturan predikat nilai pada rapor</h5>
    <p class="text-secondary">prediket pada nilai siswa/i akan otomatis terbuat sesuai aturan dibawah ini</p>
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th scope="col">Predikat</th>
                <th scope="col">Skor Pembatas</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>A</th>
                <td>>= 84</td>
                <td>
                    <a href="#" class="btn btn-sm btn-white text-dark" @click="modalEditPredikat = true"><span class="fas fa-pen predikat"></span></a>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <a href="#" class="btn btn-sm btn white text-primary" @click="modalAddPredikat = true"><span class="fas fa-plus"></span>tambah predikat</a>
                </td>
            </tr>
        </tbody>
    </table>

    <h5 class="text-capitalize mt-5">komponen penilaian</h5>
    <p class="text-secondary">Komponen nilai otomatis menambahkan siswa/i yang terdaftar di pelajaran <span class="text-capitalize">{{course.caption}} {{course.entry_year_with_class}}</span></p>
    <b class="text-capitalize mt-4">total bobot pengetahuan :</b>
    <b class="text-capitalize">total bobot keterampilan :</b>
    <div class="card w-100 mb-3">
        <div class="card-title bg-muted p-2 text-center">
            <b class="text-capitalize">penilaian harian 1 (PH 1)</b> <span class="fas fa-pen" style="margin-left: 8px" @click="modalEditComponent = true"></span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6 border-right p-col">
                    <p class="text-secondary text-capitalize">bobot pengetahuan</p>
                    <p class="text-black">5%</p>
                </div>
                <div class="col-6">
                    <p class="text-secondary text-capitalize">bobot keterampilan</p>
                    <p class="text-black">5%</p>
                </div>
            </div>
        </div>
    </div>
    <a href="#" class="btn btn-white text-primary" @click="modalAddComponent = true"><span class="fas fa-plus"></span>Tambah Komponen Penilaian</a>

    <!-- modal weights -->
    <modal v-if="modalEditWeights" @close="modalEditWeights = false" :action="editWeights">
        <h5 slot="header">Edit Pengaturan Bobot dan KKM</h5>
        <div slot="body">
            <div class="alert alert-danger mb-3" v-if="errorMessage">
                {{ errorMessage }}
            </div>
            <div class="row mb-3" v-if="course.curriculum !== 'K21 | Sekolah Penggerak'">
                <b class="mb-4">Total bobot nilai pengetahuan dan keterampilan harus 100%</b>
                <div class="form-group col-sm-6">
                    <label class="mb-2">Bobot Pengetahuan</label>
                    <input type="number" class="form-control" v-model="gradebookData.weights.knowledge">
                </div>
                <div class="form-group col-sm-6">
                    <label class="mb-2">Bobot Keterampilan</label>
                    <input type="number" class="form-control" v-model="gradebookData.weights.skill">
                </div>
            </div>
            <div class="form-group">
                <label class="mb-2">Skor Ketuntasan Minimal</label>
                <input type="number" class="form-control" v-model="gradebookData.scorebar" :class="{'is-invalid': errors.scorebar}">
                <div class="invalid-feedback" v-if="errors.scorebar">
                    {{ errors.scorebar[0] }}
                </div>
            </div>
        </div>
    </modal>

    <!-- modal predikat -->

    <modal v-if="modalAddPredikat" @close="modalAddPredikat = false" :action="addPredikat">
        <h5 slot="header">Tambah Predikat</h5>
        <div slot="body">
            <div class="alert alert-danger mb-3" v-if="errorMessage">
                {{ errorMessage }}
            </div>
            <div class="form-group mb-3">
                <label class="mb-2">Predikat</label>
                <input type="text" class="form-control">
                <!-- <div class="invalid-feedback" v-if="errors.scorebar">
                    {{ errors.scorebar[0] }}
                </div> -->
            </div>
            <div class="form-group">
                <div class="d-flex flex-column">
                    <label class="mb-1">Skor Pembatas</label>
                    <small class="mb-2">Predikat tersebut didapatkan apabila nilai lebih sama dengan dari nilai berikut</small>
                </div>
                <input type="number" class="form-control">
                <!-- <div class="invalid-feedback" v-if="errors.scorebar">
                    {{ errors.scorebar[0] }}
                </div> -->
            </div>
        </div>
    </modal>

    <modal v-if="modalEditPredikat" @close="modalEditPredikat = false" :action="editPredikat">
        <h5 slot="header">Edit Predikat</h5>
        <div slot="body">
            <div class="alert alert-danger mb-3" v-if="errorMessage">
                {{ errorMessage }}
            </div>
            <div class="form-group mb-3">
                <label class="mb-2">Predikat</label>
                <input type="text" class="form-control">
                <!-- <div class="invalid-feedback" v-if="errors.scorebar">
                    {{ errors.scorebar[0] }}
                </div> -->
            </div>
            <div class="form-group">
                <div class="d-flex flex-column">
                    <label class="mb-1">Skor Pembatas</label>
                    <small class="mb-2">Predikat tersebut didapatkan apabila nilai lebih sama dengan dari nilai berikut</small>
                </div>
                <input type="number" class="form-control">
                <!-- <div class="invalid-feedback" v-if="errors.scorebar">
                    {{ errors.scorebar[0] }}
                </div> -->
            </div>
        </div>
        <button type="button" class="btn btn-outline-danger" slot="button" @click="showModalDeletePredikat">Hapus</button>
    </modal>

    <modal v-if="modalDeletePredikat" @close="modalDeletePredikat = false" :deleteOpt="deletePredikat">
        <h5 slot="header">Hapus Predikat</h5>
        <div slot="body">
            <span>Yakin akan menghapus predikat <b class="text-capitalize">test</b>? data terkait mungkin saja akan mengalami perubahan</span>
        </div>
    </modal>

    <!-- modal components -->
    <modal v-if="modalAddComponent" @close="modalAddComponent = false" :action="addComponent">
        <h5 slot="header">Tambah Penilaian</h5>
        <div slot="body">
            <div class="alert alert-danger mb-3" v-if="errorMessage">
                {{ errorMessage }}
            </div>
            <div class="row mb-3">
                <b class="mb-4">Total bobot nilai pengetahuan dan keterampilan harus 100%</b>
                <div class="form-group col-sm-6">
                    <label class="mb-2">Singkatan Penilaian</label>
                    <input type="text" class="form-control" placeholder="Contoh : PH 1">
                </div>
                <div class="form-group col-sm-6">
                    <label class="mb-2">Nama Penilaian</label>
                   <input type="text" class="form-control" placeholder="Contoh : Penilaian Harian 1">
                </div>
            </div>
            <div class="row mb-3">
                <div class="form-group col-sm-6">
                    <label class="mb-2">Bobot Pengetahuan</label>
                    <div class="input-group">
                        <button class="btn btn-outline-secondary" disabled="disabled">
                            <span class="fas fa-minus"></span>
                        </button>
                        <input type="number" class="form-control" value="1" min="1" max="100">
                        <button class="btn btn-outline-secondary">
                            <span class="fas fa-plus i-input"></span>
                        </button>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="mb-2">Bobot Keterampilan</label>
                    <div class="input-group">
                        <button class="btn btn-outline-secondary" disabled="disabled">
                            <span class="fas fa-minus"></span>
                        </button>
                        <input type="number" class="form-control" value="1" min="1" max="100">
                        <button class="btn btn-outline-secondary">
                            <span class="fas fa-plus i-input"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </modal>

    <modal v-if="modalEditComponent" @close="modalEditComponent = false" :action="editComponent">
        <h5 slot="header">Edit Penilaian</h5>
        <div slot="body">
            <div class="alert alert-danger mb-3" v-if="errorMessage">
                {{ errorMessage }}
            </div>
            <div class="row mb-3">
                <b class="mb-4">Total bobot nilai pengetahuan dan keterampilan harus 100%</b>
                <div class="form-group col-sm-6">
                    <label class="mb-2">Singkatan Penilaian</label>
                    <input type="text" class="form-control" placeholder="Contoh : PH 1">
                </div>
                <div class="form-group col-sm-6">
                    <label class="mb-2">Nama Penilaian</label>
                   <input type="text" class="form-control" placeholder="Contoh : Penilaian Harian 1">
                </div>
            </div>
            <div class="row mb-3">
                <div class="form-group col-sm-6">
                    <label class="mb-2">Bobot Pengetahuan</label>
                    <div class="input-group">
                        <button class="btn btn-outline-secondary" disabled="disabled">
                            <span class="fas fa-minus"></span>
                        </button>
                        <input type="number" class="form-control" value="1" min="1" max="100">
                        <button class="btn btn-outline-secondary">
                            <span class="fas fa-plus i-input"></span>
                        </button>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="mb-2">Bobot Keterampilan</label>
                    <div class="input-group">
                        <button class="btn btn-outline-secondary" disabled="disabled">
                            <span class="fas fa-minus"></span>
                        </button>
                        <input type="number" class="form-control" value="1" min="1" max="100">
                        <button class="btn btn-outline-secondary">
                            <span class="fas fa-plus i-input"></span>
                        </button>
                    </div>
                </div>
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
    name: "gradeBookDetail",
    components: {
        "modal": modalComponent
    },
    data() {
        return {
            period: {},
            course: {},
            gradebookData: {},
            modalEditWeights: false,
            modalAddPredikat: false,
            modalEditPredikat: false,
            modalDeletePredikat: false,
            modalAddComponent: false,
            modalEditComponent: false
        }
    },
    created() {
        this.getGradebook(this.$route.params.gb);
        this.getPeriod(this.$route.params.period);
        this.getCourse(this.$route.params.course);
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('reportPeriods', ['detail']),
        ...mapActions('courses', ['show']),
        ...mapActions('gradebooks', ['gradebook', 'updateGradebook']),

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
        getGradebook(id) {
            this.gradebook(id).then((result) => {
                this.gradebookData = result;
                this.gradebookData.scorebar = parseInt(result.scorebar).toFixed();
                this.gradebookData.weights.knowledge = result.weights.knowledge*100;
                this.gradebookData.weights.skill = result.weights.skill*100;
            })
        },
        editWeights() {
            let payload = {id: this.gradebookData.id, data: this.gradebookData};
            this.updateGradebook(payload).then((result) => {
                this.getGradebook(result.id);
                this.modalEditWeights = false;
            })
        },
        addPredikat() {
            console.log('add');
        },
        editPredikat() {
            console.log('edit');
        },
        showModalDeletePredikat() {
            this.modalEditPredikat = false;
            this.modalDeletePredikat = true;
        },
        deletePredikat() {
            console.log('delete');
        },
        addComponent() {
            console.log('add');
        },
        editcomponent() {
            console.log('edit');
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

.row.top-section {
    margin-left: 4px !important;
    margin-right: 4px !important;
}

.border {
    border-color: #333 !important;
}

p {
    margin-bottom: 0.3rem !important;
}

.fas.fa-pen,
.fas.fa-plus {
    margin-right: 8px;
    cursor: pointer;
}

.fas.fa-plus.i-input {
    margin-right: 0;
}

tbody {
    border-top: none !important;
}

table {
    max-width: 500px;
}

.btn.btn-sm {
    padding: 0 !important;
}

.bg-muted {
    background-color: #dadada;
}

.card-title {
    margin-bottom: 0 !important;
}

.card-body {
    padding: 0 !important;
}

.border-right {
    border-right: 1px solid #dadada;
}

.p-col {
    padding-left: 25px !important;
}
</style>