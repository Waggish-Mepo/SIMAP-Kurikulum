<template>
    <div>
        <!-- <div class="loader" v-if="isLoading"></div> -->
        <h4 class="text-capitalize mt-3 mb-4 font-weight-bold">rayon</h4>
        <!-- <div class="alert alert-danger my-3" v-if="errorMessage">
            {{ errorMessage }}
        </div> -->
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="text" class="form-control input-text shadow-sm bg-white" placeholder="Cari Rayon....">
                    <div class="input-group-append">
                        <a href="#" class="btn btn-outline-muted btn-lg shadow-sm bg-white"><i class="fa fa-search"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex">
                    <button type="button" class="btn btn-primary btn-block mt-md-1 me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <span class="fas fa-plus"></span> Tambah Rayon
                    </button>
                    <a href="#" class="btn btn-secondary btn-block mt-md-1">Refresh Data</a>
                </div>
            </div>
        </div>
        <div class="table-responsive mt-3">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <vue-good-table
                            :columns="columns"
                            :rows="rows"
                            :pagination-options="paginationOpts"
                            :sort-options="sortOpts"
                            :fixed-header="true"
                            :line-numbers="true"
                            max-height="800px"
                            styleClass="vgt-table condensed striped"
                        >
                        <template slot="table-row" slot-scope="props">
                            <span v-if="props.column.field === 'rayon'">
                                <a href="" class="text-dark" data-bs-toggle="modal" data-bs-target="#exampleModal3">Wikrama 2</a>
                            </span>
                            <span v-if="props.column.field === 'pemb'">
                                <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2">Pilih Pembimbing Rayon</a>
                            </span>
                            <span v-if="props.column.field === 'id'">
                                <a href="/8/region/Regions" class="router">Pilih Siswa Rayon</a>
                            </span>
                        </template>
                        </vue-good-table>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Rayon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="d-flex flex-column">
                                <label class="mb-1">Buat Rayon Baru</label>
                            </div>
                            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Nama Rayon">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3 mb-3">
                        <button type="button" class="btn btn-outline-primary me-3">Batal</button>
                        <button type="button" class="btn btn-primary me-3">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pilih Pembimbing Rayon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="d-flex flex-column">
                                <label class="mb-1">Pembimbing Rayon</label>
                            </div>
                            <select class="form-select" aria-label="Default select example" >
                                <option selected hidden>Pilih Pembimbing Rayon</option>
                                <option value="1">Bu Nunuk</option>
                                <option value="2">Bu Ria</option>
                                <option value="3">Pak Muslih</option>
                                <option value="4">Pak Budiono</option>
                                <option value="5">Pak Jm</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3 mb-3">
                        <button type="button" class="btn btn-outline-primary me-3">Batal</button>
                        <button type="button" class="btn btn-primary me-3">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Rayon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="d-flex flex-column">
                                <label class="mb-1">Nama Rayon</label>
                            </div>
                            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Nama Rayon">
                        </div>
                        <div class="form-group">
                            <div class="d-flex flex-column mt-2">
                                <label class="mb-1">Pembimbing Rayon</label>
                            </div>
                            <select class="form-select" aria-label="Default select example" >
                                <option selected hidden>Pilih Pembimbing Rayon</option>
                                <option value="1">Bu Nunuk</option>
                                <option value="2">Bu Ria</option>
                                <option value="3">Pak Muslih</option>
                                <option value="4">Pak Budiono</option>
                                <option value="5">Pak Jm</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3 mb-3">
                        <button type="button" class="btn btn-outline-danger ms-3" data-bs-toggle="modal" data-bs-target="#exampleModal4">Hapus</button>
                        <div>
                            <button type="button" class="btn btn-outline-primary me-3">Batal</button>
                            <button type="button" class="btn btn-primary me-3">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus Rayon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Yakin Akan Menghapus Mata Pelajaran <b class="text-capitalize">Wikrama 2</b>?</p>
                            <p>Semua Data Nilai Mata Pelajaran <b class="text-capitalize">Wikrama 2</b> Dalam Buku Nilai Akan Terhapus.</p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3 mb-3">
                        <button type="button" class="btn btn-outline-primary me-3">Batal</button>
                        <button type="button" class="btn btn-danger me-3">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  name: 'my-component',
  data(){
    return {
        // modalUpdate: false,
        sortOpts: { enabled: true },
        paginationOpts: {
            enabled: true,
            mode: "records",
            perPage: 40,
            position: "bottom",
            perPageDropdown: [10, 50, 100],
            dropdownAllowAll: true,
            setCurrentPage: 1,
            nextLabel: "Next",
            prevLabel: "Prev",
            rowsPerPageLabel: "Rows per page",
            ofLabel: "of",
            pageLabel: "Page", // for 'pages' mode
            allLabel: "All",
        },
        columns: [
          {
            label: 'Rayon',
            field: 'rayon',
            sortable: false,
            filterOptions: { enabled: true },
          },
          {
            label: 'Pembimbing Rayon',
            field: 'pemb',
            sortable: false,
            filterOptions: { enabled: true },
          },
          {
            label: "Aksi",
            field: "id",
            sortable: false,
            filterOptions: { enabled: false},
          },
        ],
        rows: [
          { id:1, },
          { id:2, },
          { id:3, },
          { id:4, },
          { id:5, },
        ],
      };
    },
};
</script>

