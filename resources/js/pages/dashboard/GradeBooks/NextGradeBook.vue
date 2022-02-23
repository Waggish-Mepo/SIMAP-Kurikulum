<template>
    <div class="mt-3">
        <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/:page/gradebook">Buku Nilai Dan Rapor</a></li>
                <li class="breadcrumb-item active" aria-current="page">Buku Nilai Semester Ganjil</li>
            </ol>
        </nav>
        <h4 class="text-capitalize mt-3 mb-4 font-weight-bold"><strong> Bahasa Indonesia</strong></h4>
        <div class="card p-3 mb-2 bg-white" data-bs-toggle="collapse">
            <div class="d-flex justify-content-between text-capitalize">
                <div><span class="fas fa-file-invoice"></span>Rapot Semester Ganjil - Bahasa Indonesia Kelas 12</div>
            </div>
        </div>
        <div class="card p-3 mb-2 bg-white" data-bs-toggle="collapse">
            <div class="d-flex justify-content-between text-capitalize">
                <div><span class="fas fa-file-invoice"></span>Rapot Semester Ganjil - Bahasa Indonesia Kelas 11</div>
            </div>
        </div>
        <h4 class="text-capitalize mt-3 mb-4 font-weight-bold"><strong> Bahasa Inggris</strong></h4>
        <div class="card p-3 mb-2 bg-white" data-bs-toggle="collapse">
            <div class="d-flex justify-content-between text-capitalize">
                <div><span class="fas fa-file-invoice"></span>Rapot Semester Ganjil - Bahasa Inggris Kelas 10</div>
            </div>
        </div>
        <div class="card p-3 mb-2 bg-white" data-bs-toggle="collapse">
            <div class="d-flex justify-content-between text-capitalize">
                <div><span class="fas fa-file-invoice"></span>Rapot Semester Ganjil - Bahasa Inggris Kelas 11</div>
            </div>
        </div>
        <h4 class="text-capitalize mt-3 mb-4 font-weight-bold"><strong> Matematika</strong></h4>
        <div class="card p-3 mb-2 bg-white" data-bs-toggle="collapse">
            <div class="d-flex justify-content-between text-capitalize">
                <div><span class="fas fa-file-invoice"></span>Rapot Semester Ganjil - Matematika Kelas 11</div>
            </div>
        </div>
    </div>
</template>
<style scoped>

.btn-secondary {
    margin-left: 10px !important;
}
span.fas.fa-file-invoice{
    margin-right: 5px;
}
</style>