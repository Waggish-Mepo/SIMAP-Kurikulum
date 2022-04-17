<!DOCTYPE html>
<html>

<head>
    <title>Report Attitude</title>
    <style>
        body {
            margin: 0;
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif;
            padding: 20px;
        }

        h3 {
            text-align: center;
            text-transform: capitalize;
            margin: 0 0 30px 0;
        }

        table.container {
            width: 100%;
        }

        thead.header {
            font-weight: normal !important;
        }

        .footer-content,
        .footer-div {
            text-align: center;
            height: 30px;
        }

        .footer-div::after {
            counter-reset: page;
            counter-increment: page;
            content: counter(page);
            color: #000;
        }

        .header-div {
            position: fixed;
            top: 50px;
        }

        .footer-div {
            position: fixed;
            bottom: 0;
            left: 50%;
            font-size: 12px;
        }

        .first-section,
        .header-div {
            display: flex;
            justify-content: space-between;
            text-transform: capitalize;
            text-align: left;
            height: 60px;
        }

        .wrap-text {
            display: flex;
            margin-bottom: 8px;
            font-size: 12px;
        }

        span.title {
            width: 145px;
        }

        h5 {
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .second-section table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            font-size: 12px;
        }

        .second-section table thead tr th {
            padding: 10px;
            text-align: center;
            border: 1px solid #000;
        }

        .second-section table tbody tr td {
            border: 1px solid #000;
            height: 50px;
        }

        .second-section table tbody tr td.td-bold {
            margin-left: 5px;
            font-weight: bold;
        }

        .second-section table tbody tr td.td-center {
            text-align: center;
        }

        .card {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            font-size: 12px;
            text-align: center;
            height: 60px;
        }

        .fourth-section table {
            width: 45%;
            border-collapse: collapse;
            font-size: 12px;
            border: 1px solid #000;
        }

        .fourth-section table thead tr th {
            padding: 10px;
            border: 1px solid #000;
        }

        .fourth-section table thead tr th.t-left {
            padding: 10px;
            border: 1px solid #000;
            text-align: left;
        }

        .fourth-section table tbody tr td {
            padding: 5px;
            border: 1px solid #000;
        }

        .five-section {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            font-size: 12px;
            height: auto;
            margin-top: 10px;
        }

        .five-section .top-content {
            display: flex;
        }

        .five-section .top-content::after {
            content: "";
            display: table;
            clear: both;
        }

        .five-section .top-content .column {
            padding: 30px;
            float: left;
            width: 50%;
        }

        .five-section .top-content .column.right {
            margin-left: 35%;
        }


        .signature {
            width: 100px;
            height: 60px;
            border-bottom: 1px solid #000;
        }

        .name {
            margin-top: 8px;
        }

        .five-section .last-content {
            margin-top: 5%;
            margin-left: 45%;
            margin-bottom: 5%;
        }
    </style>
</head>

<body>
    <h3>laporan Perkembangan Karakter Peserta Didik</h3>
    <table class="container">
        <thead class="header">
            <tr>
                <td>
                    <div class="first-section">
                    </div>
                </td>
            </tr>
        </thead>
        <tbody class="content">
            <td>
                <div class="second-section">
                    <h5>F. Deskrikpsi Perkembangan Karakter</h5>
                    <table>
                        <thead>
                            <tr>
                                <th>Karakter yang dibangun</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="td-center">Integritas</td>
                                <td class="td-center">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Excepturi, quae optio.</td>
                            </tr>
                            <tr>
                                <td class="td-center">Religius</td>
                                <td class="td-center">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Excepturi, quae optio.</td>
                            </tr>
                            <tr>
                                <td class="td-center">Nasionalis</td>
                                <td class="td-center">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Excepturi, quae optio.</td>
                            </tr>
                            <tr>
                                <td class="td-center">Mandiri</td>
                                <td class="td-center">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Excepturi, quae optio.</td>
                            </tr>
                            <tr>
                                <td class="td-center">Gotong Royong</td>
                                <td class="td-center">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Excepturi, quae optio.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="third-section">
                    <h5>G. Catatan Perkembangan Karakter</h5>
                    <div class="card">
                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Excepturi, quae optio.</p>
                    </div>
                </div>
                <div class="five-section">
                    <div class="top-content">
                        <div class="column">
                            <p>Orang Tua/Wali</p>
                            <div class="signature parent"></div>
                        </div>
                        <div class="column right">
                        <p class="date">Bogor, </p>
                            <p>Wali Kelas</p>
                            <div class="signature"></div>
                            <div class="name"></div>
                        </div>
                    </div>
                    <div class="last-content">
                        <p>Mengetahui,</p>
                        <p>Kepala Sekolah</p>
                        <div class="signature"></div>
                        <div class="name"></div>
                    </div>
                </div>
            </td>
        </tbody>
        <tfoot class="footer">
            <tr>
                <td>
                    <div class="footer-content">
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="header-div">
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td style="width: 60rem">
                        <div class="wrap-text">
                            <span class="title">NIS / NISN</span>
                            :
                        </div>
                        <div class="wrap-text">
                            <span class="title">Nama Peserta Didik</span>
                            :
                        </div>
                        <div class="wrap-text">
                            <span class="title">Kompetensi Keahlian</span>
                            :
                        </div>
                    </td>
                    <td>
                        <div class="wrap-text">
                            <span class="title">Tahun Pelajaran</span>
                            :
                        </div>
                        <div class="wrap-text">
                            <span class="title">Semester</span>
                            :
                        </div>
                        <div class="wrap-text">
                            <span class="title">Kelas</span>
                            :
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
</body>

</html>