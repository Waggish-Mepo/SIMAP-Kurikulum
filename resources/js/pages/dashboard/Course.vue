<template>
    <div class="mt-3">
        <div class="row">
            <div class="col-md-8">
                <div class="input-group mb-3">
                    <input type="text" class="form-control input-text shadow-sm bg-white" placeholder="Cari Pelajaran....">
                    <div class="input-group-append">
                        <a href="#" class="btn btn-outline-muted btn-lg shadow-sm bg-white"><i class="fa fa-search"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <a href="#" class="btn btn-secondary btn-block mt-md-1">Refresh Data</a>
            </div>
        </div>
        <div class="card w-100 bg-white p-2 mt-3" @click="modalAdd = true">
            <a href="#"><span class="fas fa-plus mr-3"></span> Tambah Mata Pelajaran</a>
        </div>
        <div class="col-12 mt-3">
            <div class="panel-group shadow-sm mb-2" id="subject-panel">
                <div class="panel panel-default">
                    <div class="panel-heading" @click="showPanelCollapse('subject1', 'title1')">
                        <h4 class="panel-title"> 
                            <a data-toggle="collapse" data-parent="#subject-panel" href="#subject1" aria-expanded="false" class="collapsed" id="title1"> <span class="fas fa-book"></span> Tentang Kami </a>
                        </h4>
                    </div>
                    <div id="subject1" class="panel-collapse collapse" aria-expanded="false" role="tablist" style="height: 0px;">
                        <div class="panel-body">
                            <ul class="list-group">
                                <li class="list-group-item" @click="modalEdit = true"><a href="#">Kelas 10 | RPL</a></li>
                                <li class="list-group-item"><a href="#">Kelas 10 | MMD</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal -->
        <modal v-if="modalAdd" @close="modalAdd = false" :action="addCourse">
            <h5 slot="header">Tambah Pelajaran</h5>
            <div slot="body">
                <!-- <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div> -->
                <div class="form-group">
                    <label class="mb-2">Mata Pelajaran</label>
                    <select2 :options="subjects"></select2>
                    <!-- <div class="invalid-feedback" v-if="errors.title">
                        {{ errors.title[0] }}
                    </div> -->
                </div>
                <div class="form-group">
                    <label class="mb-2">Kelas/Angkatan Masuk</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="option1" checked>
                        <label class="form-check-label text-capitalize">
                            kelas 10 | angkatan masuk 2021
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="option2">
                        <label class="form-check-label text-capitalize">
                            Kelas 11 | angkatan masuk 2020
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="option3">
                        <label class="form-check-label text-capitalize">
                            Kelas 12 | angkatan masuk 2019
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-2">Keterangan</label>
                    <input type="text" class="form-control" placeholder="contoh : matematika IT">
                </div>
                <div class="form-group">
                    <label class="mb-2">Kurikulum Acuan</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="option1" checked>
                        <label class="form-check-label text-capitalize">
                            k13
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="option2">
                        <label class="form-check-label text-capitalize">
                            k21
                        </label>
                    </div>
                </div>
            </div>
        </modal>

        <modal v-if="modalEdit" @close="modalEdit = false" :action="editCourse">
            <h5 slot="header">Edit Pelajaran</h5>
            <div slot="body">
                <!-- <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div> -->
                <div class="form-group">
                    <label class="mb-2">Mata Pelajaran</label>
                    <select2 :options="subjects"></select2>
                    <!-- <div class="invalid-feedback" v-if="errors.title">
                        {{ errors.title[0] }}
                    </div> -->
                </div>
                <div class="form-group">
                    <label class="mb-2">Kelas/Angkatan Masuk</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="option1" checked>
                        <label class="form-check-label text-capitalize">
                            kelas 10 | angkatan masuk 2021
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="option2">
                        <label class="form-check-label text-capitalize">
                            Kelas 11 | angkatan masuk 2020
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="option3">
                        <label class="form-check-label text-capitalize">
                            Kelas 12 | angkatan masuk 2019
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-2">Keterangan</label>
                    <input type="text" class="form-control" placeholder="contoh : matematika IT">
                </div>
                <div class="form-group">
                    <label class="mb-2">Kurikulum Acuan</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="option1" checked>
                        <label class="form-check-label text-capitalize">
                            k13
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="option2">
                        <label class="form-check-label text-capitalize">
                            k21
                        </label>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-danger" slot="button">Hapus</button>
        </modal>
    </div>
</template>

<script>
// modal
import modalComponent from '../../components/Modal.vue';
// vue-select
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
    name: "Courses",
    components: {
        "modal": modalComponent,
        "select2": vSelect
    },
    data() {
        return {
            modalAdd: false,
            modalEdit: false,
            subjects: [
                'Bahasa Indonesia',
                'Bahasa Sunda'
            ]
        }
    },
    methods: {
        showPanelCollapse(PBody, PTitle) {
            let panelBody = document.getElementById(PBody);
            panelBody.classList.toggle('show');
            let iconTitle = document.getElementById(PTitle);
            iconTitle.classList.toggle('collapsed');
        },
        addCourse() {
            console.log('add');
        },
        editCourse() {
            console.log('edit');
        }
    }
}
</script>

<style scoped>
a:hover {
    text-decoration: none;
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

.card {
    border: 0 !important;
}

.card a {
    font-weight: 500;
    padding: 8px 10px;
    font-size: 16px;
}

.panel-group {
    margin-bottom: 0;
}

.panel {
    background-color: transparent;
    box-shadow: none;
    border-bottom: 10px solid transparent;
    border-radius: 0;
    margin: 0;
}

.panel-default {
    border: 0;
}

.accordion-wrap .panel-heading {
    padding: 0px;
    border-radius: 0px;
    cursor: pointer;
}

.panel-title {
    margin-top: 0;
    margin-bottom: 0;
    font-size: 16px;
    font-weight: 500;
    color: inherit;
}

.panel .panel-heading a.collapsed,
.panel .panel-heading a {
    color: #000;
    background-color: #fff;
    display: block;
    padding: 16px 20px;
}

span.fas.fa-book,
span.fas.fa-plus {
    margin-right: 10px;
}

span.fas.fa-book {
    color: #333;
}

.panel-body {
    border-top: 0;
    padding: 0;
    background: #dadada;
    color: #333;
    font-size: 14px;
}

.list-group {
    border-radius: 0 !important;
}

.list-group-item {
    border-left: 0 !important;
    border-right: 0 !important;
    background-color: #eaeaea !important;
    padding-left: 3rem;
}

.list-group-item a {
    color: #333;
}

.list-group-item a:hover {
    text-decoration: underline;
}

.panel .panel-heading a:after {
    content: "\21A5";
}

.panel .panel-heading a:after,
.panel .panel-heading a.collapsed:after {
    float: right;
    width: 21px;
    display: block;
    height: 21px;
    line-height: 21px;
    text-align: center;
    color: #333;
    background: transparent;
    font-weight: 700;
}

.panel .panel-heading a.collapsed:after {
    content: "\21A7";
}

.form-group {
    margin-bottom: 15px;
}

label.mb-2 {
    font-weight: 600;
}

@media (max-width: 470px) {
    .input-text,
    .card a,
    .panel-title {
        font-size: 0.9rem;
    }

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

    .panel .panel-heading a.collapsed,
    .panel .panel-heading a {
        padding: 14px 18px;
    }

    .card a {
        padding: 4px 5px;
    }
}
</style>