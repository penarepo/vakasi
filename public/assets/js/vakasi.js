$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $(function () {
        var tblVakasi = $("#tblVakasi").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "vakasi-nilai",
                type: "GET",
                error: function (err) {
                    console.log(err);
                },
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    name: "DT_RowIndex",
                },
                {
                    data: "nip",
                    name: "nip",
                },
                {
                    data: "prodi",
                    name: "prodi",
                },
                {
                    data: "dosen_pengajar",
                    name: "dosen_pengajar",
                },
                {
                    data: "action",
                    name: "action",
                    orderable: true,
                    searchable: true,
                },
            ],
            columnDefs: [
                {
                    targets: [0], // your case first column
                    className: "text-center",
                },
            ],
        });
    });

    $("#tblVakasi").on("click", "#btnDetail", function () {
        data_id = $(this).attr("data");

        $.ajax({
            method: "GET",
            url: "/mk-vakasi-nilai/" + data_id,
            success: function (data) {
                var list_mk = data.length;

                if (list_mk != 0) {
                    $("#list_mk").html("");
                    let no = 1;

                    data.forEach(function (data, i) {
                        list = `
                            <tr>
                                <td class="text-center">${no++}</td>
                                <td class="text-center">${data["nama_mk"]} - ${
                            data["nama_kelas"]
                        }</td>
                                <td class="text-center">${
                                    data["jumlah_peserta_kelas"]
                                }</td>
                                <td class="text-center">${data["tgl_uts"]}</td>
                                <td class="text-center">${
                                    data["batas_upload"]
                                }</td>
                                <td class="text-center">${
                                    data["tgl_pengisian_nilai"]
                                }</td>
                                <td class="text-center">${data["status"]}</td>
                            </tr>
                        `;
                        $("#list_mk").append(list);
                    });
                } else {
                    $("#list_mk").html("");
                    list_kosong = `
                            <tr>
                                <td class="text-center" colspan="8">Tidak Ada Mata Kuliah</td>
                            </tr>
                        `;
                    $("#list_mk").append(list_kosong);
                }
                $("#detalMk").modal("show");
            },
        });

        // window.open('/cetak-vakasi-nilai/' + data, '_blank');
    });
});
