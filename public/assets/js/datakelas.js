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
                url: "data-kelas-all",
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
                    data: "kode_mk",
                    name: "kode_mk",
                },
                {
                    data: "nama_mk",
                    name: "nama_mk",
                },
                {
                    data: "nama_kelas",
                    name: "nama_kelas",
                },
                {
                    data: "dosen_pengajar",
                    name: "dosen_pengajar",
                },
                {
                    data: "status",
                    name: "status",
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

    $("#tblVakasi").on("click", "#btnDetailKelas", function () {
        data_id = $(this).attr("data");

        $.ajax({
            method: "GET",
            url: "/data-kelas-detail/" + data_id,
            success: function (data) {
                list = `<div class="form-group">
                            <input type="text" hidden class="form-control" name="id" value="${data["id"]}">
                        </div>
                        <div class="form-group">
                            <label for="tgl_pengisian_nilai" class="col-form-label">Tanggal Input Nilai:</label>
                            <input type="datetime-local" class="form-control" value="${data["tgl_pengisian_nilai"]}" name="tgl_pengisian_nilai">
                        </div>
                        <div class="form-group">
                            <label for="bonus_tepat_mengajar">Bonus Tepat Mengajar</label>
                            <select class="form-control" name="bonus_tepat_mengajar" id="bonus_tepat_mengajar">
                                <option selected value="${data["bonus_tepat_mengajar"]}">${data["bonus_tepat_mengajar"]}</option>
                                <option value="0">Rp. 0</option>
                                <option value="175000">Rp. 175.000</option>
                                <option value="200000">Rp. 200.000</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status_pencairan">Status Pencairan</label>
                            <select class="form-control" name="status_pencairan" id="status_pencairan">
                                <option selected value="${data["status_pencairan"]}">${data["status_pencairan"]}</option>
                                <option value="Y">Ya</option>
                                <option value="T">Tidak</option>
                            </select>
                        </div>
                        <div class="form-group">
                        <label for="tgl_pencairan" class="col-form-label">Tanggal Pencairan:</label>
                        <input type="date" class="form-control" value="${data["tgl_pencairan"]}" name="tgl_pencairan">
                        </div>
                        `;
                document.getElementById("list_mk").innerHTML = list;
                // $("#list_mk").innerHTML();
                $("#detalMk").modal("show");
            },
        });
    });
});
