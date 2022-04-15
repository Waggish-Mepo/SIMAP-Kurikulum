<template>
<div>
    <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'rayon', params: {page: 8} }"><a href="#">Rayon</a></router-link></li>
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'region.regions', params: {page: 8} }"><a href="#">Daftar Siswa</a></router-link> / Tambah Siswa</li>
            </ol>
        </nav>
        <h4 class="text-capitalize mt-3 mb-4 font-weight-bold">data siswa</h4>
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
                            @on-selected-rows-change="selectionChanged"
                            :select-options="{ enabled: true }"
                            max-height="800px"
                            styleClass="vgt-table condensed striped"
                        >
                        </vue-good-table>
                    </tr>
                </tbody>
            </table>
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
    methods: {
        selectionChanged(e){
            console.log(e.selectRows);
        }
    }
};
</script>