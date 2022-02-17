<template>
  <div>
    <h4 class="text-capitalize mt-3 mb-4 font-weight-bold">mapel dan tim guru</h4>
    <div class="row">
        <div class="col-md-8">
            <div class="input-group mb-3"> 
                <input type="text" class="form-control input-text shadow-sm bg-white" placeholder="Cari Mapel....">
                <div class="input-group-append"> 
                    <button class="btn btn-outline-muted btn-lg shadow-sm bg-white" type="button"><i class="fa fa-search"></i></button> 
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <a href="#" class="btn btn-primary btn-block mt-1" @click="modalAdd = true">
                <span class="fas fa-plus"></span> Tambah Mapel
            </a>
        </div>
    </div>
    <div class="table-responsive p-0 card-table mt-3">
        <table class="table table-bordered text-capitalize bg-white">
            <thead>
                <tr class="text-center">
                    <th></th>
                    <th>mata pelajaran</th>
                    <th>tim guru</th>
                </tr>
                <tr>
                    <th colspan="3" class="r-category">a. muatan nasional</th>
                </tr>
                <tr>
                    <td class="text-center">:</td>
                    <td>pendidikan agama islam dan budi pekerti</td>
                    <td><a href="#" class="text-primary" @click="modalEdit = true">pilih guru</a></td>
                </tr>
                <tr>
                    <td class="text-center">:</td>
                    <td>pendidikan pancasila dan kewarganegaraan</td>
                    <td>
                        <ul>
                            <li>nama1</li>
                            <li>nama2</li>
                            <li>nama3</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <th colspan="4" class="r-category">b. muatan kewilayahan</th>
                </tr>
                <tr>
                    <th colspan="4" class="r-category">c. muatan peminatan kejuruan</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- modal -->

    <modal v-if="modalAdd" @close="modalAdd = false" :action="addSubject">
        <h5 slot="header">Tambah Mapel</h5>
        <div slot="body">
            <div class="form-group">
                <label class="mb-2">Kelompok Mapel</label>
                <select2 :options="categories" v-model="subject.group"></select2>
            </div>
            <div class="form-group">
                <div class="d-flex flex-column">
                    <label class="mb-1">Nama Mapel</label>
                    <small class="text-capitalize mb-2">pastikan mata pelajaran belum tersedia pada list table mapel</small>
                </div>
                <input type="text" class="form-control" v-model="subject.name">
            </div>
        </div>
    </modal>

    <modal v-if="modalEdit" @close="modalEdit = false" :action="editSubject">
        <h5 slot="header">Edit Mapel</h5>
        <div slot="body">
            <div class="form-group">
                <label class="mb-2">Kelompok Mapel</label>
                <select2 :options="categories"></select2>
            </div>
            <div class="form-group">
                <label class="mb-2">Nama Mapel</label>
                <input type="text" class="form-control">
            </div>
            <div class="form-group">
                <label class="mb-2">Guru Mapel</label>
                <select2 multiple :options="teachers"></select2>
            </div>
        </div>
        <button type="button" class="btn btn-outline-danger" slot="button" @click="showModalDelete">Hapus</button>
    </modal>

    <modal v-if="modalDelete" @close="modalDelete = false" :deleteOpt="deleteSubject">
        <h5 slot="header">Hapus Mapel</h5>
        <div slot="body">
            <p>Yakin akan menghapus mata pelajaran <b class="text-capitalize">bahasa indonesia</b>?</p>
            <p>Semua data nilai mata pelajaran <b class="text-capitalize">bahasa indonesia</b> dalam buku nilai akan terhapus.</p>
        </div>
    </modal>
  </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
// modal
import modalComponent from '../../components/Modal.vue';
// vue-select
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
    name: "subjects",
    components: {
        "modal": modalComponent,
        "select2": vSelect
    },
    data() {
        return {
            modalAdd: false,
            subject: {
                group: null,
                name: null
            },
            categories: [
                'A (Muatan Nasional)',
                'B (Muatan Kewilayahan)',
                'C (Muatan Produktif)',
                'C1 (Dasar Bidang Keahlian)',
                'C2 (Dasar Program Keahlian)',
                'C3 (Dasar Kompetensi Keahlian)'
            ],
            modalEdit: false,
            teachers: [
                'guru1',
                'guru2',
                'guru3'
            ],
            modalDelete: false
        }
    },
    methods: {
        addSubject() {
            console.log(this.subject);
        },
        editSubject() {
            console.log('edit');
        },
        showModalDelete() {
            this.modalEdit = false;
            this.modalDelete = true;
        },
        deleteSubject() {
            console.log('hapus');
        }
    }
}
</script>

<style scoped>
.btn:hover {
    color: #fff;
}

.input-text:focus {
    box-shadow: 0px 0px 0px;
    border-color: #B4ADAD;
    outline: 0px;
}

.form-control {
    border: 1px solid #B4ADAD;
}

.btn-outline-muted {
    color: #535353;
    border-color: #B4ADAD;
    border-radius: 0px !important;
}

.card-table {
    width: 100%;
    display: block;
    margin: auto;
}

.r-category {
    padding-left: 2rem;
}

td, .th-middle {
    vertical-align : middle;
}

.form-group {
    margin-bottom: 10px;
}
</style>