const HELPER = {
    createCombo: async function (config) {
        const {
            el,
            valueField,
            displayField,
            url,
            withNull = false,
            grouped = false,
            chosen = false,
            callback = () => { }
        } = config;

        try {
            const response = await fetch(url);
            const data = await response.json();

            el.forEach(id => {
                const select = document.getElementById(id);
                if (!select) return;

                select.innerHTML = '';

                if (withNull) {
                    const option = document.createElement('option');
                    option.value = '';
                    option.text = '-- Pilih --';
                    select.appendChild(option);
                }

                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item[valueField];
                    option.text = item[displayField];
                    select.appendChild(option);
                });

                if (chosen && typeof $ !== 'undefined' && $.fn.select2) {
                    $('#' + id).select2({
                        placeholder: '-- Pilih --',
                        width: '100%',
                    });
                }

                callback();
            });

        } catch (err) {
            console.error('Gagal mengambil data combo:', err);
        }
    },

    initDatatable: function (config) {
        config = $.extend(true, {
            el: '',
            multiple: false,
            sorting: 'asc',
            index: 1,
            force: false,
            responsive: false,
            parentCheck: 'checkAll',
            childCheck: 'checkbox',
            clickable: false,
            stateSave: false,
            checkboxAble: false,
            destroyAble: false,
            showCheckbox: false,
            tabDetails: false,
            advanceFilter: {
                show: false,
                function: "showFilter()"
            },
            // data: {
            //     csrf_spi: $.cookie('csrf_lock_spi'),
            // },
            filterColumn: {
                state: false,
                exceptionIndex: []
            },
            callbackClick: function (row, data, seleced) { },
            detailClickCallback: function () { },
            callbackComplete: function (table) { },
        }, config);

        var defaultColumnDef = [{
            "bSortable": false,
            "bSearchable": false,
            "aTargets": 0,
            "render": function (data, type, row, meta) {
                return `
                        <div class="ml-5">
                            ${data}
                        </div>
                    `;
            }
        }];
        if (config.tabDetails) {
            defaultColumnDef = [{
                "bSortable": false,
                "bSearchable": false,
                "aTargets": 0,
                "render": function (data, type, row, meta) {
                    var dataRecord = $(data).find('input.checkbox').data('record');
                    return `
                            <div class="d-flex flex-row ml-5">
                                ${data}
                                <span class="label label-md label-rounded label-success tab-details ml-5 span-child" data-row="${meta.row}" data-record="${dataRecord}" onclick="HELPER.onTabDetails(this)">
                                    <i class="flaticon2-next icon-sm text-white pt-1"></i>
                                </span>
                            </div>
                        `;
                }
            }];
        }
        var xdefault = {
            "aLengthMenu": [
                [5, 10, 15, 25, 50, 100],
                [5, 10, 15, 25, 50, 100]
            ],
            "iDisplayLength": 10,
            "sPaginationType": "bootstrap_full_number",
            "bProcessing": true,
            'bServerSide': true,
            "bAutoWidth": true,
            "scrollCollapse": true,
            "pagingType": "simple_numbers",
            "responsive": config.responsive,
            destroy: true,
            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "sLengthMenu": "Tampilkan _MENU_ data per halaman",
                "emptyTable": "Tidak ada data yang dapat ditampilkan",
                "info": "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data yang dapat ditampilkan",
                "infoFiltered": "(Ditemukan dari  total _MAX_ data)",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "search": "Pencarian :",
                "searchPlaceholder": "Search ...",
                "sProcessing": "<img src='./assets/img/loading.gif'>",
                "zeroRecords": "Tidak ada data ditemukan"
            },
            "aoColumnDefs": defaultColumnDef,
            "order": [
                [config.index, config.sorting]
            ],
            headerCallback: function (thead, data, start, end, display) {
                if (config.checkboxAble && config.multiple) {
                    thead.getElementsByTagName('th')[0].innerHTML = `
                            <label class="checkbox checkbox-single text-center ml-5">
                                <input type="checkbox" value="" class="group-checkable checkAll"/>
                                <span></span>
                            </label>
                        `;
                }
            },
            fnDrawCallback: function (oSettings) {
                if (config.clickAble) {
                    if (!config.multiple) {
                        $(`#${config.el} tbody`).find('tr').each(function (i, v) {
                            $('td:eq(0)', v).css({
                                'text-align': 'center'
                            });
                            $(v).addClass('clickable');
                        })
                        $('.row_selected').removeClass('row_selected');
                        $("#" + config.el + " tr").css('cursor', 'pointer');
                        $("#" + config.el + " tbody tr").each(function (i, v) {
                            if (config.showCheckbox == true) {
                                $('input[name=checkbox]', v).removeClass('d-none').addClass('d-block');
                            }
                            $(v).on('click', function () {
                                if (oSettings.aoData.length > 0) {
                                    $(v).addClass('row_selected');
                                    if ($(this).hasClass('selected')) {
                                        $(v).removeClass('selected');
                                        $(v).removeAttr('checked');
                                        $('input[name=checkbox]', v).prop('checked', false);
                                        $('.disable').attr('disabled', true);
                                        $('.row_selected').removeClass('row_selected');
                                        var getRowData = $(`input[name="checkbox"]`).data('record');
                                        config.callbackClick(
                                            i, // set row,
                                            JSON.parse(atob(getRowData)), // set row data
                                            false // set is not selected
                                        );
                                    } else {
                                        $(".checkbox").removeAttr('checked');
                                        $(".selected").removeClass('selected');
                                        $('#' + config.el + '.dataTable tbody tr.selected').removeClass('selected');
                                        $(v).addClass('selected');
                                        $('.row_selected').removeClass('row_selected');
                                        $(v).addClass('row_selected');
                                        $('input[name=checkbox]', v).prop('checked', true);
                                        $('.disable').attr('disabled', false);
                                        var getRowData = $(`input[name="checkbox"]`).data('record');
                                        config.callbackClick(
                                            i, // set row,
                                            JSON.parse(atob(getRowData)), // set row data
                                            true // set is selected
                                        );
                                    }
                                }
                            });
                        });
                    } else {
                        $(`#${config.el} tbody`).find('tr').each(function (i, v) {
                            $(v).addClass('clickable');
                        });
                        var cnt = 0;
                        $(`#${config.el} tr`).css('cursor', 'pointer');
                        $(`#${config.el} tbody tr`).each(function (i, v) {
                            if (config.showCheckbox) {
                                $('.not-checkbox', v).addClass('d-none')
                                $('input[name=checkbox]', v).removeClass('d-none');
                                $('input[name=checkbox]', v).addClass(config.childCheck)

                                $('input[name=checkbox]', v).on('change', function () {
                                    $(v).trigger('click');
                                });
                            }
                            $(v).off('click');
                            $(v).on('click', function (el) {
                                if ($(this).hasClass('selected')) {
                                    --cnt;
                                    $(v).removeClass('selected');
                                    $(v).removeAttr('checked');
                                    $('input[name=checkbox]', v).prop('checked', false);
                                    $(v).removeClass('row_selected');
                                    var run = config.callbackClick(v);
                                } else {
                                    ++cnt;
                                    $('input[name=checkbox]', v).prop('checked', true);
                                    var run = config.callbackClick(v);
                                    $(v).addClass('selected');
                                    $(v).addClass('row_selected');
                                }

                                if (cnt > 0) {
                                    $('.disable').attr('disabled', false);
                                } else {
                                    $('.disable').attr('disabled', true);
                                }
                            });
                        });

                        $('.' + config.parentCheck).click(function (event) {
                            if (this.checked) {
                                $('.' + config.childCheck).each(function () {
                                    this.checked = true;
                                    $(this).trigger('change')
                                });
                                $("#" + config.el + " tbody tr").each(function (i, v) {
                                    var run = config.callbackClick(v);
                                    $(v).addClass('selected');
                                    $(v).addClass('row_selected');
                                });
                                $('.' + config.parentCheck).addClass('selected');
                                $('.disable').attr('disabled', false);
                            } else {
                                $('.' + config.childCheck).each(function () {
                                    this.checked = false;
                                    $(this).trigger('change')
                                });
                                $("#" + config.el + " tbody tr").each(function (i, v) {
                                    var run = config.callbackClick(v);
                                    $(v).removeClass('row_selected');
                                    $(v).removeClass('selected');
                                    $(v).removeAttr('checked');
                                });
                                $('.disable').attr('disabled', true);
                            }
                        });

                        $('#' + config.el).find('th').click(function (i, v) {
                            if ($(this).hasClass('sorting_disabled')) { } else {
                                $("#" + config.el + " tbody tr").each(function (i2, v2) {
                                    $(v2).removeClass('row_selected');
                                    $(v2).removeClass('selected');
                                    $(v2).removeAttr('checked');
                                });
                                $('.' + config.parentCheck).removeClass('selected');
                                $('.' + config.parentCheck).prop('checked', false).trigger('change');
                            }
                        })

                    }
                }

                if (config.tabDetails) {
                    $(`.span-child`).click((e) => {
                        var target = e.currentTarget;
                        var rowShown = $(target).hasClass('shown');
                        if (rowShown) {
                            var dataTarget = $(target).data();
                            var runClickSub = config.detailClickCallback(
                                JSON.parse(atob(dataTarget.record)),
                                dataTarget.row,
                                `div-child-${dataTarget.row}`,
                            );
                        }
                    });
                }
                HELPER.setRole();
            },
            fnRowCallback: function (row, data, index, rowIndex) {
                $('.disable').attr('disabled', true);
            },
            fnInitComplete: function (oSettings, data) {
                HELPER.setRole();
            },

            "ajax": {
                'url': config.url,
                'type': 'POST',
                data: function (d) {
                    // d.csrf_spi = $.cookie('csrf_lock_spi');
                    d.data = config.data;
                }
            },
        };

        var el = $("#" + config.el);
        if (!config.force) {
            var dt = $(el).dataTable($.extend(true, xdefault, config));
        } else {
            var dt = $(el).dataTable(config);
        }

        config.callbackComplete(dt);

        $(el).addClass('table-condensed').removeClass('table-striped').addClass('compact nowrap hover dt-head-left');
        if (config.advanceFilter.show) {
            $(`#${config.el}_filter`).find('label').find(`input[type="search"]`).after(`
                    <a
                        href="javascript:void(0);"
                        onclick="${config.advanceFilter.function}"
                        class="btn btn-sm btn-icon btn-secondary"
                    >
                        <span class="svg-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M5,4 L19,4 C19.2761424,4 19.5,4.22385763 19.5,4.5 C19.5,4.60818511 19.4649111,4.71345191 19.4,4.8 L14,12 L14,20.190983 C14,20.4671254 13.7761424,20.690983 13.5,20.690983 C13.4223775,20.690983 13.3458209,20.6729105 13.2763932,20.6381966 L10,19 L10,12 L4.6,4.8 C4.43431458,4.5790861 4.4790861,4.26568542 4.7,4.1 C4.78654809,4.03508894 4.89181489,4 5,4 Z" fill="#000000"/>
                                </g>
                            </svg>
                        </span>
                    </a>
                `);
        }
        return dt;
    },

    onTabDetails: function (el) {
        var rowData = $(el).data();
        var rowShown = $(el).hasClass('shown');
        var myQueue = new Queue();
        myQueue.enqueue(function (next) {
            if (!rowShown) {
                $(el).removeClass('label-success');
                $(el).addClass('label-danger');
                $(el).addClass('shown');
                $(el).find('i').removeClass('flaticon2-next');
                $(el).find('i').addClass('flaticon2-down');
            } else {
                $(el).removeClass('label-danger');
                $(el).addClass('label-success');
                $(el).removeClass('shown');
                $(el).find('i').removeClass('flaticon2-down');
                $(el).find('i').addClass('flaticon2-next');
            }
            next();
        }, '1m').enqueue(function (next) {
            var row = $(el).parent().closest('tr');
            var tds = row.find('td');
            if (!rowShown) {
                $(row).after(`
                        <tr class="child-${rowData.row}">
                            <td colspan="${tds.length}">
                                <div class="div-child" id="div-child-${rowData.row}">
                                    Place your data here
                                </div>
                            </td>
                        </tr>
                    `);
            } else {
                $(`tr.child-${rowData.row}`).remove();
            }
        }, 'end').dequeueAll();
    },

    setRole: function () {
        $.each(__role, function (i, v) {
            $('.xremove_button[data-role="' + v.menu_kode + '"]').removeClass('hide');
        });
    },

    save: function (config) {
        var xurl = null;
        if (config.addapi === true) {
            xurl = ($("[name=" + HELPER[config.fields][0] + "]").val() === "") ? HELPER[config.api].store : HELPER[config.api].update;
        }
        else {
            if (typeof HELPER.api != 'undefined') {
                xurl = ($("[name=" + HELPER.fields[0] + "]").val() === "") ? HELPER.api.store : HELPER.api.update;
            }
        }

        config = $.extend(true, {
            form: null,
            confirm: false,
            confirmIcon: 'warning',
            confirmTitle: 'Simpan Data',
            confirmMessage: 'Apakah anda yakin untuk menyimpan data tersebut?',
            data: $.extend($('[name=' + config.form + ']').serializeObject()),
            method: 'POST',
            fields: 'fields',
            api: 'api',
            addapi: false,
            url: xurl,
            withNotes: {
                show: false,
                required: false,
            },
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            },
            cache: false,
            contentType: 'application/x-www-form-urlencoded',
            // processData: false,
            success: function (response) {
                HELPER.showMessage({
                    icon: response.success ? 'success' : 'error',
                    success: response.success,
                    message: response.message,
                    title: ((response.success) ? 'Sukses' : 'Gagal')
                });
                unblock(100);
            },
            error: function (response, status, errorname) {
                HELPER.showMessage({
                    icon: 'error',
                    success: false,
                    title: errorname,
                    message: 'Terjadi Kesalahan Sistem, hubungi Administrator'
                });
                unblock(100);
            },
            complete: function (response) {
                var rsp = $.parseJSON(response.responseText);
                config.callback(rsp.success, rsp.id, rsp.record, rsp.message, response);
            },
            callback: function (arg) { }
        }, config);

        var do_save = function (_config) {
            loadBlock('Sedang menyimpan data...');
            $.ajax({
                url: _config.url,
                data: _config.data,
                type: _config.method,
                cache: _config.cache,
                contentType: _config.contentType,
                processData: _config.processData,
                xhr: function () {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                success: _config.success,
                error: _config.error,
                complete: _config.complete
            });
        }

        if (config.confirm) {
            if (config.withNotes.show) {
                var editorHtml = `
                        <span class="text-dark-50">${config.confirmMessage}${(config.withNotes.required) ? '<span class="required text-danger" aria-required="true">* </span>' : ''}</span>
                        <textarea
                            class="form-control mt-5"
                            id="notes"
                            name="notes"
                            placeholder="Masukkan catatan ..."
                            ${(config.withNotes.required) ? "required" : ""}
                            rows="5"
                        ></textarea>
                    `;
                var swalConfig = {
                    title: config.confirmTitle,
                    icon: config.confirmIcon,
                    confirmButtonText: 'Ya, Lanjutkan!',
                    confirmButtonClass: 'btn btn-focus btn-primary',
                    reverseButtons: true,
                    showCancelButton: true,
                    cancelButtonText: 'Tidak',
                    cancelButtonClass: 'btn btn-focus btn-secondary',
                    html: editorHtml
                }
            } else {
                var swalConfig = {
                    title: config.confirmTitle,
                    text: config.confirmMessage,
                    icon: config.confirmIcon,
                    confirmButtonText: 'Ya, Lanjutkan!',
                    confirmButtonClass: 'btn btn-focus btn-primary',
                    reverseButtons: true,
                    showCancelButton: true,
                    cancelButtonText: 'Tidak',
                    cancelButtonClass: 'btn btn-focus btn-secondary'
                };
            }
            Swal.fire(swalConfig).then(function (result) {
                if (result.value) {
                    if (config.withNotes.required && $(`[name="notes"]`).val() === '') {
                        // swal without button ok
                        Swal.fire({
                            icon: 'error',
                            title: 'Catatan tidak boleh kosong!',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        return;
                    }
                    // append notes if exist
                    if (config.withNotes.show) {
                        config.data.append('notes', $(`[name="notes"]`).val());
                    }
                    do_save(config);
                } else {
                    config.oncancel(result)
                }
            });
        }
        else {
            do_save(config);
        }
    },

    destroy: function (config) {
        config = $.extend(true, {
            table: null,
            confirm: true,
            method: 'POST',
            api: 'api',
            data: null,
            multiple: false,
            fields: 'fields',
            callback: function (arg) { }
        }, config);

        var do_destroy = function (_config, id) {
            loadBlock('Sedang menghapus data');
            var dataSend = {};
            if (_config.data === null) {
                dataSend['id'] = id;
            }
            else {
                dataSend['id'] = id;
                $.each(_config.data, function (i, v) {
                    dataSend[i] = v;
                });
            }
            $.ajax({
                url: HELPER[config.api].destroy,
                data: $.extend(dataSend),
                type: _config.method,
                success: function (response) {
                    HELPER.showMessage({
                        success: response.success,
                        message: response.message,
                        title: ((response.success) ? 'Sukses' : 'Gagal')
                    });
                    unblock(100);
                },
                error: function (response, status, errorname) {
                    HELPER.showMessage({
                        success: false,
                        title: 'Gagal melakukan operasi',
                        message: errorname,
                    });
                    unblock(100);
                },
                complete: function (response) {
                    var rsp = $.parseJSON(response.responseText);
                    config.callback(rsp.success, rsp.id, rsp.record, rsp.message);
                },
            })
        }

        var do_destroy_multiple = function (_config, data) {
            var dataSend = {};
            $.each(data, function (i, v) {
                dataSend[i] = v;
            });
            loadBlock('Sedang menghapus data');
            $.ajax({
                url: config.url,
                data: $.extend(dataSend),
                type: _config.method,
                success: function (response) {
                    HELPER.showMessage({
                        success: response.success,
                        message: response.message,
                        title: ((response.success) ? 'Sukses' : 'Gagal')
                    });
                    unblock(100);
                },
                error: function (response, status, errorname) {
                    HELPER.showMessage({
                        success: false,
                        title: 'Gagal melakukan operasi',
                        message: errorname,
                    });
                    unblock(100);
                },
                complete: function (response) {
                    var rsp = $.parseJSON(response.responseText);
                    config.callback(rsp.success, rsp.id, rsp.record, rsp.message);
                },
            })
        }
        if (config.multiple === false) {
            var data = null;
            $("#" + config.table).find('input[name=checkbox]').each(function (key, value) {
                if ($(value).is(":checked")) {
                    data = $.parseJSON(atob($(value).data('record')));
                }
            });
            if (data !== null) {
                var id = data[HELPER[config.fields][0]];
                if (config.confirm) {
                    bootbox.confirm({
                        title: "Informasi",
                        message: "Apakah anda yakin akan menghapus data tersebut?",
                        buttons: {
                            confirm: {
                                label: '<i class="fa fa-check"></i> Ya',
                                className: 'btn-success'
                            },
                            cancel: {
                                label: '<i class="fa fa-times"></i> Tidak',
                                className: 'btn-danger'
                            }
                        },
                        callback: function (result) {
                            if (result === true) {
                                do_destroy(config, id);
                            }
                        }
                    });
                }
                else {
                    do_destroy(config, id);
                }
            }
            else {
                HELPER.showMessage({
                    title: 'Informasi',
                    message: 'Anda belum memilih data pada tabel...!',
                    image: './assets/img/information.png',
                    time: 2000
                })
            }
        }
        else {
            var data = [];
            $("#" + config.table).find('input[name=checkbox]').each(function (key, value) {
                if ($(value).is(":checked")) {
                    var cek = $.parseJSON(atob($(value).data('record')));
                    data[key] = cek;
                }
            });

            if (data.length > 0) {
                if (config.confirm) {
                    bootbox.confirm({
                        title: "Informasi",
                        message: "Apakah anda yakin akan menghapus data tersebut?",
                        buttons: {
                            confirm: {
                                label: '<i class="fa fa-check"></i> Ya',
                                className: 'btn-success'
                            },
                            cancel: {
                                label: '<i class="fa fa-times"></i> Tidak',
                                className: 'btn-danger'
                            }
                        },
                        callback: function (result) {
                            if (result === true) {
                                do_destroy_multiple(config, data);
                            }
                        }
                    });
                }
                else {
                    do_destroy_multiple(config, data);
                }
            }
            else {
                HELPER.showMessage({
                    title: 'Informasi',
                    message: 'Anda belum memilih data pada tabel...!',
                    image: './assets/img/information.png',
                    time: 2000
                })
            }
        }
    },
};
