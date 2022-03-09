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
    <table class="table table-bordered text-center bg-white">
        <thead>
            <tr>
                <th scope="col">Predikat</th>
                <th scope="col">Skor Pembatas</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(pl, index) in predicates" :key="index">
                <th>{{pl.letter}}</th>
                <td>>= {{pl.min_score}}</td>
                <td>
                    <a href="#" class="btn btn-sm btn-white text-dark" @click="showModalEditPredicate(pl.id)"><span class="fas fa-pen predikat"></span></a>
                </td>
            </tr>
            <tr v-if="predicates.length < 1">
                <td colspan="3" class="text-center text-capitalize">data predikat belum tersedia</td>
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
    <div class="d-flex flex-column mb-4 mt-4" v-if="course.curriculum !== 'K21 | Sekolah Penggerak'">
        <b class="text-capitalize">total bobot pengetahuan : {{totalKnowledge}}</b>
        <b class="text-capitalize">total bobot keterampilan : {{totalSkill}}</b>
    </div>
    <div class="card w-100 mb-3" v-for="(gc, index) in components" :key="index">
        <div class="card-title bg-muted p-2 text-center">
            <b class="text-capitalize"><a href="#" class="text-dark" @click="redirectPage">{{gc.title}} ({{gc.abbreviation}})</a></b> <span class="fas fa-pen" style="margin-left: 8px" @click="showComponent(gc.id)"></span>
        </div>
        <div class="card-body" v-if="gc.knowledge_weight">
            <div class="row">
                <div class="col-6 border-right p-col">
                    <p class="text-secondary text-capitalize">pengetahuan</p>
                    <p class="text-black">{{gc.knowledge_weight}}</p>
                </div>
                <div class="col-6">
                    <p class="text-secondary text-capitalize">keterampilan</p>
                    <p class="text-black">{{gc.skill_weight}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card w-100 mb-3 mt-3 p-2" v-if="components.length < 1">
        <p class="text-center">Data penilaian belum tersedia.</p>
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
                    <input type="number" class="form-control" v-model="gradebookData.weights.knowledge" :class="{'is-invalid': errors.weights}">
                    <div class="invalid-feedback" v-if="errors.weights">
                        {{ errors.weights[0] }}
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="mb-2">Bobot Keterampilan</label>
                    <input type="number" class="form-control" v-model="gradebookData.weights.skill" :class="{'is-invalid': errors.weights}">
                    <div class="invalid-feedback" v-if="errors.weights">
                        {{ errors.weights[0] }}
                    </div>
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
                <input type="text" class="form-control" :class="{'is-invalid': errors.letter}" v-model="predicateSubmitForm.letter" placeholder="Contoh : A">
                <div class="invalid-feedback" v-if="errors.letter">
                    {{ errors.letter[0] }}
                </div>
            </div>
            <div class="form-group">
                <div class="d-flex flex-column">
                    <label class="mb-1">Skor Pembatas</label>
                    <small class="mb-2">Predikat tersebut didapatkan apabila nilai lebih sama dengan dari nilai berikut</small>
                </div>
                <input type="number" class="form-control" :class="{'is-invalid': errors.min_score}" v-model="predicateSubmitForm.min_score" placeholder="Contoh: 75">
                <div class="invalid-feedback" v-if="errors.min_score">
                    {{ errors.min_score[0] }}
                </div>
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
                <input type="text" class="form-control" v-model="predicateDetail.letter" :class="{'is-invalid': errors.letter}">
                <div class="invalid-feedback" v-if="errors.letter">
                    {{ errors.letter[0] }}
                </div>
            </div>
            <div class="form-group">
                <div class="d-flex flex-column">
                    <label class="mb-1">Skor Pembatas</label>
                    <small class="mb-2">Predikat tersebut didapatkan apabila nilai lebih sama dengan dari nilai berikut</small>
                </div>
                <input type="number" class="form-control" v-model="predicateDetail.min_score" :class="{'is-invalid': errors.min_score}">
                <div class="invalid-feedback" v-if="errors.min_score">
                    {{ errors.min_score[0] }}
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-outline-danger" slot="button" @click="showModalDeletePredikat">Hapus</button>
    </modal>

    <modal v-if="modalDeletePredikat" @close="modalDeletePredikat = false" :deleteOpt="deletePredikat">
        <h5 slot="header">Hapus Predikat</h5>
        <div slot="body">
            <span>Yakin akan menghapus predikat <b class="text-capitalize">{{predicateDetail.letter}} dengan aturan >= {{predicateDetail.min_score}}</b>? data terkait mungkin saja akan mengalami perubahan</span>
        </div>
    </modal>

    <!-- modal components -->
    <modal v-if="modalAddComponent" @close="modalAddComponent = false" :action="addComponent">
        <h5 slot="header">Tambah Penilaian</h5>
        <div slot="body">
            <div class="alert alert-danger mb-3" v-if="errorMessage">
                {{ errorMessage }}
            </div>
            <div class="alert alert-danger mb-3" v-if="errorInWeight.status">
                {{ errorInWeight.message }}
            </div>
            <div class="row mb-3">
                <div class="form-group col-sm-6">
                    <label class="mb-2">Singkatan Penilaian</label>
                    <input type="text" class="form-control" placeholder="Contoh : PH 1" v-model="componentSubmitForm.abbreviation" :class="{'is-invalid': errors.abbreviation}">
                    <div class="invalid-feedback" v-if="errors.abbreviation">
                        {{ errors.abbreviation[0] }}
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="mb-2">Nama Penilaian</label>
                    <input type="text" class="form-control" placeholder="Contoh : Penilaian Harian 1" v-model="componentSubmitForm.title" :class="{'is-invalid': errors.title}">
                    <div class="invalid-feedback" v-if="errors.title">
                        {{ errors.title[0] }}
                    </div>
                </div>
            </div>
            <div class="row mb-3" v-if="course.curriculum !== 'K21 | Sekolah Penggerak'">
                <div class="form-group col-sm-6">
                    <label class="mb-2">Bobot Pengetahuan</label>
                    <div class="input-group">
                        <button class="btn btn-outline-secondary" @click="downScore('knowledge_weight')">
                            <span class="fas fa-minus"></span>
                        </button>
                        <input type="number" class="form-control" v-model="knowledge_weight" :class="{'is-invalid': errors.knowledge_weight}">
                        <button class="btn btn-outline-secondary" @click="upScore('knowledge_weight')">
                            <span class="fas fa-plus i-input"></span>
                        </button>
                    </div>
                    <div class="invalid-feedback" v-if="errors.knowledge_weight">
                        {{ errors.knowledge_weight[0] }}
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="mb-2">Bobot Keterampilan</label>
                    <div class="input-group">
                        <button class="btn btn-outline-secondary" @click="downScore('skill_weight')">
                            <span class="fas fa-minus"></span>
                        </button>
                        <input type="number" class="form-control" v-model="skill_weight" :class="{'is-invalid': errors.skill_weight}">
                        <button class="btn btn-outline-secondary" @click="upScore('skill_weight')">
                            <span class="fas fa-plus i-input"></span>
                        </button>
                    </div>
                    <div class="invalid-feedback" v-if="errors.skill_weight">
                        {{ errors.skill_weight[0] }}
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
            <div class="alert alert-danger mb-3" v-if="errorInWeight.status">
                {{ errorInWeight.message }}
            </div>
            <div class="row mb-3">
                <div class="form-group col-sm-6">
                    <label class="mb-2">Singkatan Penilaian</label>
                    <input type="text" class="form-control" placeholder="Contoh : PH 1" v-model="componentEditForm.abbreviation">
                </div>
                <div class="form-group col-sm-6">
                    <label class="mb-2">Nama Penilaian</label>
                   <input type="text" class="form-control" placeholder="Contoh : Penilaian Harian 1" v-model="componentEditForm.title">
                </div>
            </div>
            <div class="row mb-3" v-if="course.curriculum !== 'K21 | Sekolah Penggerak'">
                <div class="form-group col-sm-6">
                    <label class="mb-2">Bobot Pengetahuan</label>
                    <div class="input-group">
                        <button class="btn btn-outline-secondary" @click="downScore('knowledge_weight_edit')">
                            <span class="fas fa-minus"></span>
                        </button>
                        <input type="number" class="form-control" v-model="componentEditForm.knowledge_weight">
                        <button class="btn btn-outline-secondary" @click="upScore('knowledge_weight_edit')">
                            <span class="fas fa-plus i-input"></span>
                        </button>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="mb-2">Bobot Keterampilan</label>
                    <div class="input-group">
                        <button class="btn btn-outline-secondary" @click="downScore('skill_weight_edit')">
                            <span class="fas fa-minus"></span>
                        </button>
                        <input type="number" class="form-control" v-model="componentEditForm.skill_weight">
                        <button class="btn btn-outline-secondary" @click="upScore('skill_weight_edit')">
                            <span class="fas fa-plus i-input"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-outline-danger" slot="button" @click="showModalDeleteComponent">Hapus</button>
    </modal>

    <modal v-if="modalDeleteComponent" @close="modalDeleteComponent = false" :deleteOpt="deleteComponent">
        <h5 slot="header">Hapus Penilaian</h5>
        <div slot="body">
            <span>Yakin akan menghapus data <b class="text-capitalize">{{componentEditForm.title}}</b>? semua data yang terkait dengan <b class="text-capitalize">{{componentEditForm.title}}</b> akan ikut terhapus.</span>
        </div>
    </modal>

    <modal v-if="modalRedirect" @close="modalRedirect = false">
        <h5 slot="header">Gagal!</h5>
        <div slot="body">
            <span>Gagal membuka <b>halaman penilaian</b> dikarenakan tidak ditemukannya data <b>rombel</b> pada mata pelajaran <b class="text-capitalize">{{course.caption}} {{course.entry_year_with_class}}</b>. Silahkan atur rombel pada halaman <b>Data Siswa</b></span>
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
            predicates: [],
            predicateDetail: {},
            predicateSubmitForm: {
                letter: null,
                max_score: 0,
                min_score: null,
                gradebook_id: this.$route.params.gb
            },
            components: [],
            totalKnowledge: null,
            totalSkill: null,
            knowledge_weight: 1,
            skill_weight: 1,
            general_weight: 1,
            componentSubmitForm: {
                title: null,
                abbreviation: null,
                knowledge_weight: null,
                skill_weight: null,
                general_weight: null,
                gradebook_id: this.$route.params.gb
            },
            componentEditForm: {},
            errorInWeight: {
                status: false,
                message: null
            },
            modalEditWeights: false,
            modalAddPredikat: false,
            modalEditPredikat: false,
            modalDeletePredikat: false,
            modalAddComponent: false,
            modalEditComponent: false,
            modalDeleteComponent: false,
            modalRedirect: false
        }
    },
    created() {
        this.getGradebook(this.$route.params.gb);
        this.getPeriod(this.$route.params.period);
        this.getCourse(this.$route.params.course);
        this.getPredicateLetter(this.$route.params.gb);
        this.getGradebookComponents(this.$route.params.gb);
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('reportPeriods', ['detail']),
        ...mapActions('courses', ['show']),
        ...mapActions('gradebooks', ['gradebook', 'updateGradebook']),
        ...mapActions('predicateLetters', ['getPredicate', 'predicate', 'createPredicate', 'updatePredicate']),
        ...mapActions('gradebookComponents', ['getComponents', 'component', 'createComponents', 'updateComponent']),
        ...mapActions('studentGroups', ['getByCourse']),

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
        getPredicateLetter(id) {
            this.getPredicate(id).then((result) => {
                this.predicates = result;
            })
        },
        addPredikat() {
            this.createPredicate(this.predicateSubmitForm).then((result) => {
                this.predicateSubmitForm.letter = null;
                this.predicateSubmitForm.min_score = null;
                this.modalAddPredikat = false;
                this.getPredicateLetter(this.$route.params.gb);
            })
        },
        showModalEditPredicate(id) {
            this.predicate(id).then((result) => {
                this.predicateDetail = result;
                this.modalEditPredikat = true;
            })
        },
        editPredikat() {
            let payload = {id: this.predicateDetail.id, data: this.predicateDetail};
            this.updatePredicate(payload).then((result) => {
                this.modalEditPredikat = false;
                this.getPredicateLetter(this.$route.params.gb);
            })
        },
        showModalDeletePredikat() {
            this.modalEditPredikat = false;
            this.modalDeletePredikat = true;
        },
        upScore(model) {
            if(model === "knowledge_weight") {
                if(this.knowledge_weight < 10) {
                    this.knowledge_weight += 1;
                }
            } else if (model === "skill_weight") {
                if(this.skill_weight < 10) {
                    this.skill_weight += 1;
                }
            } else if (model === "knowledge_weight_edit") {
                if (this.componentEditForm.knowledge_weight < 10) {
                    this.componentEditForm.knowledge_weight += 1;
                }
            } else if (model === "skill_weight_edit") {
                if (this.componentEditForm.skill_weight < 10) {
                    this.componentEditForm.skill_weight += 1;
                }
            }
        },
        downScore(model) {
            if(model === "knowledge_weight") {
                if(this.knowledge_weight !== 0) {
                    this.knowledge_weight -= 1;
                }
            } else if (model === "skill_weight") {
                if(this.skill_weight !== 0) {
                    this.skill_weight -= 1;
                }
            } else if (model === "knowledge_weight_edit") {
                if (this.componentEditForm.knowledge_weight !== 0){
                    this.componentEditForm.knowledge_weight -= 1;
                }
            } else if (model === "skill_weight_edit") {
                if (this.componentEditForm.knowledge_weight !== 0) {
                    this.componentEditForm.skill_weight -= 1;
                }
            }
        },
        deletePredikat() {
            console.log('delete');
        },
        getGradebookComponents(id) {
            this.getComponents(id).then((result) => {
                this.components = result;
                if (this.components.length > 0) {
                    this.totalKnowledge = 0;
                    this.totalSkill = 0;
                    for (let i = 0; i < this.components.length; i++) {
                        this.totalKnowledge += this.components[i].knowledge_weight;
                        this.totalSkill += this.components[i].skill_weight;
                    }
                }
            });
        },
        addComponent() {
            if (this.componentSubmitForm.knowledge_weight + this.componentSubmitForm.skill_weight > 10) {
                this.errorInWeight.status = true;
                this.errorInWeight.message = "total bobot pengetahuan dan keterampilan tidak boleh lebih dari 10";
            }
            if (this.course.curriculum !== 'K21 | Sekolah Penggerak') {
                this.componentSubmitForm.knowledge_weight = this.knowledge_weight;
                this.componentSubmitForm.skill_weight = this.skill_weight;
            } else {
                this.componentSubmitForm.general_weight = 1;
            }
            this.createComponents(this.componentSubmitForm).then((result) => {
                this.componentSubmitForm.title = null;
                this.componentSubmitForm.abbreviation = null;
                this.componentSubmitForm.knowledge_weight = null;
                this.knowledge_weight = 1;
                this.componentSubmitForm.skill_weight = null;
                this.skill_weight = 1;
                this.componentSubmitForm.general_weight = null;
                this.modalAddComponent = false;
                this.getGradebookComponents(this.$route.params.gb);
            })
        },
        showComponent(id) {
            this.component(id).then((result) => {
                this.componentEditForm = result;
                this.modalEditComponent = true;
            })
        },
        editComponent() {
            if (this.componentEditForm.knowledge_weight + this.componentEditForm.skill_weight > 10) {
                this.errorInWeight.status = true;
                this.errorInWeight.message = "total bobot pengetahuan dan keterampilan tidak boleh lebih dari 10";
            }
            let payload = {id: this.componentEditForm.id, data: this.componentEditForm};
            this.updateComponent(payload).then((result) => {
                this.modalEditComponent = false;
                this.getGradebookComponents(this.$route.params.gb);
            })
        },
        showModalDeleteComponent() {
            this.modalEditComponent = false;
            this.modalDeleteComponent = true;
        },
        deleteComponent() {
            console.log('delete');
        },
        redirectPage() {
            this.getByCourse(this.course.id).then((result) => {
                if(result.length < 1) {
                    this.modalRedirect = true;
                } else {
                    this.$router.push({ name: 'gradebooks.course.detail.group', params: {period: this.period.id, course: this.course.id, gb: this.$route.params.gb, sg: result[0].id} });
                }
            });
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

@media (max-width: 575px) {
    h5 {
        font-size: 0.9rem !important;
    }
}
</style>