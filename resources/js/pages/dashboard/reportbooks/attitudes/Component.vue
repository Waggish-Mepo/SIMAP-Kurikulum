<template>
    <div class="mt-2">
        <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'reportbooks.periods', params: {page: 7} }"><a href="#">Periode Rapor</a></router-link></li>
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'reportbooks.periods.students', params: {page: 7, period: period.id} }" class="router"><a href="#">{{period.title + ' - ' + period.school_year}}</a></router-link></li>
                <li class="breadcrumb-item active" aria-current="page">Komponen Nilai Sikap</li>
            </ol>
        </nav>

        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>
        <h5 class="mb-3 mt-5 title">Komponen Nilai Rapor Sikap</h5>
        <a href="#" class="btn bg-blue1 text-white mb-4" @click="modalAdd = true">Tambah Komponen Nilai Sikap</a>

        <div class="table-responsive p-0 card-table mt-4" v-for="(attitude, index) in attitudes" :key="index">
            <h5 class="text-capitalize mb-3" v-if="index == 'CHARACTER'">Nilai Karakter</h5>
            <h5 class="text-capitalize mb-3" v-else-if="index == 'COMPETENCE'">Nilai Kompetensi</h5>
            <h5 class="mb-3" v-else>Nilai {{index[0].toUpperCase() + index.slice(1).toLowerCase()}}</h5>
            <table class="table table-bordered text-capitalize bg-white" style="max-width: 1150px" v-if="attitude.length > 0">
                <thead class="bg-muted text-center">
                    <th class="th-comp up-down">#</th>
                    <th class="th-comp">No.</th>
                    <th class="th-comp">Nama</th>
                    <th class="th-comp">Predikat</th>
                    <th class="th-comp">Deskripsi</th>
                </thead>
                <tbody style="border-top: 0;" v-for="(data, index) in attitude" :key="index">
                    <tr>
                        <td class="text-center" v-if="data.attitude_predicates.length < 1" style="width: 80px !important">
                            <a href="#" class="text-dark" v-if="data.order > 1" @click="orderUp(data.id)"><i class="fas fa-arrow-up"></i></a>
                            <a href="#" class="text-dark" v-if="data.order < attitude.length" @click="orderDown(data.id)"><i class="fas fa-arrow-down" style="margin-left: 8px !important"></i></a>
                        </td>
                        <td class="text-center" v-else :rowspan="data.attitude_predicates.length+1" style="vertical-align : middle; width: 80px !important">
                            <a href="#" class="text-dark" v-if="data.order > 1" @click="orderUp(data.id)"><i class="fas fa-arrow-up"></i></a>
                            <a href="#" class="text-dark" v-if="data.order < attitude.length" @click="orderDown(data.id)"><i class="fas fa-arrow-down" style="margin-left: 8px !important"></i></a>
                        </td>
                        <td class="text-center" v-if="data.attitude_predicates.length < 1">{{data.order}}.</td>
                        <td class="text-center" v-else :rowspan="data.attitude_predicates.length+1" style="vertical-align : middle;">{{data.order}}.</td>
                        <td class="text-center" v-if="data.attitude_predicates.length < 1"><a href="#" @click="showModalEdit(data.id)" class="text-dark">{{data.name}}</a></td>
                        <td class="text-center" v-else :rowspan="data.attitude_predicates.length+1" style="vertical-align : middle;"><a href="#" @click="showModalEdit(data.id)" class="text-dark">{{data.name}}</a></td>
                        <td class="text-center" colspan="2"><a href="#" @click="showModalAddAP(data.id)">Tambah Predikat</a></td>
                    </tr>
                    <tr v-for="(predicate, index) in data.attitude_predicates" :key="index">
                        <td class="text-center"><a href="#" @click="showModalEditAP(predicate.id)" class="text-dark">{{predicate.predicate}}</a></td>
                        <td class="text-truncate" style="max-width: 150px"><a href="#" @click="showModalEditAP(predicate.id)" class="text-dark">{{predicate.description}}</a></td>
                    </tr>
                </tbody>
            </table>
            <!-- data null -->
            <div v-else class="w-100 card-not-found">
                <img src="/assets/img/sad.png" alt="not found" class="d-block img m-auto" style="width: 80px !important">
                <h5 class="text-center text-capitalize mt-4">data terkait tidak ditemukan</h5>
            </div>
        </div>

        <!-- modal -->
        <modal v-if="modalAdd" @close="modalAdd = false" :action="addComponent">
            <h5 slot="header">Tambah Komponen Nilai Sikap</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Nama Komponen</label>
                    <input type="text" class="form-control" v-model="componentPayload.name" placeholder="Religius">
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Tipe Penilaian</label>
                    <select class="form-select" v-model="componentPayload.type">
                        <option value="CHARACTER">Karakter</option>
                        <option value="COMPETENCE">Kompetensi</option>
                        <option value="PANCASILA">Pancasila</option>
                    </select>
                </div>
            </div>
        </modal>

        <modal v-if="modalEdit" @close="modalEdit = false" :action="editComponent">
            <h5 slot="header">Edit Komponen Nilai Sikap</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Nama Komponen</label>
                    <input type="text" class="form-control" v-model="componentEditPayload.name" placeholder="Religius">
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Tipe Penilaian</label>
                    <select class="form-select" v-model="componentEditPayload.type">
                        <option value="CHARACTER">Karakter</option>
                        <option value="COMPETENCE">Kompetensi</option>
                        <option value="PANCASILA">Pancasila</option>
                    </select>
                </div>
            </div>
            <button type="button" class="btn btn-outline-danger" slot="button" @click="showModalDelete">Hapus</button>
        </modal>

        <modal v-if="modalDelete" @close="modalDelete = false" :deleteOpt="deleteComponent">
            <h5 slot="header">Hapus Komponen Nilai Sikap</h5>
            <div slot="body">
                <span><b>Semua data</b> yang berkaitan dengan komponen nilai <b class="text-capitalize">{{componentEditPayload.name}}</b> juga akan <b>terhapus</b> dan <b>tidak dapat diakses kembali</b>. Yakin tetap menghapus?</span>
            </div>
        </modal>

        <modal v-if="modalAddAP" @close="modalAddAP = false" :action="addAttitudePredicate">
            <h5 slot="header">Tambah Predikat Pada Komponen Nilai <b>{{selectedComponent}}</b></h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Predikat</label>
                    <input type="text" class="form-control" v-model="predicatePayload.predicate" placeholder="Baik">
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Deskripsi Predikat</label>
                    <textarea class="form-control" v-model="predicatePayload.description" rows="3"></textarea>
                </div>
            </div>
        </modal>

        <modal v-if="modalEditAP" @close="modalEditAP = false" :action="editAttitudePredicate">
            <h5 slot="header">Edit Predikat Pada Komponen Nilai <b>{{selectedComponent}}</b></h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Predikat</label>
                    <input type="text" class="form-control" v-model="predicateEditPayload.predicate" placeholder="Baik">
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Deskripsi Predikat</label>
                    <textarea class="form-control" v-model="predicateEditPayload.description" rows="3"></textarea>
                </div>
            </div>
            <button type="button" class="btn btn-outline-danger" slot="button" @click="showModalDeleteAP">Hapus</button>
        </modal>

        <modal v-if="modalDeleteAP" @close="modalDeleteAP = false" :deleteOpt="deleteAttitudePredicate">
            <h5 slot="header">Hapus Predikat Pada Komponen Nilai <b>{{selectedComponent}}</b></h5>
            <div slot="body">
                <span><b>Semua data</b> yang berkaitan dengan predikat <b>{{predicateEditPayload.predicate}}</b> pada komponen nilai <b class="text-capitalize">{{selectedComponent}}</b> juga akan <b>terhapus</b> dan <b>tidak dapat diakses kembali</b>. Yakin tetap menghapus?</span>
            </div>
        </modal>
    </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
// modal
import modalComponent from '../../../../components/Modal.vue';
export default {
    name: "attitudeComponents",
    data() {
        return {
            period: {},
            modalAdd: false,
            modalEdit: false,
            modalDelete: false,
            modalAddAP: false,
            modalEditAP: false,
            modalDeleteAP: false,
            modalOrder: false,
            types: [],
            attitudes: [],
            no: 1,
            componentPayload: {
                name: '',
                type: '',
                report_period_id: this.$route.params.period
            },
            componentEditPayload: {},
            selectedComponent: null,
            predicatePayload: {
                predicate: '',
                description: '',
                attitude_id: null
            },
            predicateEditPayload: {},
        }
    },
    components: {
        "modal": modalComponent
    },
    created() {
        this.getPeriod(this.$route.params.period);
        this.getAttitudeTypes();
        this.getAttitudes();
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('reportPeriods', ['detail']),
        ...mapActions('attitudes', ['index', 'attitudeTypes', 'show', 'create', 'edit', 'editOrder', 'deleteAttitudeCascade']),
        ...mapActions('attitudePredicates', ['showAP', 'createAP', 'editAP', 'deleteAttitudePredicateCascade']),

        getPeriod(id) {
            this.detail(id).then((result) => {
                this.period = result;
            })
        },
        getAttitudeTypes() {
            this.attitudeTypes().then((result) => {
                this.types = result;
            })
        },
        getAttitudes() {
            let payload = {report_period_id: this.$route.params.period};
            this.index(payload).then((result) => {
                this.attitudes = result;
            })
        },
        addComponent() {
            this.create(this.componentPayload).then((result) => {
                this.componentPayload.name = '';
                this.componentPayload.type = '';
                this.modalAdd = false;
                this.getAttitudes();
            })
        },
        showModalEdit(id) {
            this.show(id).then((result) => {
                this.componentEditPayload = result;
                this.modalEdit = true;
            });
        },
        editComponent() {
            let payload = {id: this.componentEditPayload.id, data: this.componentEditPayload};
            this.edit(payload).then((result) => {
                this.modalEdit = false;
                this.getAttitudes();
            })
        },
        orderUp(id) {
            let payload = {id: id, data: {typeOrder: 'UP'}};
            this.editOrder(payload).then((result) => {
                this.getAttitudes();
            })
        },
        orderDown(id) {
            let payload = {id: id, data: {typeOrder: 'DOWN'}};
            this.editOrder(payload).then((result) => {
                this.getAttitudes();
            })
        },
        showModalDelete() {
            this.modalEdit = false;
            this.modalDelete = true;
        },
        deleteComponent() {
            let payload = {id: this.componentEditPayload.id, periodId: this.$route.params.period};

            this.deleteAttitudeCascade(payload).then((result) => {
                this.modalDelete = false;
                this.getAttitudes();
            })
        },
        showModalAddAP(id) {
            this.predicatePayload.attitude_id = id;
            this.show(id).then((result) => {
                this.selectedComponent = result.name;
                this.modalAddAP = true;
                this.getAttitudes();
            })
        },
        addAttitudePredicate() {
            this.createAP(this.predicatePayload).then((result) => {
                this.predicatePayload.predicate = '';
                this.predicatePayload.description = '';
                this.predicatePayload.attitude_id = null;
                this.modalAddAP = false;
                this.getAttitudes();
            })
        },
        showModalEditAP(id) {
            this.showAP(id).then((result) => {
                this.predicateEditPayload = result;
                this.show(result.attitude_id).then((result) => {
                    this.selectedComponent = result.name;
                    this.modalEditAP = true;
                });
            })
        },
        editAttitudePredicate() {
            let payload = {id: this.predicateEditPayload.id, data: this.predicateEditPayload};
            this.editAP(payload).then((result) => {
                this.modalEditAP = false;
                this.getAttitudes();
            })
        },
        showModalDeleteAP() {
            this.modalEditAP = false;
            this.modalDeleteAP = true;
        },
        deleteAttitudePredicate() {
            let payload = {id: this.predicateEditPayload.id, periodId: this.$route.params.period};

            this.deleteAttitudePredicateCascade(payload).then((result) => {
                this.modalDeleteAP = false;
                this.getAttitudes();
            })
        }
    }
}
</script>

<style scoped>
td {
    cursor: pointer;
}

th.th-comp {
    border-right-width: 1px !important;
    padding: 8px 0 !important
}

th.th-comp.up-down {
    border-left-width: 1px !important;
    width: 80px !important
}
</style>
