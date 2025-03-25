var menuid = null;
var __role = [];

var HELPER = function () {
    var loadBlock = function (message) {
        $.blockUI({
            message: '<i class="fa fa-spinner fa-spin fa-2x" style="color:#3598dc;"></i>',
            css: { border: 'none', backgroundColor: 'rgba(47, 53, 59, 0)' },
            baseZ: 999999
        });
    }

    var unblock = function (delay) {
        window.setTimeout(function () {
            $.unblockUI();
        }, delay);
    }

    var html_entity_decode = function (txt) {
        var randomID = Math.floor((Math.random() * 100000) + 1);
        $('body').append('<div id="random' + randomID + '"></div>');
        $('#random' + randomID).html(txt);
        var entity_decoded = $('#random' + randomID).html();
        $('#random' + randomID).remove();
        return entity_decoded;
    }

    // untuk rename kolom file input

    return {
        block: function (msg) {
            loadBlock(msg);
        },
        unblock: function (delay) {
            unblock(delay);
        },
        toRp: function (angka) {
            var rev = parseInt(angka, 10).toString().split('').reverse().join('');
            var rev2 = '';
            for (var i = 0; i < rev.length; i++) {
                rev2 += rev[i];
                if ((i + 1) % 3 === 0 && i !== (rev.length - 1)) {
                    rev2 += '.';
                }
            }
            return '' + rev2.split('').reverse().join('') + ',00';
        },
        setting: function () {
            var html =
                '<div class="modal fade" id="modal_setting" tabindex="-1" data-backdrop="static" data-keyboard="false">' +
                '<div class="modal-dialog">' +
                '<div class="modal-content btn-radius">' +
                '<div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>' +
                '<span class="caption-subject font-purple-intense" style="font-weight: bold;">' +
                '<i class="icon-settings"></i> Pengaturan Akun' +
                '</span>' +
                '</div>' +
                '<form action="javascript:HELPER.onUpdatePassword()" name="set_account_form" class="form-horizontal">' +
                '<div class="modal-body">' +
                '<div class="row" style="padding: 20px 0px 0px 10px;">' +
                '<div class="form-group">' +
                '<label class="control-label col-md-3">Password Lama <span style="color: red;">*</span></label>' +
                '<div class="col-md-8">' +
                '<input type="password" name="old_password" class="form-control rb" required="" placeholder="Password lama">' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label class="control-label col-md-3">Password Baru <span style="color: red;">*</span></label>' +
                '<div class="col-md-8">' +
                '<input type="password" name="new_password" class="form-control rb" required="" placeholder="Password baru">' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label class="control-label col-md-3">Ulangi Password <span style="color: red;">*</span></label>' +
                '<div class="col-md-8">' +
                '<input type="password" name="re_password" class="form-control rb" required="" placeholder="Ulangi password baru">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="modal-footer">' +
                '<button type="button" data-dismiss="modal" class="btn dark btn-outline">Batal</button>' +
                '<button type="submit" class="btn green">Simpan</button>' +
                '</div>' +
                '</form>' +
                '</div>' +
                '</div>' +
                '</div>';
            $('#child-md').html(html);
        },
        onUpdatePassword: function () {
            HELPER.confirm({
                message: 'Apakah anda yakin ingin mengubah data tersebut?',
                callback: function (result) {
                    if (result) {
                        HELPER.block();
                        HELPER.ajax({
                            url: APP_URL + 'Siswa/upDatePassword',
                            data: {
                                old_password: $('[name="old_password"]').val(),
                                new_password: $('[name="new_password"]').val(),
                                re_password: $('[NAME="re_password"]').val()
                            }, success: function (dSuc) {
                                if (dSuc.success) {
                                    $('.rb').attr('style', '');
                                    $('#modal_setting').modal('hide');
                                } else {
                                    $('.rb').attr('style', '');
                                    $.each(dSuc.fields, function (i, v) {
                                        $('[name="' + v + '"]').attr('style', 'border: 1px solid red;')
                                    });
                                    HELPER.showMessage({
                                        title: 'Informasi',
                                        success: false,
                                        message: dSuc.fields[0],
                                    });
                                }
                            }, complete: function (dCom) {
                                HELPER.unblock(500);
                            }
                        })
                    }
                }
            });
        },
        login: function () {
            alert()
        },
        logout: function () {
            HELPER.confirm({
                title: 'Informasi',
                text: 'Apakah anda yakin akan keluar?',
                callback: function (result) {
                    if (result) {
                        HELPER.ajax({
                            url: `${APP_URL}MainPage/logout`,
                            data: {
                                csrf_spi: $.cookie('csrf_lock_spi'),
                                token: ""
                                // token: FCM.getMyToken()
                            }, complete: function (response) {
                                if (localStorage.getItem('from') != null) {
                                    window.location.href = localStorage.getItem('from');
                                    localStorage.removeItem("from");
                                } else {
                                    window.location.href = APP_URL_REMOVE_INDEX + 'spi-login';
                                }
                            }
                        });
                    }
                }
            });
        },
        html_entity_decode: function (txt) {
            html_entity_decode(txt);
        },
        getCookie: function (cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1);
                if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
            }
            return "";
        },
        nullConverter: function (val, xval) {
            var retval = val;
            if (val === null || val === '' || typeof val == 'undefined') {
                retval = (typeof xval != 'undefined') ? xval : "-";
            }
            return retval;
        },
        routeModul: function (el) {
            HELPER.block();
            var page = $(el).data();
            var dataSend = {
                id: page.id,
                title: page.title
            };
            HELPER.ajax({
                url: `${APP_URL}MainPage/setModulMenu`,
                data: dataSend,
                complete: (response) => {
                    var sidebarList = new Array();
                    var myQ = new Queue();
                    myQ.enqueue(function (next) {
                        if (response.menu.success) {
                            var sidebarGroup = null;
                            // set selected modules title
                            sidebarList.push([`
                                <ul class="menu-nav">
                                <li class="menu-section">
                                    <h4 class="menu-text">${page.title}</h4>
                                    <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                                </li>
                            `]);
                            // create sidebar menu list
                            $.each(response.menu.data, (i, v) => {
                                // set sidebar group
                                if (sidebarGroup != v.menu_group && v.menu_group) {
                                    sidebarList.push([`
                                        <li class="menu-section">
                                            <h4 class="menu-text">${v.menu_group}</h4>
                                            <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                                        </li>
                                    `]);
                                }
                                // set menu list
                                sidebarList.push([`
                                    <li class="menu-item menu-sidebar-item menu-sidebar-item-${v.menu_id}" aria-haspopup="true">
                                        <a href="javascript:void(0);" onclick="HELPER.loadPage(this, 'sidebar')"
                                            data-id="${v.menu_id}" class="menu-link sidebar-menu-link">
                                            <span class="svg-icon menu-icon">
                                                ${(v.menu_icon) ? v.menu_icon : ""}
                                            </span>
                                            <span class="menu-text">${v.menu_title}</span>
                                        </a>
                                    </li>
                                `]);
                                // renew sidebar group
                                sidebarGroup = v.menu_group;
                            });
                            sidebarList.push([`</ul>`]);
                            // append to sidebar
                            $(`#kt_aside_menu`).html(sidebarList.join(''));
                        } else {
                            HELPER.showMessage({
                                'success': response.success,
                                'title': 'Info',
                                'message': `Modul tidak ditemukan, silahkan hubungi administrator.`
                            });
                        }
                        // set modul active
                        $(`.menu-modul-item`).removeClass('menu-item-active');
                        $(`.menu-modul-item-${page.id}`).addClass('menu-item-active');
                        next();
                    }, '1st').enqueue(function (next) {
                        // reset sub menu list
                        $(`.page-menusub`).html('');
                        // reset page title
                        $(`.page-title`).html('');
                        // reset page breadcrumb
                        $(`.page-breadcrumb`).html('');
                        // reset page content view
                        $(`.page-content-view`).html('');

                        next()
                    }, '2nd').enqueue(function (next) {
                        HELPER.unblock(500);
                    }, '3rd').dequeueAll()
                }
            });
        },
        loadPage: function (el, type) {
            var page = $(el).data();
            var dataSend = {
                id: page.id
            };
            // set menu id
            menuid = page.id
            HELPER.ajax({
                url: `${APP_URL}MainPage/getPage`,
                data: dataSend,
                complete: (response) => {
                    // check session
                    if (!response.signined) {
                        window.location.reload();
                    } else {
                        var setMenuSub = new Array();
                        var setBreadcrumb = new Array();
                        var myQ = new Queue();
                        myQ.enqueue(function (next) {
                            // create sub menu list & breadcrumb
                            if (response.menusub) {
                                setMenuSub.push([`
                                    <ul
                                        class="nav nav-pills"
                                        style="background-color: #EAEEF2; border-radius: 5px;"
                                    >
                                `]);
                                $.each(response.menusub, (i, v) => {
                                    const isActiveMenu = (v.id === response.id) ? 'active' : '';
                                    setMenuSub.push([`
                                        <li class="nav-item mr-0">
                                            <a
                                                href="javascript:void(0);"
                                                class="nav-link sidebar-menu-link ${isActiveMenu}"
                                                onclick="HELPER.loadPage(this, 'menusub')"
                                                data-id="${v.id}"
                                                id="btn-menu"
                                            >
                                                ${(v.icon) ? `
                                                    <span class="nav-icon">
                                                        ${v.icon}
                                                    </span>
                                                `: ``}
                                                <span class="nav-text">${v.title}</span>
                                            </a>
                                        </li>
                                    `]);
                                });
                                setMenuSub.push([`</ul>`]);
                            }
                            // create breadcrumb
                            $.each(response.breadcrumb, (i, v) => {
                                var classItem = ((response.breadcrumb.length - 1) == i) ? "text-light" : "text-muted";
                                setBreadcrumb.push([`
                                    <li class="breadcrumb-item ${classItem}">
                                        <a href="javascript:void(0);" class="${classItem}">
                                            ${v}
                                        </a>
                                    </li>
                                `]);
                            });

                            next();
                        }, '1st').enqueue(function (next) {
                            // set active menu
                            $(`.menu-sidebar-item`).removeClass('menu-item-active');
                            if (response.level > 1) {
                                // set active for parent menu
                                $(`.menu-sidebar-item-${response.parentId}`).addClass('menu-item-active');
                            } else {
                                // set active for current menu
                                $(`.menu-sidebar-item-${response.id}`).addClass('menu-item-active');
                            }
                            // set sub menu list
                            $(`.page-menusub`).html(setMenuSub.join(''));
                            // set page title
                            $(`.page-title`).html(`${response.title} <small>${(response.remark) ? response.remark : ""}</small>`);
                            // set page breadcrumb
                            $(`.page-breadcrumb`).html(setBreadcrumb.join(''));
                            // set page content view

                            $(`.page-content-view`).find('script').remove();
                            $(`.page-content-view`).html(atob(response.view));

                            initOwlCarousel();
                            next();
                        }, '2nd').enqueue(function (next) {
                            if (type == 'sidebar') {
                                $(`#btn-menu`).first().click();
                            }
                            HELPER.unblock(500);
                        }, '3rd').dequeueAll()
                    }
                },
                error: (error) => {
                    if (error.status == 403) {
                        bootbox.alert("Anda tidak memiliki izin untuk mengakses aplikasi.", () => {
                            window.location.reload();
                        });
                    }
                },
            });
        },
        initTable: function (config) {
            config = $.extend(true, {
                el: '',
                multiple: false,
                sorting: 'asc',
                index: 1,
                force: false,
                responsive: false,
                parentCheck: 'checkAll',
                childCheck: 'checkbox',
                clickable: true,
                stateSave: true,
                data: {
                    csrf_spi: $.cookie('csrf_lock_spi'),
                },
                filterColumn: {
                    state: false,
                    exceptionIndex: []
                },
            }, config);
            var xdefault =
            {
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
                    "lengthMenu": "_MENU_ entries",
                    "search": "Pencarian:",
                    "sProcessing": "<img src='./assets/images/loading.gif'>",
                    "zeroRecords": "No matching records found"
                },
                "aoColumnDefs": [{
                    "bSortable": false,
                    "bSearchable": false,
                    "aTargets": [0],
                }],
                "aaSorting": [
                    [config.index, config.sorting]
                ],
                fnDrawCallback: function (oSettings) {
                    $('thead').find('th').css({ 'text-align': 'center' });
                    if (config.multiple === false) {
                        $('tbody').find('tr').each(function (i, v) {
                            $('td:eq(0)', v).css({ 'text-align': 'center' });
                            if (config.clickable === true) {
                                $(v).addClass('clickable');
                            }
                        })

                        $('.row_selected').removeClass('row_selected');
                        if (config.clickable === true) {
                            $("#" + config.el + " tr").css('cursor', 'pointer');
                            $("#" + config.el + " tbody tr").each(function (i, v) {
                                $(v).on('click', function () {
                                    if (oSettings.aoData.length > 0) {
                                        $(v).addClass('row_selected');
                                        if ($(this).hasClass('selected')) {
                                            $(v).removeClass('selected');
                                            $('.checkbox').prop('checked', false);
                                            $('input[name=checkbox]', v).prop('checked', false);
                                            $('.disable').attr('disabled', true);
                                            $('.row_selected').removeClass('row_selected');
                                        } else {
                                            $('.checkbox').prop('checked', false);
                                            $(".selected").removeClass('selected');
                                            $('#' + config.el + '.dataTable tbody tr.selected').removeClass('selected');
                                            $(v).addClass('selected');
                                            $('.row_selected').removeClass('row_selected');
                                            $(v).addClass('row_selected');
                                            $('input[name=checkbox]', v).prop('checked', true);
                                            $('.disable').attr('disabled', false);
                                        }
                                    }
                                });
                            });
                        }
                    } else {
                        $('tbody').find('tr').each(function (i, v) {
                            $(v).addClass('clickable');
                        })
                        var cnt = 0;
                        $("#" + config.el + " tr").css('cursor', 'pointer');
                        $("#" + config.el + " tbody tr").each(function (i, v) {
                            $(v).on('click', function () {
                                if ($(this).hasClass('selected')) {
                                    --cnt;
                                    $(v).removeClass('selected');
                                    $(v).removeAttr('checked');
                                    $('input[name=checkbox]', v).prop('checked', false);
                                    $(v).removeClass('row_selected');
                                } else {
                                    ++cnt;
                                    $('input[name=checkbox]', v).prop('checked', true);
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
                                    $("#" + config.el + " tbody tr").each(function (i, v) {
                                        $(v).addClass('selected');
                                        $(v).addClass('row_selected');
                                    });
                                });
                                $('.' + config.parentCheck).addClass('selected');
                                $('.disable').attr('disabled', false);
                            } else {
                                $('.' + config.childCheck).each(function () {
                                    this.checked = false;
                                    $("#" + config.el + " tbody tr").each(function (i, v) {
                                        $(v).removeClass('row_selected');
                                        $(v).removeClass('selected');
                                        $(v).removeAttr('checked');
                                    });
                                });
                                $('.disable').attr('disabled', true);
                            }
                        });

                        $('th').click(function (i, v) {
                            if ($(this).hasClass('sorting_disabled')) { } else {
                                $("#" + config.el + " tbody tr").each(function (i2, v2) {
                                    $(v2).removeClass('row_selected');
                                    $(v2).removeClass('selected');
                                    $(v2).removeAttr('checked');
                                });
                                $('.' + config.parentCheck).removeClass('selected');
                                $('.' + config.parentCheck).prop('checked', false);
                            }
                        })
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
                    // 'data': $.extend(config.data, {csrf_spi: $.cookie('csrf_lock_spi')}), debrecate csrf
                    data: function (d) {
                        d.csrf_spi = $.cookie('csrf_lock_spi');
                        d.data = config.data;
                    }
                },
            };
            /*add input filter column*/
            if (config.filterColumn.state) {
                $("#" + config.el + ' tfoot').remove();
                $("#" + config.el).append('<tfoot>' + $("#" + config.el + ' thead').html() + '</tfoot>');
            }

            var el = $("#" + config.el);
            if (!config.force) {
                var dt = $(el).dataTable($.extend(true, xdefault, config));
            } else {
                var dt = $(el).dataTable(config);
            }

            /*initiate filter column*/

            if (config.filterColumn.state) {
                $('#' + config.el + ' tfoot th').each(function (i, v) {
                    var title = 'Enter untuk cari';
                    var kelas = (typeof $(v).data('type') == 'undefined') ? '' : $(v).data('type')
                    if (i > 0 && $.inArray(i, config.filterColumn.exceptionIndex) == -1) {
                        $(v).html('<input type="text" placeholder=": ' + title + '" class="form-control search ' + kelas + '" />');
                    } else {
                        $(v).html(' ');
                    }
                });
                $('#' + config.el).DataTable().columns().every(function (i, v) {
                    var that = this;

                    $('input', this.footer()).on('keyup', function (event) {
                        if (event.keyCode == 13 || event.which == 13) {
                            if (that.search() !== this.value) {
                                that
                                    .column(i)
                                    .search(this.value)
                                    .draw();
                            }
                        }
                    });

                });
            }

            // Sort by columns 1 and 2 and redraw
            /* table
                .order( [ 1, 'asc' ], [ 2, 'asc' ] )
                .draw();*/

            $(el).addClass('table-condensed').removeClass('table-striped').addClass('compact nowrap hover dt-head-left');
            return dt;
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
                data: {
                    csrf_spi: $.cookie('csrf_lock_spi'),
                },
                filterColumn: {
                    state: false,
                    exceptionIndex: []
                },
                callbackClick: function (row, data, seleced) { },
                detailClickCallback: function () { }
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
                // set details button
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
                    // set header checkbox
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
                    // set callback on click details
                    if (config.tabDetails) {
                        // set sub click callback
                        $(`.span-child`).click((e) => {
                            var target = e.currentTarget;
                            var rowShown = $(target).hasClass('shown');
                            if (rowShown) {
                                var dataTarget = $(target).data();
                                var runClickSub = config.detailClickCallback(
                                    JSON.parse(atob(dataTarget.record)), // row data
                                    dataTarget.row, // row button
                                    `div-child-${dataTarget.row}`, // div id target
                                );
                            }
                        });
                    }
                    // set role
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
                        d.csrf_spi = $.cookie('csrf_lock_spi');
                        d.data = config.data;
                    }
                },
            };

            // trigger datatable
            var el = $("#" + config.el);
            if (!config.force) {
                var dt = $(el).dataTable($.extend(true, xdefault, config));
            } else {
                var dt = $(el).dataTable(config);
            }

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
                // if button wasn't open
                if (!rowShown) {
                    // change label color
                    $(el).removeClass('label-success');
                    $(el).addClass('label-danger');
                    // append class shown
                    $(el).addClass('shown');
                    // change arrow direction
                    $(el).find('i').removeClass('flaticon2-next');
                    $(el).find('i').addClass('flaticon2-down');
                } else {
                    // change label color
                    $(el).removeClass('label-danger');
                    $(el).addClass('label-success');
                    // remove class shown
                    $(el).removeClass('shown');
                    // change arrow direction
                    $(el).find('i').removeClass('flaticon2-down');
                    $(el).find('i').addClass('flaticon2-next');
                }
                next();
            }, '1m').enqueue(function (next) {
                var row = $(el).parent().closest('tr');
                var tds = row.find('td');
                // append child
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
                    // remove child
                    $(`tr.child-${rowData.row}`).remove();
                }
            }, 'end').dequeueAll();
        },

        initTable2: function (config) {
            config = $.extend(true, {
                el: '',
                multiple: false,
                force: false,
                data: {
                    csrf_spi: $.cookie('csrf_lock_spi'),
                }
            }, config);
            var xdefault =
            {
                "aLengthMenu": [
                    [5, 10, 15, 25, 50, 100],
                    [5, 10, 15, 25, 50, 100]
                ],
                "iDisplayLength": 10,
                "sPaginationType": "bootstrap_full_number",
                "bProcessing": true,
                'bServerSide': true,
                // "bAutoWidth": true,
                // "scrollCollapse": true,
                "pagingType": "simple_numbers",
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
                    "sProcessing": "<img src='./assets/images/loading.gif'>",
                    "zeroRecords": "No matching records found"
                },
                "aoColumnDefs": [{
                    "bSortable": false,
                    "bSearchable": false,
                    "aTargets": [0],
                }],
                "aaSorting": [],
                fnDrawCallback: function (oSettings) {

                },
                fnRowCallback: function (row, data, index, rowIndex) {

                },
                fnInitComplete: function (oSettings, data) {

                },

                "ajax": {
                    'url': config.url,
                    'type': 'POST',
                    'data': config.data
                },
            };

            var el = $("#" + config.el);
            if (!config.force) {
                var dt = $(el).dataTable($.extend(true, xdefault, config));
            } else {
                var dt = $(el).dataTable(config);
            }

            $(el).addClass('table-condensed').removeClass('table-striped').addClass('compact nowrap hover dt-head-left');
            return dt;
        },

        aksi: function (role_update = '', role_delete = '', role_detail = '', role_detail_2 = '', role_detail_3 = '') {
            tootlip = (typeof role_detail.tooltip != "undefined") ? role_detail.tooltip : "";
            tootlip2 = (typeof role_detail_2.tooltip != "undefined") ? role_detail_2.tooltip : "";
            tootlip3 = (typeof role_detail_3.tooltip != "undefined") ? role_detail_3.tooltip : "";
            var detail = (role_detail != '') ? '<a href="javascript:;" ' + ((tootlip != "") ? "title='" + tootlip + "'" : "") + ' data-roleable="true" data-role="' + role_detail.role + '" onclick="' + role_detail.action + '(this)" class="' + ((role_detail.role == '') ? '' : 'hide') + ' xremove_button btn-radius btn btn-xs ' + role_detail.btn_color + '"> <i class="' + role_detail.icon + '"></i> ' + role_detail.title + ' </a>' : '';
            var detail_2 = (role_detail_2 != '') ? '<a href="javascript:;" ' + ((tootlip2 != "") ? "title='" + tootlip2 + "'" : "") + ' data-roleable="true" data-role="' + role_detail_2.role + '" onclick="' + role_detail_2.action + '(this)" class="' + ((role_detail.role == '') ? '' : 'hide') + ' xremove_button btn-radius btn btn-xs ' + role_detail_2.btn_color + '"> <i class="' + role_detail_2.icon + '"></i> ' + role_detail_2.title + ' </a>' : '';
            var detail_3 = (role_detail_3 != '') ? '<a href="javascript:;" ' + ((tootlip3 != "") ? "title='" + tootlip3 + "'" : "") + ' data-roleable="true" data-role="' + role_detail_3.role + '" onclick="' + role_detail_3.action + '(this)" class="' + ((role_detail.role == '') ? '' : 'hide') + ' xremove_button btn-radius btn btn-xs ' + role_detail_3.btn_color + '"> <i class="' + role_detail_3.icon + '"></i> ' + role_detail_3.title + ' </a>' : '';
            return '<a href="javascript:;" data-roleable="true" data-role="' + role_update + '" onclick="onEdit(this)" class="hide xremove_button btn-radius btn btn-xs btn-warning"> <i class="fa fa-edit"></i> Ubah </a>&nbsp;' +
                '<a href="javascript:;" data-roleable="true" data-role="' + role_delete + '" onclick="onDelete(this)" class="hide xremove_button btn-radius btn btn-xs btn-danger"> <i class="fa fa-trash-o"></i> Hapus </a> ' + detail + detail_2 + detail_3;
        },

        getRecord: function (el) {
            return JSON.parse(atob($($($(el).parents('tr').children('td')[0]).children('input')).data('record')));
        },

        getRowData: function (config) {
            var xdata = $.parseJSON(atob($($(config.data[0])[2]).data('record')));
            return xdata;
        },

        getRowDataMultiple: function (config) {
            var xdata = $.parseJSON(atob($(config.data[0]).data('record')));
            return xdata;
        },

        toggleForm: function (config) {
            config = $.extend(true, {
                speed: 'fast',
                easing: 'swing',
                callback: function () { },
                tohide: 'data_table',
                toshow: 'data_form',
                animate: null,
            }, config);

            if (config.animate !== null) {
                if (config.animate === 'fade') {
                    $("." + config.tohide).fadeOut(config.speed, function () {
                        $("." + config.toshow).fadeIn(config.speed, config.callback).css('display', 'block');
                    });
                }
                else if (config.animate === 'toogle') {
                    $("." + config.tohide).fadeToggle(config.speed, function () {
                        $("." + config.toshow).fadeToggle(config.speed, config.callback).css('display', 'block');
                    });
                }
                else if (config.animate === 'slide') {
                    $("." + config.tohide).slideUp(config.speed, function () {
                        $("." + config.toshow).slideDown(config.speed, config.callback).css('display', 'block');
                    });
                }
                else {
                    $("." + config.tohide).fadeOut(config.speed, function () {
                        $("." + config.toshow).fadeIn(config.speed, config.callback).css('display', 'block');
                    });
                }
            }
            else {
                $("." + config.tohide).fadeOut(config.speed, function () {
                    $("." + config.toshow).fadeIn(config.speed, config.callback).css('display', 'block');
                });
            }

            $('html,body').animate({
                scrollTop: 0 /*pos + (offeset ? offeset : 0)*/
            }, 'slow');
        },

        refresh: function (config) {
            config = $.extend(true, {
                table: null
            }, config);

            if (config.table !== null) {
                if (config.table.constructor === Object) {
                    $.each(config.table, function (i, v) {
                        $("#" + v).dataTable().fnReloadAjax();
                    });
                }
                else if (config.table.constructor === Array) {
                    $.each(config.table, function (i, v) {
                        $("#" + v).dataTable().fnReloadAjax();
                    });
                }
                else {
                    $("#" + config.table).dataTable().fnReloadAjax();
                }
            }
            $('.disable').attr('disabled', true);
        },

        back: function (config) {
            config = $.extend(true, {
                speed: 'fast',
                easing: 'swing',
                callback: function () { },
                tohide: 'form_data',
                toshow: 'table_data',
                animate: null,
                loadPage: true,
                table: null,
            }, config);

            $.when(function () {
                if (config.table !== null) {
                    if (config.table.constructor === Object) {
                        $.each(config.table, function (i, v) {
                            $("#" + v).dataTable().fnReloadAjax();
                        });
                    }
                    else if (config.table.constructor === Array) {
                        $.each(config.table, function (i, v) {
                            $("#" + v).dataTable().fnReloadAjax();
                        });
                    }
                    else {
                        $("#" + config.table).dataTable().fnReloadAjax();
                    }
                }

                if (config.animate !== null) {
                    if (config.animate === 'fade') {
                        $("." + config.tohide).fadeOut(config.speed, function () {
                            $("." + config.toshow).fadeIn(config.speed, config.callback)
                        });
                    }
                    else if (config.animate === 'toogle') {
                        $("." + config.tohide).fadeToggle(config.speed, function () {
                            $("." + config.toshow).fadeToggle(config.speed, config.callback)
                        });
                    }
                    else if (config.animate === 'slide') {
                        $("." + config.tohide).slideUp(config.speed, function () {
                            $("." + config.toshow).slideDown(config.speed, config.callback);
                        });
                    }
                    else {
                        $("." + config.tohide).fadeOut(config.speed, function () {
                            $("." + config.toshow).fadeIn(config.speed, config.callback)
                        });
                    }
                }
                else {
                    $("." + config.tohide).fadeOut(config.speed, function () {
                        $("." + config.toshow).fadeIn(config.speed, config.callback)
                    });
                }
            }()).done(function () {
                if (config.loadPage === true) {
                    $("a.sidebar-menu-link[data-id='" + menuid + "']").trigger('click');
                }
            }());
        },

        reloadPage: function () {
            $("[data-menuid='" + menuid + "']").not('.md_notif').trigger('click');
        },

        decodeHtml: function (html) {
            var txt = document.createElement("textarea");
            txt.innerHTML = html;
            return txt.value;
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

            config.data.append('csrf_spi', $.cookie('csrf_lock_spi'));
            config = $.extend(true, {
                form: null,
                confirm: false,
                confirmIcon: 'warning',
                confirmTitle: 'Simpan Data',
                confirmMessage: 'Apakah anda yakin untuk menyimpan data tersebut?',
                data: $.extend($('[name=' + config.form + ']').serializeObject(), {
                    csrf_spi: $.cookie('csrf_lock_spi')
                }),
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
                // bootbox.confirm({
                //     title: "Informasi",
                //     message: "Apakah anda yakin akan menyimpan data tersebut?",
                //     buttons: {
                //         confirm: {
                //             label: '<i class="fa fa-check"></i> Ya',
                //             className: 'btn-success'
                //         },
                //         cancel: {
                //             label: '<i class="fa fa-times"></i> Tidak',
                //             className: 'btn-danger'
                //         }
                //     },
                //     callback: function (result) {
                //         if (result===true) {
                //             do_save(config);
                //         }
                //     }
                // });
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
                    data: $.extend(dataSend, { csrf_spi: $.cookie('csrf_lock_spi') }),
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
                    data: $.extend(dataSend, { csrf_spi: $.cookie('csrf_lock_spi') }),
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

        getDataFromTable: function (config) {
            config = $.extend(true, {
                table: null,
                multiple: false,
                callback: function (args) { }
            }, config);
            var data = '';
            var multidata = [];

            $("#" + config.table).find('input[name=checkbox]').each(function (key, value) {
                if ($(value).is(":checked")) {
                    if (config.multiple) {
                        multidata.push($.parseJSON(atob($(value).data('record'))));
                    } else {
                        data = $.parseJSON(atob($(value).data('record')));
                    }
                }
            });
            if (config.multiple) {
                config.callback(multidata);
            } else {
                config.callback(data);
            }
        },

        saveMultiple: function (config) {
            config = $.extend(true, {
                url: null,
                table: null,
                confirm: true,
                method: 'POST',
                data: null,
                message: true,
                callback: function (arg) { },
                success: function (arg) { },
                error: function (arg) { },
                complete: function (arg) { },
                cache: false,
                contentType: false,
                processData: false,
                xhr: null,
            }, config);

            var sentData = function (_config, data) {
                var dataSend = {};
                var localdataSend = {};
                var xdataSend = {};

                if (config.data === null) {
                    $.each(data.server, function (i, v) {
                        dataSend[i] = v;
                    });
                    xdataSend = dataSend;
                } else {
                    $.each(data.server, function (i, v) {
                        dataSend[i] = v;
                    });
                    $.each(data.local, function (i, v) {
                        localdataSend[i] = v;
                    });
                    xdataSend['server'] = dataSend;
                    xdataSend['data'] = localdataSend;
                }

                loadBlock('');
                $.ajax({
                    url: config.url,
                    data: $.extend(xdataSend, { csrf_spi: $.cookie('csrf_lock_spi') }),
                    type: config.method,
                    cache: config.cache,
                    /*contentType: config.contentTypes,
                    processData: config.processDatas,*/
                    xhr: (config.xhr === null) ? function () {
                        var myXhr = $.ajaxSettings.xhr();
                        return myXhr;
                    } : config.xhr,
                    success: function (response) {
                        if (config.message == false) {
                            config.success(response);
                        } else {
                            config.success(response);
                            HELPER.showMessage({
                                success: response.success,
                                message: response.message,
                                title: ((response.success) ? 'Sukses' : 'Gagal')
                            });
                        }
                    },
                    error: function (response, status, errorname) {
                        if (config.message == false) {
                            config.fail(response, status, errorname);
                        } else {
                            config.fail(response, status, errorname);
                            HELPER.showMessage({
                                success: false,
                                title: 'Gagal melakukan operasi',
                                message: errorname,
                            });
                        }
                    },
                    complete: function (response) {
                        var rsp = $.parseJSON(response.responseText);
                        config.callback(rsp.success, rsp.id, rsp.record, rsp.message, rsp);
                        unblock(1000);
                    },
                });
            }

            var data = [];
            var xdata = [];
            $("#" + config.table).find('input[name=checkbox]').each(function (key, value) {
                if ($(value).is(":checked")) {
                    var cek = null;
                    if ($(value).val().length == 32) {
                        cek = $(value).val();
                    } else {
                        var cek = $.parseJSON(atob($(value).data('record')));
                    }
                    data[key] = cek;
                }
                xdata['server'] = data;
                xdata['local'] = config.data;
            });
            if (xdata.server.length > 0) {
                if (config.confirm) {
                    bootbox.confirm({
                        title: "Informasi",
                        message: "Apakah anda yakin akan menyimpan data tersebut?",
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
                                sentData(config, xdata);
                            }
                        }
                    });
                }
                else {
                    sentData(config, xdata);
                }
            }
        },

        setRowDataTable: function (config) {
            HELPER.saveMultiple(config);
        },

        text_truncate: function (str, length = null, ending = null) {
            str = HELPER.nullConverter(str);
            if (length == null) {
                length = 100;
            }
            if (ending == null) {
                ending = '...';
            }
            if (str.length > length) {
                return str.substring(0, length - ending.length) + ending;
            } else {
                return str;
            }
        },

        textMore: function (config) {
            config = $.extend(true, {
                text: '-',
                length: 50,
                ending: '...',
                btn_text: 'Lihat banyak',
                btn_text_reverse: 'Lihat sedikit',
                reverse: false,
                fromReverse: false,
                showMore: true,
                from: 1,
                callbackClick: function () { },
            }, config);
            var str = HELPER.nullConverter(config.text)
            var btn_click = "";
            var btn_click_reverse = "";
            if (str.length > config.length) {
                try {
                    if (config.showMore) {
                        if (config.reverse) {
                            if (config.fromReverse) {
                                config.fromReverse = false;
                                btn_click = `<a href="javascript:void(0)" data-config="${btoa(JSON.stringify(config))}" onclick="HELPER.clickTextMore(this)" title="${config.btn_text_reverse}">${config.btn_text_reverse}</a>`;
                                str = config.text + " " + btn_click;
                            } else {
                                config.fromReverse = true;
                                var temp_str = HELPER.text_truncate(config.text, config.length, config.ending);
                                btn_click = `<a href="javascript:void(0)" data-config="${btoa(JSON.stringify(config))}" onclick="HELPER.clickTextMore(this)" title="${config.btn_text}">${config.btn_text}</a>`;
                                str = temp_str + " " + btn_click;
                            }
                        } else {
                            if (config.from) {
                                var temp_str = HELPER.text_truncate(config.text, config.length, config.ending);
                                btn_click = `<a href="javascript:void(0)" data-config="${btoa(JSON.stringify(config))}" onclick="HELPER.clickTextMore(this)" title="${config.btn_text}">${config.btn_text}</a>`;
                                str = temp_str + " " + btn_click;
                            } else {
                                str = config.text;
                            }
                        }
                    } else {
                        if (config.from) {
                            var temp_str = HELPER.text_truncate(config.text, config.length, config.ending);
                            str = temp_str;
                        } else {
                            str = config.text;
                        }
                    }
                } catch (e) {
                    // console.log(e);
                }
            }
            var temp_span = `${str}`;
            return temp_span;
        },

        findWhere: function (config) {
            config = $.extend(true, {
                object: [],
                prop: null,
                val: null,
                oneData: true
            }, config);
            // can only used if there's found any object
            if (config.object.length > 0) {
                var resultData = $.grep(config.object, function (obj) {
                    return obj[config.prop] == config.val;
                });
                return config.oneData ? resultData[0] : resultData;
            }
        },

        // function to find index of matches object
        findIndex: function (config) {
            config = $.extend(true, {
                object: [], // object to find
                prop: null, // property that used to define target object
                val: null, // value of property that used to define target object
            }, config);

            if (config.object.length > 0 && config.prop != null && config.val != null) {
                var indexToReplace = -1;
                $.each(config.object, (i, v) => {
                    if (v[config.prop] == config.val) {
                        indexToReplace = i;
                        return false;
                    }
                });

                return indexToReplace;
            }
        },

        clickTextMore: function (el) {
            if ($(el).data().hasOwnProperty('config')) { var config = JSON.parse(atob($(el).data('config'))); config.from = 0; $(el).parent().html(HELPER.textMore(config)) }
        },

        loadData: function (config) {
            config = $.extend(true, {
                debug: false,
                table: null,
                type: 'POST',
                url: null,
                server: false,
                data: null,
                fields: 'fields',
                before_load: function () { },
                after_load: function () { },
                callback: function (arg) { }
            }, config);
            config.before_load();
            loadBlock('Sedang menampilkan data');
            if (config.server === true) {
                var datalocal = [];
                $("#" + config.table).find('input[name=checkbox]').each(function (key, value) {
                    if ($(value).is(":checked")) {
                        datalocal = $.parseJSON(atob($(value).data('record')));
                        datalocal['id'] = datalocal[HELPER.fields[0]];
                        datalocal['data'] = config.data;

                        $.ajax({
                            url: config.url,
                            data: $.extend(datalocal, { csrf_spi: $.cookie('csrf_lock_spi') }),
                            type: config.type,
                            success: function (response) { },
                            complete: function (data_response) {
                                var response = $.parseJSON(data_response.responseText);
                                var dataserver = {};
                                if (response.constructor === Object) {
                                    dataserver = response;
                                }
                                else if (response.constructor === Array) {
                                    dataserver = response[0];
                                }
                                if (dataserver !== null) {
                                    $.when(function () {
                                        $.each(dataserver, function (i, v) {
                                            if ($("[name=" + i + "]").find("option:selected").length) {
                                                if (!$($("[name=" + i + "]")).data('select2')) {
                                                    $("[name=" + i + "]").val(v);
                                                }
                                                else {
                                                    $('[name="' + i + '"]').select2('val', v);
                                                }
                                            }
                                            else if ($("[name=" + i + "]").attr('type') == 'checkbox') {
                                                $('[name="' + i + '"][value="' + v + '"]').prop('checked', true);
                                            }
                                            else if ($("[name=" + i + "]").attr('type') == 'radio') {
                                                $('[name="' + i + '"][value="' + v + '"]').prop('checked', true);
                                            }
                                            else if ($("[name='" + i + "']").attr('type') == 'file') {
                                                $("[name=" + i + "]").val("");
                                            }
                                            else {
                                                $("[name=" + i + "]").val(html_entity_decode(v))
                                            }
                                        })

                                        if (Object.keys(datalocal).length > 0) {
                                            $.each(datalocal, function (i, v) {
                                                if ($("[name=" + i + "]").attr('type') == 'checkbox') {
                                                    $('[name="' + i + '"][value="' + v + '"]').prop('checked', true);
                                                }
                                                else if ($("[name=" + i + "]").attr('type') == 'radio') {
                                                    $('[name="' + i + '"][value="' + v + '"]').prop('checked', true);
                                                }
                                                else {
                                                    $("[name=" + i + "]").val(html_entity_decode(v))
                                                }
                                            })
                                        }
                                        config.callback(datalocal, dataserver);
                                        return dataserver;
                                    }()).done(unblock(100))
                                }
                                else {
                                    $.when(unblock(100)).then(function () {
                                        HELPER.showMessage({
                                            title: 'Informasi',
                                            message: 'Anda belum memilih data pada tabel...!',
                                            image: './assets/img/information.png',
                                            time: 2000
                                        })
                                    }());
                                }
                            }
                        });
                        if (config.debug) { }
                    }
                });
            }
            else {
                var data = null;
                $("#" + config.table).find('input[name=checkbox]').each(function (key, value) {
                    if ($(value).is(":checked")) {
                        data = $.parseJSON(atob($(value).data('record')));
                        if (config.debug) { }
                    }
                });
                if (data !== null) {
                    $.when(function () {
                        HELPER[config.fields].forEach(function (v, i, a) {
                            if ($("[name=" + v + "]").find("option:selected").length) {
                                if (!$($("[name=" + v + "]")).data('select2')) {
                                    $("[name=" + v + "]").val(data[v]);
                                }
                                else {
                                    $('[name="' + v + '"]').select2('val', data[v]);
                                }
                            }
                            else if ($("[name=" + v + "]").attr('type') == 'checkbox') {
                                $('[name="' + v + '"][value="' + data[v] + '"]').prop('checked', true);
                            }
                            else if ($("[name=" + v + "]").attr('type') == 'radio') {
                                $('[name="' + v + '"][value="' + data[v] + '"]').prop('checked', true);
                            } else {
                                $("[name=" + v + "]").val(html_entity_decode(data[v]))
                            }
                        });
                        config.callback(data);
                        return data;
                    }()).done(unblock(500));
                } else {
                    $.when(unblock(500)).then(function () {
                        HELPER.showMessage({
                            title: 'Informasi',
                            message: 'Anda belum memilih data pada tabel...!',
                            image: './assets/img/information.png',
                            time: 2000
                        })
                    }());
                }
            }

        },

        ajaxCombox: function (config) {
            config = $.extend(true, {
                el: null,
                limit: 30,
                url: null,
                tempData: null,
                data: {},
                placeholder: null,
                displayField: null,
                displayField2: null,
                displayField3: null,
                allowClear: true,
                grouped: false,
                selected: null,
                multiple: false,
                callback: function (res) { }
            }, config);
            var myQ = new Queue();
            myQ.enqueue(function (next) {
                $(config.el).select2({
                    allowClear: config.allowClear,
                    multiple: config.multiple,
                    width: '100%',
                    query: function (query) {
                        var dataQuery = {
                            q: query.term, // search term
                            page: query.page,
                            data: config.data,
                            limit: config.limit,
                            selectedId: (config.selected != null ? config.selected.id : null),
                            csrf_spi: $.cookie('csrf_lock_spi'),
                        };
                        HELPER.ajax({
                            url: config.url,
                            data: dataQuery,
                            success: function (data) {
                                var more = (query.page * 10) < data.total_count;
                                query.callback({
                                    results: data.items,
                                    more: more
                                });
                            }
                        });
                    },
                    placeholder: (config.placeholder ? config.placeholder : '- Choose -'),
                    minimumInputLength: 0,
                    // templateSelection: function (data, container) {
                    // for select2 3.5.1
                    formatSelection: function (data, container) {
                        $(data.element).attr('data-temp', data.saved);
                        if (config.tempData != null) {
                            $.each(config.tempData, function (i, v) {
                                $(data.element).attr('data-' + v.key, v.val);
                            })
                        }
                        var display = data.text;
                        if (config.displayField != null && data[config.displayField]) {
                            if (config.grouped && config.displayField2 != null) {
                                if (config.displayField3 != null) {
                                    display = data[config.displayField] + " - " + data[config.displayField2] + " ( " + data[config.displayField3] + " )"
                                } else {
                                    display = data[config.displayField] + " - " + data[config.displayField2]
                                }
                            } else {
                                display = data[config.displayField];
                            }
                        }
                        return display;
                    },
                    // templateResult: function (data) {
                    // for select2 3.5.1
                    formatResult: function (data) {
                        if (data.loading) {
                            return data.text;
                        }

                        var display = data.text;
                        if (config.displayField != null) {
                            if (config.grouped && config.displayField2 != null) {
                                if (config.displayField3 != null) {
                                    display = data[config.displayField] + " - " + data[config.displayField2] + " ( " + data[config.displayField3] + " )"
                                } else {
                                    display = data[config.displayField] + " - " + data[config.displayField2]
                                }
                            } else {
                                display = data[config.displayField];
                            }
                        }

                        return display;
                    },
                    initSelection: function (element, callback) {
                        // the input tag has a value attribute preloaded that points to a preselected movie's id
                        // this function resolves that id attribute to an object that select2 can render
                        // using its formatResult renderer - that way the movie name is shown preselected
                        var id = $(element).val();
                        if (id !== "") {
                            HELPER.ajax({
                                url: config.url,
                                data: { selectedId: id },
                                complete: function (data) {
                                    callback(data);
                                }
                            });
                        }
                    }
                });
                next()
            }, 'pertama').enqueue(function (next) {
                if (config.selected != null) {
                    if (config.multiple) {
                        if (config.selected.length > 0) {
                            var selectedData = [];
                            $.each(config.selected, function (i, v) {
                                selectedData.push({
                                    id: v.id,
                                    text: v.name
                                });
                                // var option = new Option(v.name, v.id, true, true);
                                // $(config.el).append(option).trigger('change');
                            });
                            $(config.el).select2('data', selectedData);
                        }
                    } else {
                        HELPER.ajax({
                            url: config.url,
                            data: { selectedId: config.selected },
                            complete: function (data) {
                                // set value
                                $(config.el).select2('data', {
                                    id: config.selected,
                                    text: data.items[0].text
                                });
                            }
                        });
                    }
                }
            }, 'kedua').dequeueAll()
        },

        // for newest jquery
        ajaxCombobox: function (config) {
            config = $.extend(true, {
                el: null,
                limit: 30,
                url: null,
                tempData: null,
                data: {},
                placeholder: '-Choose-',
                displayField: null,
                displayField2: null,
                displayField3: null,
                grouped: false,
                selected: null,
                width: "100%",
                allowClear: false,
                multiple: false,
                callback: function (res) { }
            }, config);
            var myQ = new Queue();

            myQ.enqueue(function (next) {
                $(config.el).select2({
                    allowClear: (config.multiple) ? false : config.allowClear,
                    width: config.width,
                    multiple: config.multiple,
                    placeholder: config.placeholder,
                    minimumInputLength: 0,
                    ajax: {
                        url: config.url,
                        dataType: 'json',
                        type: 'post',
                        data: function (params) {
                            return {
                                csrf_spi: $.cookie('csrf_lock_spi'),
                                q: params.term, // search term
                                page: params.page,
                                limit: config.limit,
                                fdata: config.data,
                                // selectedId: (config.selected != null ? config.selected.id : null)
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.items,
                                pagination: {
                                    more: (params.page * config.limit) < data.total_count
                                }
                            };
                        },
                        cache: true
                    },
                    templateSelection: function (data, container) {
                        $(data.element).attr('data-temp', data.saved);
                        if (config.tempData != null) {
                            $.each(config.tempData, function (i, v) {
                                $(data.element).attr('data-' + v.key, v.val);
                            })
                        }
                        var display = data.text;
                        if (config.displayField != null && data[config.displayField]) {
                            if (config.grouped && config.displayField2 != null) {
                                if (config.displayField3 != null) {
                                    display = data[config.displayField] + " - " + data[config.displayField2] + " ( " + data[config.displayField3] + " )"
                                } else {
                                    display = data[config.displayField] + " - " + data[config.displayField2]
                                }
                            } else {
                                display = data[config.displayField];
                            }
                        }
                        return display;
                    },
                    templateResult: function (data) {
                        if (data.loading) {
                            return data.text;
                        }

                        var display = data.text;
                        if (config.displayField != null) {
                            if (config.grouped && config.displayField2 != null) {
                                if (config.displayField3 != null) {
                                    display = data[config.displayField] + " - " + data[config.displayField2] + " ( " + data[config.displayField3] + " )"
                                } else {
                                    display = data[config.displayField] + " - " + data[config.displayField2]
                                }
                            } else {
                                display = data[config.displayField];
                            }
                        }

                        return display;
                    }
                });
                next()
            }, 'pertama').enqueue(function (next) {
                if (config.multiple && config.selected) {
                    if (config.selected.length == 1) {
                        var option = new Option(config.selected[0].name, config.selected[0].id, true, true);
                        $(config.el).append(option).trigger('change');
                    } else {
                        config.selected.forEach(function (selection) {
                            var option = new Option(selection.name, selection.id, true, true);
                            $(config.el).append(option);
                        });
                        $(config.el).trigger('change');
                    }
                } else if (config.selected) {
                    var option = new Option(config.selected.name, config.selected.id, true, true);
                    $(config.el).append(option).trigger('change');
                }
                next()
            }, 'kedua').dequeueAll()
        },

        createCombo: function (config) {
            config = $.extend(true, {
                el: null,
                valueField: null,
                valueGroup: null,
                displayField: null,
                displayField2: null,
                displayField3: null,
                url: null,
                grouped: false,
                withNull: true,
                data: null,
                chosen: false,
                value: null,
                callback: function () { }
            }, config);

            var myQueue = new Queue();
            myQueue.enqueue(function (next) {
                if (config.url !== null) {
                    $.ajax({
                        url: config.url,
                        data: $.extend(config.data, { csrf_spi: $.cookie('csrf_lock_spi') }),
                        type: 'POST',
                        complete: function (response) {
                            var html = (config.withNull === true) ? "<option value>-Pilih-</option>" : "";
                            var data = $.parseJSON(response.responseText);
                            if (data.success) {
                                $.each(data.data, function (i, v) {
                                    if (config.grouped) {
                                        if (config.displayField3 != null) {
                                            html += "<option value='" + v[config.valueField] + "'>" + v[config.displayField2] + " - " + v[config.displayField] + " ( " + v[config.displayField3] + " ) " + "</option>";
                                        } else {
                                            html += "<option value='" + v[config.valueField] + "'>" + v[config.displayField2] + " - " + v[config.displayField] + "</option>";
                                        }
                                    } else {
                                        html += "<option value='" + v[config.valueField] + "'>" + v[config.displayField] + "</option>";
                                    }
                                });
                                if (config.el.constructor === Array) {
                                    $.each(config.el, function (i, v) {
                                        $('#' + v).html(html);
                                    })
                                } else {
                                    $('#' + config.el).html(html);
                                }
                                if (config.chosen) {
                                    if (config.el.constructor === Array) {
                                        $.each(config.el, function (i, v) {
                                            $('#' + v).addClass(v);
                                            $('.' + v).select2({
                                                allowClear: true,
                                                dropdownAutoWidth: true,
                                                width: '100%',
                                                placeholder: "-Pilih-",
                                            });
                                        })
                                    } else {
                                        $('#' + config.el).addClass(config.el);
                                        $('.' + config.el).select2({
                                            allowClear: true,
                                            dropdownAutoWidth: true,
                                            width: '100%',
                                            placeholder: "-Pilih-",
                                        });
                                    }
                                }
                            }
                            config.callback(data);
                        }
                    });
                } else {
                    var response = { success: false, message: 'Url kosong' };
                    config.callback(response);
                }
                next()
            }, 'first').enqueue(function (next) {
                if (config.value != null) {
                    setTimeout(() => {
                        if (config.chosen) {
                            $('#' + config.el).val(config.value).trigger('change.select2');
                        } else {
                            $('#' + config.el).val(config.value).trigger('change');
                        }
                    }, 600);
                }
            }, 'second').dequeueAll();
        },

        createGroupCombo: function (config) {
            config = $.extend(true, {
                el: null,
                valueField: null,
                valueGroup: null,
                displayField: null,
                url: null,
                grouped: false,
                withNull: true,
                data: null,
                chosen: false,
                callback: function () { }
            }, config);

            if (config.url !== null) {
                $.ajax({
                    url: config.url,
                    data: $.extend(config.data, {
                        csrf_spi: $.cookie('csrf_lock_spi'),
                        id: config.valueField,
                        id_group: config.valueGroup,
                    }),
                    type: 'POST',
                    complete: function (response) {
                        var data = $.parseJSON(response.responseText);
                        var html = (config.withNull === true) ? "<option value>-Pilih-</option>" : "";
                        if (data.success) {
                            if (config.grouped) {
                                $.each(data.data, function (i, v) {
                                    html += '<optgroup label="' + i + '" style="font-wight:bold;">';
                                    $.each(v, function (i2, v2) {
                                        html += '<option value="' + v2[config.valueField] + '">' + v2[config.displayField] + '</option>';
                                    });
                                    html += '</optgroup>';
                                });
                            } else {

                            }

                            if (config.el.constructor === Array) {
                                $.each(config.el, function (i, v) {
                                    $('#' + v).html(html);
                                })
                            } else {
                                $('#' + config.el).html(html);
                            }

                            if (config.chosen) {
                                if (config.el.constructor === Array) {
                                    $.each(config.el, function (i, v) {
                                        $('#' + v).addClass(v);
                                        $('select.' + v).select2({
                                            allowClear: true,
                                            dropdownAutoWidth: true,
                                            // width: 'auto',
                                            placeholder: "-Pilih-",
                                        });
                                    })
                                } else {
                                    $('#' + config.el).addClass(config.el);
                                    $('select.' + config.el).select2({
                                        allowClear: true,
                                        dropdownAutoWidth: true,
                                        // width: 'auto',
                                        placeholder: "-Pilih-",
                                    });
                                }
                            }

                        }
                        config.callback(data);
                    }
                });
            } else {
                var response = { success: false, message: 'Url kosong' };
                config.callback(response);
            }
        },

        createChangeCombo: function (config) {
            config = $.extend(true, {
                el: null,
                data: null,
                url: null,
                reset: null,
                callback: function () { }
            }, config);

            $('#' + config.el).change(function () {
                var id = $(this).val();
                var data = {};
                if (config.reset !== null) {
                    $('[name="' + config.reset + '"]').val("").select2("");
                    // $("[name="+config.reset+"]").select2().val("");
                } if (config.data === null) {
                    data['id'] = id;
                } else {
                    data = config.data;
                    data['id'] = id;
                }
                $.ajax({
                    url: config.url,
                    // data: data,
                    data: $.extend(data, { csrf_spi: $.cookie('csrf_lock_spi') }),
                    type: 'POST',
                    complete: function (response) {
                        var rsp = $.parseJSON(response.responseText);
                        config.callback(rsp.success, id, rsp.data, rsp.total, response);
                    },
                    callback: function (arg) { }
                });
            });
        },

        setChangeCombo: function (config) {
            config = $.extend(true, {
                el: null,
                data: null,
                valueField: null,
                displayField: null,
                displayField2: null,
                grouped: false,
                withNull: true,
                idMode: false,
                chosen: false,
                allowClear: false,
                value: null
            }, config);

            if (config.idMode === true) {
                var html = (config.withNull === true) ? "<option value>-Pilih-</option>" : "";
                $.each(config.data, function (i, v) {
                    if (config.grouped) {
                        if (config.displayField3 != null) {
                            html += "<option value='" + v[config.valueField] + "'>" + v[config.displayField2] + " - " + v[config.displayField] + " ( " + v[config.displayField3] + " ) " + "</option>";
                        }
                        else {
                            html += "<option value='" + v[config.valueField] + "'>" + v[config.displayField2] + " - " + v[config.displayField] + "</option>";
                        }
                    } else {
                        html += "<option value='" + v[config.valueField] + "'>" + v[config.displayField] + "</option>";
                    }
                });
                $('#' + config.el).html(html);
            }
            else {
                var html = (config.withNull === true) ? "<option value>-Pilih-</option>" : "";
                $.each(config.data, function (i, v) {
                    if (config.grouped) {
                        if (config.displayField3 != null) {
                            html += "<option value='" + v[config.valueField] + "'>" + v[config.displayField2] + " - " + v[config.displayField] + " ( " + v[config.displayField3] + " ) " + "</option>";
                        }
                        else {
                            html += "<option value='" + v[config.valueField] + "'>" + v[config.displayField2] + " - " + v[config.displayField] + "</option>";
                        }
                    } else {
                        html += "<option value='" + v[config.valueField] + "'>" + v[config.displayField] + "</option>";
                    }
                });
                $('#' + config.el).html(html);
            }

            if (config.chosen) {
                $('#' + config.el).select2({
                    placeholder: "-Pilih-",
                    width: '100%',
                    allowClear: config.allowClear
                });
                if (config.value != null) {
                    setTimeout(function () {
                        $('#' + config.el).val(config.value).trigger('change.select2');
                    }, 500);
                }
            } else {
                if (config.value != null) {
                    setTimeout(function () {
                        $('#' + config.el).val(config.value).trigger('change');
                    }, 500);
                }
            }
        },

        ajax: function (config) {
            config = $.extend(true, {
                data: null,
                url: null,
                type: "POST",
                dataType: null,
                beforeSend: function () { },
                success: function () { },
                complete: function () { },
                error: function () { }
            }, config);
            var xdefault = {
                url: config.url,
                data: $.extend(config.data, { csrf_spi: $.cookie('csrf_lock_spi') }),
                type: config.type,
                dataType: config.dataType,
                xhr: config.xhr,
                beforeSend: function (data) {
                    config.beforeSend(data);
                },
                success: function (data) {
                    config.success(data);
                },
                complete: function (response) {
                    var rsp = $.parseJSON(response.responseText);
                    config.complete(rsp, response);
                },
                error: function (error) {
                    if (error && typeof error.fail === "function") {
                        error.fail(error);
                    }
                    if (config && typeof config.error === "function") {
                        config.error(error);
                    }
                },
            };
            if (config.hasOwnProperty("contentType")) {
                xdefault["contentType"] = config.contentType;
            }
            if (config.hasOwnProperty("processData")) {
                xdefault["processData"] = config.processData;
            }
            $.ajax(xdefault);
        },

        showMessage: function (config) {
            config = $.extend(true, {
                icon: 'error', // success, warning, error, info, question
                title: '',
                message: '',
                allowConfirmButton: false,
                confirmButtonText: "OK",
                confirmClassName: "btn btn-primary",
                time: 5000,
                callback: function () { },
            }, config);

            var defaultConfig = {
                title: config.title,
                text: config.message,
                icon: config.icon,
                timer: config.time
            };

            if (config.allowConfirmButton === true) {
                defaultConfig.buttonsStyling = false;
                defaultConfig.confirmButtonText = config.confirmButtonText;
                defaultConfig.customClass = {
                    confirmButton: config.confirmClassName
                };
            } else {
                defaultConfig.showConfirmButton = false;
            }

            Swal.fire(defaultConfig);
        },

        handleValidation: function (config) {

            config = $.extend(true, {
                el: null,
                rules: null,
                callback: function (arg) { }
            }, config);

            $.each(config.rules, function (i, v) {
                if (v.required) {
                    $("[name=" + i + "]").parents('.form-group').children('.control-label').append('<span class="required">* </span>')
                }
            });

            var form = $('#' + config.el);
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            //IMPORTANT: update CKEDITOR textarea with actual content before submit
            form.on('submit', function () {
                // for(var instanceName in CKEDITOR.instances) {
                //     CKEDITOR.instances[instanceName].updateElement();
                // }
            })

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "", // validate all fields including form hidden input
                rules: config.rules,
                messages: { // custom messages for radio buttons and checkboxes
                    membership: {
                        required: "Please select a Membership type"
                    },
                    service: {
                        required: "Please select  at least 2 types of Service",
                        minlength: jQuery.validator.format("Please select  at least {0} types of Service")
                    }
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    if (element.parent(".input-group").size() > 0) {
                        error.insertAfter(element.parent(".input-group"));
                    } else if (element.attr("data-error-container")) {
                        error.appendTo(element.attr("data-error-container"));
                    } else if (element.parents('.radio-list').size() > 0) {
                        error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                    } else if (element.parents('.radio-inline').size() > 0) {
                        error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                    } else if (element.parents('.checkbox-list').size() > 0) {
                        error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                    } else if (element.parents('.checkbox-inline').size() > 0) {
                        error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                    } else {
                        error.insertAfter(element); // for other inputs, just perform default behavior
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    config.callback(false);
                    success.hide();
                    error.show();
                    Metronic.scrollTo(error, -200);
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success.show();
                    error.hide();
                    // form[0].submit(); 
                    config.callback(true, form);
                }

            });

            //apply validation on select2 dropdown value change, this only needed for chosen dropdown integration.
            $('.select2me', form).change(function () {
                form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });

            // initialize select2 tags
            $("#select2_tags").change(function () {
                form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input 
            }).select2({
                tags: ["red", "green", "blue", "yellow", "pink"]
            });

            //initialize datepicker
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                autoclose: true
            });
            $('.date-picker .form-control').change(function () {
                form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input 
            })
        },

        initValidation: function (config) {
            config = $.extend(true, {
                el: null,
                rules: null,
                callback: function (arg) { },
                complete: function (fv) { }
            }, config);

            // set label required & rules
            var rules = [];
            $.each(config.rules, (i, v) => {
                var newRules = [];
                if (typeof v.required != undefined && v.required) {
                    // set label
                    var idColumn = $(`[name="${i}"]`).attr('id');
                    var labelForm = $(`label.col-form-label[for="${idColumn}"]`);
                    if (typeof labelForm != "undefined") {
                        var labelTitle = labelForm.html();
                        // delay to load label title first
                        setTimeout(() => {
                            var addedRequired = '<span class="required required-tag text-danger" aria-required="true">*</span>';
                            var isIncluded = labelForm.find('span.required-tag').length > 0;
                            // replace if exist required sign
                            if (isIncluded) {
                                labelTitle = labelTitle.replace(addedRequired, "");
                            }
                            // change label form
                            labelForm.html(`${labelTitle}${addedRequired}`);
                        }, 300);
                    }
                    // set rules
                    newRules['notEmpty'] = {
                        message: 'Kolom wajib diisi'
                    };
                }

                if (typeof v.choice != undefined && v.choice) {
                    newRules['choice'] = {
                        message: 'Pilih salah satu'
                    };
                }
                // set rules
                rules[i] = {
                    validators: newRules
                }
            });
            // prepare form validation
            var form = document.getElementById(config.el);
            var fv = FormValidation.formValidation(form, {
                fields: rules,
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap(),
                    // Validate fields when clicking the Submit button
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    // Submit the form when all fields are valid
                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                }
            });
            // invalid event
            fv.on('core.field.invalid', function (event) {
                // set callback false
                config.callback(false);
                // set on change event
                var isSelect2 = $(`[name="${event}"].select2me`);
                var isDatePicker = $(`[name="${event}"].date-picker`);
                var isDate = $(`[name="${event}"].date`);
                var isYearPicker = $(`[name="${event}"].year-picker`);
                var isRadio = $(`[name="${event}"][type="radio"]`);
                var isSelectYear = $(`[name="${event}"].year`);
                // Select2
                if (isSelect2.length > 0) {
                    var id = isSelect2.attr('id');
                    // get select2 container
                    var select2Container = $(`span[aria-labelledby="select2-${id}-container"]`);
                    if (select2Container.length == 0) {
                        select2Container = $(`#select2-${id}-container`).parent();
                    }
                    // set danger border container
                    select2Container.css('border', '1px solid #F64E60');
                    isSelect2.on('change', function () {
                        // Revalidate field
                        fv.revalidateField(event);
                        // set success border container
                        select2Container.css('border', '1px solid #1BC5BD');
                    });
                }
                // Datepicker
                if (isDatePicker.length > 0 || isDate.length > 0) {
                    isDatePicker.datepicker().on('changeDate', function (e) {
                        // Revalidate field
                        fv.revalidateField(event);
                    });
                }
                // Datepicker
                if (isYearPicker.length > 0 || isSelectYear.length > 0) {
                    isYearPicker.datepicker({
                        format: "yyyy",
                        startView: "year",
                        minViewMode: "years"
                    }).on('changeDate', function (e) {
                        // Revalidate field
                        fv.revalidateField(event);
                    });
                }
                // Radio
                if (isRadio.length > 0) {
                    isRadio.on('change', function () {
                        // Revalidate field
                        fv.revalidateField(event);
                    });
                }
                // only one message
                var elMessage = $(`[data-field="${event}"]`);
                if (elMessage.length > 1) {
                    $.each(elMessage, (i, v) => {
                        if (i > 0) {
                            // remove duplicated message
                            $(v).remove();
                        }
                    });
                }
            });
            // valid event
            fv.on('core.form.valid', function (event) {
                config.callback(true);
            });
            // set complete callback
            config.complete(fv)
        },

        setRequired: function (el) {
            $(el).each(function (i, v) {
                var getId = $("[name=" + v + "]").attr('id');
                $("[name=" + v + "]").attr('required', true).parents('.form-group').children(`label.col-form-label[for="${getId}"]`).append('<span aria-required="true" style="color: red;"> *</span>')
            })
        },

        print: function (config) {
            config = $.extend(true, {
                el: 'bodylaporan',
                page: null,
                csslink: null,
                historyprint: null,
                callback: function () { }
            }, config);

            var contents = (config.el.length > 32) ? config.el : $("#" + config.el).html();
            var frame1 = $('<iframe />');
            frame1[0].name = "frame1";
            frame1.css({ "position": "absolute", "top": "-1000000px" });
            $("body").append(frame1);
            var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
            frameDoc.document.open();
            frameDoc.document.write("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">");
            frameDoc.document.write('<html>');
            frameDoc.document.write('</head>');
            frameDoc.document.write('</body>');
            if (config.csslink != null) {
                if (config.csslink.constructor === Array) {
                    $.each(config.csslink, function (i, v) {
                        frameDoc.document.write('<link href="' + v + '" rel="stylesheet" type="text/css" />');
                    })
                }
                else {
                    frameDoc.document.write('<link href="' + config.csslink + '" rel="stylesheet" type="text/css" />');
                }
            }
            frameDoc.document.write(contents);
            frameDoc.document.write('</body></html>');
            frameDoc.document.close();
            if (config.historyprint != null) {
                $.ajax({
                    url: config.historyprint,
                    data: {
                        csrf_spi: $.cookie('csrf_lock_spi')
                    },
                    success: function (response) { },
                    complete: function (response) {
                        var rsp = $.parseJSON(response.responseText);
                        config.callback(rsp.success, id, rsp.data, rsp.total);
                    },
                    callback: function (arg) { }
                });
            }
            setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                frame1.remove();
            }, 300);
        },

        prompt: function (config) {
            config = $.extend(true, {
                title: null,
                inputType: null,
                confirmLabel: '<i class="fa fa-check"></i> Ya',
                confirmClassName: 'btn-success',
                cancelLabel: '<i class="fa fa-times"></i> Tidak',
                cancelClassName: 'btn-danger',
                inputOptions: null,
                size: null,
                callback: function () { }
            }, config);

            bootbox.prompt({
                title: (config.title != null) ? config.title : 'Informasi',
                inputType: config.inputType,
                inputOptions: (config.inputOptions != null) ? config.inputOptions : null,
                size: (config.size != null) ? config.size : null,
                buttons: {
                    confirm: {
                        label: config.confirmLabel,
                        className: config.confirmClassName
                    },
                    cancel: {
                        label: config.cancelLabel,
                        className: config.cancelClassName
                    }
                },
                callback: function (result) {
                    config.callback(result);
                }
            });
        },

        toExcel: function (config) {
            config = $.extend(true, {
                el: null,
                title: null,
            }, config);

            if (config.el.constructor === Array) {
                $.each(config.el, function (i, v) {
                    if (i == 0) {
                        tableToExcel(v, config.title);
                    } else {
                        tableToExcel(v, config.title + '-' + (i + 2));
                    }
                });
            } else {
                tableToExcel(config.el, config.title);
            }
        },

        toWord: function (config) {
            config = $.extend(true, {
                el: null,
                title: null,
                paperSize: null,
                style: null,
                margin: null,
            }, config);

            var html, link, blob, url, css, margin;
            margin = (config.margin != null) ? config.margin : '1cm 1cm 1cm 1cm';
            css = (
                '<style>' +
                '@page ' + config.el + '{size: ' + paperSize(config.paperSize) + '; margin: ' + margin + ';}' +
                'div.' + config.el + ' {page: ' + config.el + ';} ' + config.style +
                '</style>'
            );

            html = window.$('#' + config.el).html();
            blob = new Blob(['\ufeff', css + html], { type: 'application/msword;charset=utf-8' });
            url = URL.createObjectURL(blob);
            link = document.createElement('A');
            link.href = url;
            // Set default file name. 
            // Word will append file extension - do not add an extension here.
            link.download = config.title;
            document.body.appendChild(link);
            if (navigator.msSaveOrOpenBlob) navigator.msSaveOrOpenBlob(blob, config.title + '.doc'); // IE10-11
            else link.click();
            document.body.removeChild(link);
        },

        toDecimal: function (value, comma = '') {
            var val = value;
            if ($.isNumeric(val)) {
                if (val != null) {
                    _val = val.toString();
                    val = _val.replace(/[^0-9\.-]/g, '');
                    val = parseFloat(val);
                }
                if (val != 0) {
                    return val.toLocaleString("de-DE", {
                        minimumFractionDigits: comma,
                        maximumFractionDigits: comma,
                    });
                } else {
                    return '';
                }
            } else {
                return '';
            }
        },

        getExt: function (fileName) {
            var _ext = fileName.substr((fileName.lastIndexOf('.') + 1));
            var ext = _ext.toLowerCase();
            var data = {
                image: ['jpg', 'png', 'jpeg', 'gif', 'bmp'],
                office: ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
                pdf: ['pdf'],
                vidio: ['webm', 'mpeg4', '3gpp', 'mov', 'mwv', 'mkv', 'flv', 'avi', 'mp4', 'm4p', 'mpg', 'mpeg', '3gp']
            };

            var search = ext;
            var type = '';
            $.each(data, function (i, v) {
                $.each(v, function (i2, v2) {
                    if (v2 == search) {
                        type = i;
                    }
                })
            });
            return {
                type: type,
                ext: ext
            };
        },

        createFilter: function (config) {
            localStorage.removeItem("shortcut_id");
            config = $.extend(true, {
                el: null,
                table: null,
            }, config);

            $(config.el).each(function () {

                var _action = "";
                if ($(this).is("select")) {
                    _action = "change";
                } else {
                    if ($(this).attr("type") == "text") {
                        _action = "keyup";
                    }
                }

                $(this).on(_action, function () {
                    var val = $(this).val();
                    var column = $(this).data("column");
                    $('#' + config.table)
                        .DataTable()
                        .column(column)
                        .search(val, false, false)
                        .draw();
                });

            });
        },

        bytesToSize: function (bytes) {
            var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            if (bytes == 0) return '0 Byte';
            var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
        },

        newEditor: function (config) {
            config = $.extend(true, {
                id: null,
                height: '50px',
                // readonly: false,
                // value: null,
                // toolbar: ['clipboard','font'],
                callback: function (response) { }
            }, config);

            ClassicEditor
                .create(document.querySelector(`#${config.id}`), {
                    toolbar: {
                        items: [
                            'heading',
                            'alignment',
                            '|',
                            'bold',
                            'italic',
                            'underline',
                            '|',
                            'fontSize',
                            'numberedList',
                            'bulletedList',
                            '|',
                            'link',
                            'insertImage',
                            'insertTable',
                            '|',
                            'indent',
                            'outdent',
                            'undo',
                            'redo'
                        ]
                    },
                })
                .then(editor => {
                    // console.log( editor );
                    editor.ui.view.editable.element.style.height = config.height; // Set height
                    // set callback
                    config.callback(editor)
                })
                .catch(error => {
                    console.fail(error);
                });
        },

        editor: function (config) {
            config = $.extend(true, {
                id: null,
                placeholder: null,
                height: 80,
                readonly: false,
                value: null,
                toolbar: ['clipboard', 'font'],
            }, config);

            var toolbar_list = [];

            toolbar_list['clipboard'] = { name: 'clipboard', items: ['Undo', 'Redo'] };
            toolbar_list['template'] = { name: 'Template', items: ['Templates'] };
            toolbar_list['font'] = { name: 'Font', items: ['Font', 'FontSize', 'Bold', 'Italic', 'Underline', '-', 'TextColor'] };
            toolbar_list['style'] = { name: 'Style', items: ['Format'] };
            toolbar_list['tools'] = { name: 'tools', items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] };
            toolbar_list['paragraph'] = {
                name: 'paragraph',
                items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'BGColor'],
                groups: ['list', 'indent', 'blocks', 'align', 'bidi']
            };
            toolbar_list['links'] = { name: 'links', items: ['Table'] };
            toolbar_list['insert'] = { name: 'insert', items: ['Image', 'Video'] };
            toolbar_list['maximize'] = { name: 'Maximize', items: ['Maximize'] };

            var toolbar = [];
            if (config.toolbar.length > 0) {
                $.each(config.toolbar, function (i, v) {
                    toolbar.push(toolbar_list[v]);
                });
            }

            if (config.placeholder != null) {
                var div = document.createElement("div");
                div.innerHTML = config.placeholder;
                config.placeholder = div.innerHTML;
            }

            CKEDITOR.replace(config.id, {
                filebrowserBrowseUrl: 'ng/dialog.php?type=2&editor=ckeditor&fldr=',
                filebrowserUploadUrl: 'ng/dialog.php?type=2&editor=ckeditor&fldr=',
                filebrowserImageBrowseUrl: 'ng/dialog.php?type=1&editor=ckeditor&fldr=',
                removeDialogTabs: 'image:Link;image:advanced;image:Upload',
                extraPlugins: 'editorplaceholder',
                editorplaceholder: (config.placeholder != null) ? config.placeholder : '',
                height: config.height,
                toolbar: toolbar
            });

            CKEDITOR.on('instanceReady', function () {
                if (config.value != null) CKEDITOR.instances[config.id].setData(config.value);
                CKEDITOR.instances[config.id].setReadOnly(config.readonly);
            });
        },

        editorValue: function (config) {
            config = $.extend(true, {
                id: null,
                value: null,
            }, config);

            CKEDITOR.on('instanceReady', function () {
                if (config.value != null) CKEDITOR.instances[config.id].setData(config.value);
            });
        },

        editorReadOnly: function (config) {
            config = $.extend(true, {
                id: null,
                readonly: true,
            }, config);

            CKEDITOR.on('instanceReady', function () {
                CKEDITOR.instances[config.id].setReadOnly(config.readonly);
            });
        },

        ktceditor: function (config) {
            config = $.extend(
                true,
                {
                    id: null,
                    placeholder: null,
                    height: 80,
                    readonly: false,
                    value: null,
                    toolbar: ["clipboard", "font"],
                },
                config
            );
            var demos = function () {
                ClassicEditor.create(document.querySelector("#" + config.id), {
                    placeholder: config.placeholder,
                })
                    .then((editor) => {
                        if (config.value != null) editor.setData(config.value);
                        editor.isReadOnly = config.readonly;
                    })
                    .catch((error) => {
                        console.fail(error);
                    });
            };

            return {
                // Public functions
                init: function () {
                    demos();
                },
            };
        },
    }
}();

$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(Sanitizer.sanitize(this.value) || '');
        } else {
            o[this.name] = Sanitizer.sanitize(this.value) || '';
        }
    });
    return o;
};

$.fn.randBetween = function (min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
};

var tableToExcel = (function () {
    var uri = 'data:application/vnd.ms-excel;base64,'
        , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
        , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
        , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
    return function (table, name) {
        if (!table.nodeType) table = document.getElementById(table)
        var ctx = { worksheet: name || 'Worksheet', table: table.innerHTML }
        // window.location.href = uri + base64(format(template, ctx))
        var dataFormat = uri + base64(format(template, ctx));
        var $a = $("<a>");
        $a.attr("href", dataFormat);
        $('body').append($a);
        $a.attr("download", name + '.xls');
        $a[0].click();
        $a.remove();
    }
})();

function paperSize(data_tipe) {
    var tipe = data_tipe.toUpperCase();
    switch (tipe) {
        case 'A4': return '21cm 29.7cm'; break;
        case 'LETTER': return '21.6cm 27.9cm'; break;
        case 'LEGAL': return '21.6cm 35.6cm'; break;
        case 'FOLIO': return '21.5cm 33.0cm'; break;
        case 'A0': return '84.1cm 118.9cm'; break;
        case 'A1': return '59.4cm 84.1cm'; break;
        case 'A2': return '42.0cm 59.4cm'; break;
        case 'A3': return '29.7cm 42.0cm'; break;
        case 'A4': return '21.0cm 29.7cm'; break;
        case 'A5': return '14.8cm 21.0cm'; break;
        case 'A6': return '10.5cm 14.8cm'; break;
        case 'A7': return '7.4cm 10.5cm'; break;
        case 'A8': return '5.2cm 7.4cm'; break;
        case 'A9': return '3.7cm 5.2cm'; break;
        case 'A10': return '2.6cm 3.7cm'; break;
        case 'B0': return '100.0cm 141.4cm'; break;
        case 'B1': return '70.7cm 100.0cm'; break;
        case 'B2': return '50.0cm 70.7cm'; break;
        case 'B3': return '35.3cm 50.0cm'; break;
        case 'B4': return '25.0cm 35.3cm'; break;
        case 'B5': return '17.6cm 25.0cm'; break;
        case 'B6': return '12.5cm 17.6cm'; break;
        case 'B7': return '8.8cm 12.5cm'; break;
        case 'B8': return '6.2cm 8.8cm'; break;
        case 'B9': return '4.4cm 6.2cm'; break;
        case 'B10': return '3.1cm 4.4cm'; break;
    }
}

var monthNames = [
    "January", "February", "March", "April", "May", "June", "July",
    "August", "September", "October", "November", "December"
];
var dayOfWeekNames = [
    "Sunday", "Monday", "Tuesday",
    "Wednesday", "Thursday", "Friday", "Saturday"
];
function formatDate(date, patternStr) {
    if (!patternStr) {
        patternStr = 'M/d/yyyy';
    }
    var day = date.getDate(),
        month = date.getMonth(),
        year = date.getFullYear(),
        hour = date.getHours(),
        minute = date.getMinutes(),
        second = date.getSeconds(),
        miliseconds = date.getMilliseconds(),
        h = hour % 12,
        hh = twoDigitPad(h),
        HH = twoDigitPad(hour),
        mm = twoDigitPad(minute),
        ss = twoDigitPad(second),
        aaa = hour < 12 ? 'AM' : 'PM',
        EEEE = dayOfWeekNames[date.getDay()],
        EEE = EEEE.substr(0, 3),
        dd = twoDigitPad(day),
        M = month + 1,
        MM = twoDigitPad(M),
        MMMM = monthNames[month],
        MMM = MMMM.substr(0, 3),
        yyyy = year + "",
        yy = yyyy.substr(2, 2)
        ;
    // checks to see if month name will be used
    patternStr = patternStr
        .replace('hh', hh).replace('h', h)
        .replace('HH', HH).replace('H', hour)
        .replace('mm', mm).replace('m', minute)
        .replace('ss', ss).replace('s', second)
        .replace('S', miliseconds)
        .replace('dd', dd).replace('d', day)

        .replace('EEEE', EEEE).replace('EEE', EEE)
        .replace('yyyy', yyyy)
        .replace('yy', yy)
        .replace('aaa', aaa);
    if (patternStr.indexOf('MMM') > -1) {
        patternStr = patternStr
            .replace('MMMM', MMMM)
            .replace('MMM', MMM);
    }
    else {
        patternStr = patternStr
            .replace('MM', MM)
            .replace('M', M);
    }
    return patternStr;
}

(function ($) {
    $.fn.extend({
        donetyping: function (callback, timeout) {
            timeout = timeout || 1e3; // 1 second default timeout
            var timeoutReference,
                doneTyping = function (el) {
                    if (!timeoutReference) return;
                    timeoutReference = null;
                    callback.call(el);
                };
            return this.each(function (i, el) {
                var $el = $(el);
                // Chrome Fix (Use keyup over keypress to detect backspace)
                // thank you @palerdot
                // $el.is(':input') && $el.on('keyup keypress paste',function(e){
                $el.is(':input') && $el.on('blur', function (e) {
                    // This catches the backspace button in chrome, but also prevents
                    // the event from triggering too preemptively. Without this line,
                    // using tab/shift+tab will make the focused element fire the callback.
                    if (e.type == 'keyup' && e.keyCode != 8) return;

                    // Check if timeout has been set. If it has, "reset" the clock and
                    // start over again.
                    if (timeoutReference) clearTimeout(timeoutReference);
                    timeoutReference = setTimeout(function () {
                        // if we made it here, our timeout has elapsed. Fire the
                        // callback
                        doneTyping(el);
                    }, timeout);
                }).on('blur', function () {
                    doneTyping(el);
                    // If we can, fire the event since we're leaving the field
                    /*  timeoutReference = setTimeout(function(){
                          // if we made it here, our timeout has elapsed. Fire the
                          // callback
                      }, timeout);*/
                });
            });
        }
    });
})(jQuery);

function validateInput(rules) {
    let isFormValid = true;

    rules.forEach(rule => {
        // const input = document.getElementById(rule.id); 
        const inputs = document.querySelectorAll(rule.selector);
        inputs.forEach(input => {
            const errorLabel = document.querySelector(`label[for="${input.id}"]`);
            const value = input.value.trim();

            errorLabel.textContent = "";
            input.classList.remove("is-invalid");

            if (rule.rules.includes("required") && value === "") {
                errorLabel.textContent = rule.errors.required;
                errorLabel.style.color = "red";
                input.classList.add("is-invalid");
                isFormValid = false;
            }

            if (rule.rules.includes("regex_match")) {
                const regexPattern = rule.rules.match(/regex_match\[\/(.*)\/\]/)[1];
                const regex = new RegExp(regexPattern);

                if (!regex.test(value)) {
                    errorLabel.textContent = rule.errors.regex_match;
                    errorLabel.style.color = "red";
                    input.classList.add("is-invalid");
                    isFormValid = false;
                }
            }
        });

    });

    return isFormValid;
}
