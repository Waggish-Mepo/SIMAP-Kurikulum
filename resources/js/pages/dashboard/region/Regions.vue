<template>
    <div>
        <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'rayon', params: {page: 8} }"><a href="#">Rayon</a></router-link> / Daftar Siswa</li>
            </ol>
        </nav>
        <h4 class="text-capitalize mt-3 mb-4 font-weight-bold">Wikrama 2</h4>
        <div class="col-md-6">
            <div class="d-flex">
                <router-link v-bind:to="{ name: 'region.student-region', params: {page: 8, page: $route.params.page} }" class="btn btn-primary btn-block mt-md-1">
                  <span class="fas fa-plus"></span> Tambah Siswa
                </router-link>
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
                            <span v-if="props.column.field === 'id'">
                                <a href="#" title="Hapus">
                                    <i class="fas fa-trash-alt text-danger" data-bs-toggle="modal" data-bs-target="#exampleModal4"></i>
                                </a>
                            </span>
                        </template>
                        </vue-good-table>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus Siswa Dari Rayon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Yakin Akan Menghapus Siswa Ini Dari Rayon </p>
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
            label: 'NIS',
            field: 'nis',
            filterOptions: { enabled: true },
          },
          {
            label: 'Nama',
            field: 'nama',
            filterOptions: { enabled: true },
          },
          {
            label: 'Rombel',
            field: 'rombel',
            filterOptions: { enabled: true },
          },
          {
            label: "Aksi",
            field: "id",
            tdClass: "text-center",
            sortable: false,
            filterOptions: { enabled: false},
          },
        ],
        rows: [
          { id:1, nis: "11907238", nama:"Adit", rombel: "RPL XII-2"  },
          { id:2, nis: "11907766", nama:"Fema", rombel: "RPL XII-5" },
          { id:3, nis: "11907453", nama:"Rakean", rombel: "RPL XII-6" },
          { id:4, nis: "11907276", nama:"Rendi", rombel: "RPL XII-7" },
          { id:5, nis: "11907123", nama:"Fadhil", rombel: "RPL XII-8" },
          { id:6, nis: "11907886", nama:"Cahya", rombel: "RPL XII-9" },
        ],
      };
    },
};
</script>