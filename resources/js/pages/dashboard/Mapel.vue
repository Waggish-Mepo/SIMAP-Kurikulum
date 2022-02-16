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
                    <th rowspan="2"></th>
                    <th rowspan="2" class="th-middle">mata pelajaran</th>
                    <th colspan="2">tim guru</th>
                </tr>
                <tr class="text-center">
                    <th>Koordinator</th>
                    <th>guru mapel</th>
                </tr>
                <tr>
                    <th colspan="4" class="r-category">a. muatan nasional</th>
                </tr>
                <tr>
                    <td class="text-center">:</td>
                    <td>pendidikan agama islam dan budi pekerti</td>
                    <td><a href="#" class="text-primary">pilih koordinator</a></td>
                    <td><a href="#" class="text-primary">pilih guru</a></td>
                </tr>
                <tr>
                    <td class="text-center">:</td>
                    <td>pendidikan pancasila dan kewarganegaraan</td>
                    <td>guru</td>
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
                <label>Kategori</label>
                <input type="text" class="form-control">
            </div>
        </div>
    </modal>
  </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';

// modal
import modalComponent from '../../components/Modal.vue';
export default {
    name: "subjects",
    components: {
        "modal": modalComponent
    },
    data() {
        return {
            modalAdd: false
        }
    },
    methods: {
        addSubject() {
            console.log('add subject');
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
</style>