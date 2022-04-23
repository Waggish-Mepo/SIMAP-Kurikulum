<!DOCTYPE html>
<html>
<head>
    <title>{{$student['nis']}}-{{$student['name']}}</title>
    <style>
        body{
            margin: 0;
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif;
        }
        h3{
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
        .footer-content, .footer-div {
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
        .first-section, .header-div {
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
            padding: 5px;
            border: 1px solid #000;
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
            border: 1px solid #000;
            font-size: 12px;
            padding: 5px;
            line-height: 18px;
            height: 50px;
            text-transform: capitalize;
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
            font-size: 12px;
            border: 1px solid #000;
            height: auto;
            padding: 5px 15px;
            margin-top: 40px;
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
            float: left;
            width: 50%;
        }
        .five-section .top-content .column.right {
            margin-left: 35%;
        }
        .five-section p.date {
            text-align: right;
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
            display: flex;
            margin-left: 45%;
        }
    </style>
</head>
<body>
    <h3>laporan kemajuan hasil belajar peserta didik</h3>
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
            <tr>
                <td>
                    <div class="second-section">
                        <h5>A. Nilai Akademik</h5>
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="2">Mata Pelajaran</th>
                                    <th>Nilai Akhir</th>
                                    <th>Predikat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subjectgroup as $group => $scorecard)
                                    <tr>
                                        {{$groupFilter = str_replace("(", "", $group)}}
                                        {{$groupFilterNew = str_replace(")", "", $groupFilter)}}
                                        <td colspan="4" class="td-bold">{{substr($groupFilterNew, 0, 1)}}. {{substr($groupFilterNew, 1, strlen($groupFilterNew))}}</td>
                                    </tr>
                                    @foreach ($scorecard as $sc)
                                        {{$index++}}
                                        <tr>
                                            <td class="td-center">{{$index}}</td>
                                            <td>{{$sc['gradebook']['course']['subject']['name']}}</td>
                                            <td class="td-center">{{$sc['final_score']}}</td>
                                            <td class="td-center">{{$sc['predicate_desc']['letter']}}</td>
                                        </tr>
                                    @endforeach
                                    {{$index = 0}}
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="third-section">
                        <h5>B. Catatan Akademik</h5>
                        <div class="card">
                        {{$reportbook['notes']}}
                        </div>
                    </div>
                    <div class="fourth-section">
                        <h5>C. Ketidakhadiran</h5>
                        <table>
                            <thead>
                                <tr>
                                    <th class="t-left">Keterangan</th>
                                    <th class="t-left">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Sakit</td>
                                    <td>{{$reportbook['absences']['sakit'] ?? '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Ijin</td>
                                    <td>{{$reportbook['absences']['izin'] ?? '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Tanpa Keterangan</td>
                                    <td>{{$reportbook['absences']['alpa'] ?? '-'}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="five-section">
                        <p class="date">Bogor, {{$date}}</p>
                        <div class="top-content">
                            <div class="column">
                                <p>Orang Tua/Wali</p>
                                <div class="signature parent"></div>
                            </div>
                            <div class="column right">
                                <p>Wali Kelas</p>
                                <div class="signature"></div>
                                <div class="name">{{$student_counselor['name'] ?? '-'}}</div>
                            </div>
                        </div>
                        <div class="last-content">
                            <p>Mengetahui,
                                <br>Kepala Sekolah
                            </p>
                            <div class="signature"></div>
                            <div class="name">{{$headmaster_name}}</div>
                        </div>
                    </div>
                </td>
            </tr>
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
                    <td style="width: 34rem">
                        <div class="wrap-text">
                            <span class="title">NIS / NISN</span>
                            : {{$student['nis']}}/{{$student['nisn']}}
                        </div>
                        <div class="wrap-text">
                            <span class="title">Nama Peserta Didik</span>
                            : {{$student['name']}}
                        </div>
                        <div class="wrap-text">
                            <span class="title">Kompetensi Keahlian</span>
                            : {{$major['name']}}                
                        </div>
                    </td>
                    <td>
                        <div class="wrap-text">
                            <span class="title">Tahun Pelajaran</span>
                            : {{$reportperiod['school_year']}}
                        </div>
                        <div class="wrap-text">
                            <span class="title">Semester</span>
                            : {{$semester}}
                        </div>
                        <div class="wrap-text">
                            <span class="title">Kelas</span>
                            : {{$studentgroup['name']}}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="footer-div"></div>
</body>
</html>