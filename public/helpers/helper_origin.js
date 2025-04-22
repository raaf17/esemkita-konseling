var menuid= null;
var __role  = [];

var HELPER = function()
{
    var loadBlock = function(message)
    {
        $.blockUI({ 
            message: '<i class="fa fa-spinner fa-spin fa-2x" style="color:#3598dc;"></i>' ,
            css: {border: 'none', backgroundColor: 'rgba(47, 53, 59, 0)'},
            baseZ: 999999
        }); 
    }

    var unblock = function(delay)
    {
        window.setTimeout(function() {
            $.unblockUI();
        }, delay);
    }

    var html_entity_decode = function(txt){
        var randomID = Math.floor((Math.random()*100000)+1);
        $('body').append('<div id="random'+randomID+'"></div>');
        $('#random'+randomID).html(txt);
        var entity_decoded = $('#random'+randomID).html();
        $('#random'+randomID).remove();
        return entity_decoded;
    }

    return {
        block: function(msg)
        {
            loadBlock(msg);
        },
        unblock: function(delay)
        {
            unblock(delay);
        },
        toRp : function(angka){
            var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
            var rev2    = '';
            for(var i = 0; i < rev.length; i++){
                rev2  += rev[i];
                if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
                    rev2 += '.';
                }
            }
            return '' + rev2.split('').reverse().join('') + ',00';
        },
        setting: function(){
            var html = 
            '<div class="modal fade" id="modal_setting" tabindex="-1" data-backdrop="static" data-keyboard="false">'+
                '<div class="modal-dialog">'+
                    '<div class="modal-content btn-radius">'+
                        '<div class="modal-header">'+
                            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>'+
                            '<span class="caption-subject font-purple-intense" style="font-weight: bold;">'+
                                '<i class="icon-settings"></i> Pengaturan Akun'+
                            '</span>'+
                        '</div>'+
                        '<form action="javascript:HELPER.onUpdatePassword()" name="set_account_form" class="form-horizontal">'+
                        '<div class="modal-body">'+
                                '<div class="row" style="padding: 20px 0px 0px 10px;">'+
                                    '<div class="form-group">'+
                                        '<label class="control-label col-md-3">Password Lama <span style="color: red;">*</span></label>'+
                                        '<div class="col-md-8">'+
                                            '<input type="password" name="old_password" class="form-control rb" required="" placeholder="Password lama">'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="form-group">'+
                                        '<label class="control-label col-md-3">Password Baru <span style="color: red;">*</span></label>'+
                                        '<div class="col-md-8">'+
                                            '<input type="password" name="new_password" class="form-control rb" required="" placeholder="Password baru">'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="form-group">'+
                                        '<label class="control-label col-md-3">Ulangi Password <span style="color: red;">*</span></label>'+
                                        '<div class="col-md-8">'+
                                            '<input type="password" name="re_password" class="form-control rb" required="" placeholder="Ulangi password baru">'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                        '</div>'+
                        '<div class="modal-footer">'+
                            '<button type="button" data-dismiss="modal" class="btn dark btn-outline">Batal</button>'+
                            '<button type="submit" class="btn green">Simpan</button>'+
                        '</div>'+
                        '</form>'+
                    '</div>'+
                '</div>'+
            '</div>';
            $('#child-md').html(html);
        },
        onUpdatePassword: function(){
            HELPER.confirm({
                message: 'Apakah anda yakin ingin mengubah data tersebut?',
                callback: function(result){
                    if (result) {
                        HELPER.block();
                        HELPER.ajax({
                            url : APP_URL+'Siswa/upDatePassword',
                            data : {
                                old_password : $('[name="old_password"]').val(),
                                new_password : $('[name="new_password"]').val(),
                                re_password  : $('[NAME="re_password"]').val()
                            }, success: function(dSuc){
                                if (dSuc.success) {
                                    $('.rb').attr('style','');
                                    $('#modal_setting').modal('hide');
                                }else{
                                    $('.rb').attr('style','');
                                    $.each(dSuc.fields, function(i,v){
                                        $('[name="'+v+'"]').attr('style', 'border: 1px solid red;')
                                    });
                                    HELPER.showMessage({
                                        title: 'Informasi',
                                        success: false,
                                        message: dSuc.fields[0],
                                    });
                                }
                            }, complete : function(dCom){
                                HELPER.unblock(500);
                            }
                        })
                    }
                }
            });
        },
        login: function(){
            alert()
        },
        logout: function(){
            HELPER.confirm({
                title: 'Informasi',
                message: 'Apakah anda yakin akan keluar?',
                callback: function(result){
                    if (result) {
                        $.ajax({
                            url: APP_URL + 'Dashboard/logout',
                            data: {
                                csrf_spi: $.cookie('csrf_lock_spi'),
                                token: FCM.getMyToken()
                            },
                            type: "POST",
                            complete: function(response) {

                                if (localStorage.getItem('from') != null) {
                                    window.location.href = localStorage.getItem('from');
                                    localStorage.removeItem("from");
                                }else{
                                    window.location.href = APP_URL_REMOVE_INDEX+'spi-login';
                                }

                                // window.location.href = APP_URL_REMOVE_INDEX+'spi-login';
                               /* var res = JSON.parse(response.responseText);
                                if (res.success) {
                                    window.location.href = APP_URL_REMOVE_INDEX+'spi-login';
                                }else{
                                    HELPER.showMessage({
                                        title: 'Gagal',
                                        success: false,
                                        message: 'Kesalahan Sistem, hubungi Administrator'
                                    });
                                }*/
                            }
                        })
                    }
                }
            });
        },
        html_entity_decode: function(txt)
        {
            html_entity_decode(txt);
        },
        getCookie: function(cname)
        {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i=0; i<ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1);
                if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
            }
            return "";
        },
        nullConverter : function(val,xval){
            var retval = val;
            if (val === null || val === '' || typeof val == 'undefined') {
                retval = (typeof xval != 'undefined') ? xval : "-" ;
            }
            return retval;
        },
        routeModul: function(el){
            HELPER.block();
            var page = $(el).data();
            var dataSend = {
                'con'       : page.con,
                'modul_id'  : page.id,
                'modul'     : page.modul,
                csrf_spi: $.cookie('csrf_lock_spi')
            };
            $.ajax({
                url : APP_URL+'Dashboard/setModulId',
                data: dataSend,
                type: "POST",
                success: function(dSuc){
                    if (dSuc.success) {
                        window.location.href = APP_URL_REMOVE_INDEX+page.modul+'.php';
                    }else{
                        HELPER.showMessage({
                            'success'   : false,
                            'title'     : 'Informasi',
                            'message'   : 'Gagal load modul, Silahkan hubungi administrator'
                        });
                    }
                }, complete: function(dCom){
                    HELPER.unblock(500);
                }
            });
        },
        loadPage: function(el)
        {
            loadBlock();
            $('.li-menu').removeClass('active');
            $('.btn-menu-top').removeClass('btn-success');

            var is_notif = false;
            var data_notif = {};
            if ($(el).hasClass('md_notif')) {
                is_notif = true;
                data_notif = $(el).data();
                $(el).removeAttr('style');
            }

            if ($(el).hasClass('btn-menu-top') || $(el).hasClass('btn-menu-top-drop') || $(el).hasClass('md_notif')) {
                var mn_top = $(el).data();
                $('.btn-top-toggle').removeClass('btn-success').addClass('btn-primary');

                if ($(el).hasClass('btn-menu-top-drop'))
                {
                    $($(el).parent().parent().prev()).removeClass('btn-primary').addClass('btn-success');
                }
                else if ($(el).hasClass('btn-menu-top'))
                {
                    $(el).addClass('btn-success');
                }
                $('[data-menuid="'+mn_top.menu_parent+'"]').parent().addClass('active');

                // set shotcurt-id
                var xdata = $(el).data();
                localStorage.setItem("shortcut_id", xdata.content_id);
            }else{
                localStorage.removeItem("shortcut_id");
                $('#menu_top').html('');
                $(el).parent('li').addClass('active');
            }

            var page     = $(el).data();
            var dataSend = {
                'con':page.con,
                'menuid':page.menuid,
                is_notif: is_notif,
                data_notif: data_notif,
                csrf_spi: $.cookie('csrf_lock_spi')
            };
            menuid  = page.menuid;
            $.ajax({
                url: APP_URL + "Dashboard/getPage",
                data: dataSend,
                type: "POST",
                complete: function(pages)
                {
                    var resp_object = $.parseJSON(pages.responseText);
                    if (resp_object != null) {
                        if (! resp_object.isLogin){
                            window.location.reload();
                        } 
                        var title = (resp_object.menu_keterangan !=null) ? resp_object.menu_keterangan : '';
                        rights = resp_object.rights;
                        $.when(function(){
                            //   MENU TOP
                            if (typeof resp_object.menu_top != 'undefined') {

                                var menu_top = [];
                                var default_active = '';

                                if (resp_object.menu_top != '') {
                                    $.each(resp_object.menu_top, function(i,v){
                                        if (i == 0) {
                                             default_active = v.menu_id;
                                        }
                                        if (typeof v.sub != 'undefined') {
                                            menu_top.push([
                                                `
                                                    <div class="btn-group">
                                                        <a class="btn btn-sm btn-primary btn-top-toggle btn-menu-top btn-radius" href="#" data-toggle="dropdown">:: 
                                                            <i class="fa fa-file-o"></i> Generate Laporan <i class="fa fa-angle-down "></i>
                                                        </a>
                                                        <ul class="dropdown-menu pull-right">`]);
                                                            $.each(v.sub, function(i2,v2) {
                                                                menu_top.push([
                                                                    `
                                                                        <li>
                                                                            <a href="javascript:;" class="btn-dropdown btn-menu-top-drop" data-menuid="${v2.menu_id}" data-menu_parent="${v.menu_parent}" data-con="${v2.menu_kode}" onclick="HELPER.loadPage(this)">${v2.menu_title}</a>
                                                                        </li>
                                                                    `
                                                                ]);
                                                            })
                                                        menu_top.push([`</ul>
                                                    </div>
                                                `
                                            ]);
                                        }else{
                                             menu_top.push([
                                                `
                                                    <button class="btn btn-sm btn-primary btn-menu-top" data-menuid="${v.menu_id}" data-menu_parent="${v.menu_parent}" data-con="${v.menu_kode}" onclick="HELPER.loadPage(this)">:: 
                                                        <i class="${v.menu_icon}"></i> ${v.menu_title}
                                                    </button>
                                                `
                                            ]);
                                        }
                                    });
                                    $('#menu_top').html(menu_top.join(''));
                                    if (is_notif == false) {
                                        $('[data-menuid="'+default_active+'"]').addClass('btn-success');
                                    }
                                }else{
                                    // console.log(el)
                                    if (!$(el).hasClass('btn-menu-top') && !$(el).hasClass('btn-menu-top-drop')) {
                                        $('#menu_top').html('');
                                    }
                                }
                            }
                            // END MENU TOP

                            $("#idcontent").html(atob(resp_object.view));
                            $('#pagetitle').html(resp_object.menu_title+" <small>"+title+"</small>");
                            $('#icon-title').html('<i class="flaticon-list-2"></i>');
                            $('#icon-form').html('<i class="flaticon-edit-1"></i>');
                            if (resp_object.menu_title !='Dashboard') {
                                $('#con_breadcrumb').html(atob(resp_object.breadcrumb));
                            }
                            $('#box-title').html('Tabel ' + resp_object.menu_title);
                            $('#form-title').html('Form ' + resp_object.menu_title);
                            
                        }()).then(function(){
                            var container = $("#idcontent");
                            __role = resp_object.rights;
                            
                            if (resp_object.rights.length > 0){
                                $.each(resp_object.rights,function(i,v){
                                    var role_object = $("[data-role="+v.menu_kode+"]",container);
                                    if ($(role_object).data('roleable')){
                                        $(role_object).addClass('aman');
                                    }
                                });
                                $.each($('[data-roleable=true]',container),function(i,v){
                                    if (! $(v).hasClass('aman')) {
                                        if ($(v).data('tab')) {
                                            window.li = $(v)[0];
                                            if (li) {
                                                $(li.nextElementSibling).find('a').trigger('click');
                                            }
                                        }
                                        $(v).remove();
                                    } else {
                                        $(v).removeClass('aman');
                                    }
                                })
                            }else{
                                $('[data-roleable="true"]').css({'display':'none'})
                            }
                        }()).then(function(){

                            setTimeout(function(){
                                $('.total_notif').html('0').html(resp_object.jumlah_notif);
                                $('.total_notif_badge').html('0').html(resp_object.jumlah_notif);
                                $('.list_notif').html('').html(resp_object.html_notif);
                            }, 200)

                        }())
                    }
                },
                error: function(pages, status, errorname)
                {
                    if (pages.status == 403) {
                        bootbox.alert("Anda tidak memiliki izin untuk mengakses / di server ini.", function(){ 
                            window.location.reload();
                        });
                    }
                }
            }).done(function() {
                if (is_notif) {
                    setTimeout(function() {
                        $('.btn-menu-top[data-menuid="'+data_notif.menuid+'"]').removeClass('btn-primary').addClass('btn-success');
                        $('[data-menuid="'+data_notif.parent+'"]').parent().addClass('active');
                    }, 1000)
                }
            }());
        },
        initTable: function(config)
        {
            config = $.extend(true,{
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
                filterColumn : {
                    state : false,
                    exceptionIndex : []
                },
            },config);
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
                fnDrawCallback: function(oSettings)                
                {   
                    $('thead').find('th').css({'text-align':'center'});
                    if (config.multiple===false){
                        $('tbody').find('tr').each(function(i,v){
                            $('td:eq(0)',v).css({'text-align':'center'});
                            if (config.clickable === true) {
                                $(v).addClass('clickable');
                            }
                        })

                        $('.row_selected').removeClass('row_selected');
                        if (config.clickable === true) {
                            $("#"+config.el+" tr").css('cursor', 'pointer');
                            $("#"+config.el+" tbody tr").each(function(i, v) {
                                $(v).on('click', function() {
                                    if (oSettings.aoData.length > 0){
                                        $(v).addClass('row_selected');
                                        if ( $(this).hasClass('selected') ) {
                                            $(v).removeClass('selected');    
                                            $('.checkbox').prop('checked', false);
                                            $('input[name=checkbox]',v).prop('checked',false);
                                            $('.disable').attr('disabled',true);
                                            $('.row_selected').removeClass('row_selected');
                                        }else {
                                            $('.checkbox').prop('checked', false);
                                            $(".selected").removeClass('selected');
                                            $('#'+config.el+'.dataTable tbody tr.selected').removeClass('selected');
                                            $(v).addClass('selected');
                                            $('.row_selected').removeClass('row_selected');
                                            $(v).addClass('row_selected');
                                            $('input[name=checkbox]',v).prop('checked',true);
                                            $('.disable').attr('disabled',false);
                                        }  
                                    }
                                });
                            });
                        }
                    }else{
                        $('tbody').find('tr').each(function(i,v){
                            $(v).addClass('clickable');
                        })
                        var cnt = 0;
                        $("#"+config.el+" tr").css('cursor', 'pointer');
                        $("#"+config.el+" tbody tr").each(function(i, v){
                            $(v).on('click', function() {
                                if ( $(this).hasClass('selected') ) {
                                    --cnt;
                                    $(v).removeClass('selected');    
                                    $(v).removeAttr('checked'); 
                                    $('input[name=checkbox]',v).prop('checked',false);
                                    $(v).removeClass('row_selected');
                                }else {
                                    ++cnt;
                                    $('input[name=checkbox]',v).prop('checked',true);
                                    $(v).addClass('selected');
                                    $(v).addClass('row_selected');
                                }  

                                if (cnt>0) {
                                    $('.disable').attr('disabled',false);
                                }else{
                                    $('.disable').attr('disabled',true);
                                }
                            });
                        });

                        $('.'+config.parentCheck).click(function(event){ 
                            if(this.checked) {
                                $('.'+config.childCheck).each(function() {
                                    this.checked = true;       
                                    $("#"+config.el+" tbody tr").each(function(i, v){
                                        $(v).addClass('selected');
                                        $(v).addClass('row_selected');
                                    });
                                });
                                $('.'+config.parentCheck).addClass('selected');
                                $('.disable').attr('disabled',false);
                            }else{
                                $('.'+config.childCheck).each(function() { 
                                    this.checked = false; 
                                    $("#"+config.el+" tbody tr").each(function(i, v){
                                        $(v).removeClass('row_selected');
                                        $(v).removeClass('selected');    
                                        $(v).removeAttr('checked'); 
                                    });
                                });        
                                $('.disable').attr('disabled',true);
                            }
                        });

                        $('th').click(function(i,v){
                            if($(this).hasClass('sorting_disabled')){}else{
                                $("#"+config.el+" tbody tr").each(function(i2, v2){
                                    $(v2).removeClass('row_selected');
                                    $(v2).removeClass('selected');    
                                    $(v2).removeAttr('checked'); 
                                });
                                $('.'+config.parentCheck).removeClass('selected');
                                $('.'+config.parentCheck).prop('checked',false);
                            }
                        })   
                    }
                    HELPER.setRole();                    
                },
                fnRowCallback: function(row, data, index, rowIndex){
                    $('.disable').attr('disabled',true);
                },
                fnInitComplete: function(oSettings, data){
                    HELPER.setRole();
                },

                "ajax": {
                    'url': config.url,
                    'type': 'POST',
                    // 'data': $.extend(config.data, {csrf_spi: $.cookie('csrf_lock_spi')}), debrecate csrf
                    data: function ( d ) {
                        d.csrf_spi = $.cookie('csrf_lock_spi');
                        d.data = config.data;
                    }
                },
            };
            /*add input filter column*/
            if (config. filterColumn.state) {
                $("#" + config.el+ ' tfoot').remove();
                $("#" + config.el).append('<tfoot>'+$("#" + config.el+' thead').html()+'</tfoot>');                                
            }            

            var el = $("#" + config.el);
            if (! config.force) {
                var dt = $(el).dataTable($.extend(true, xdefault, config));                
            } else {
                var dt = $(el).dataTable(config);
            }

            /*initiate filter column*/

            if (config.filterColumn.state) {                
                $('#'+config.el+' tfoot th').each( function (i,v) {
                    var title = 'Enter untuk cari';
                    var kelas = (typeof $(v).data('type') == 'undefined') ? '' : $(v).data('type')
                    if (i > 0 && $.inArray(i,config.filterColumn.exceptionIndex) == -1) {
                        $(v).html( '<input type="text" placeholder=": '+title+'" class="form-control search '+kelas+'" />' );
                    } else {
                        $(v).html(' ');                        
                    }
                });
                $('#'+config.el).DataTable().columns().every( function (i,v) {
                    var that = this;

                    $( 'input', this.footer() ).on( 'keyup', function (event) {             
                        if (event.keyCode == 13 || event.which == 13){
                           if ( that.search() !== this.value ) {
                                that
                                    .column(i)
                                    .search( this.value )
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

        initTable2: function(config)
        {
            config = $.extend(true,{
                el: '',
                multiple: false,
                force: false,
                data: {
                    csrf_spi: $.cookie('csrf_lock_spi'),
                }
            },config);
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
                "aaSorting": [],
                fnDrawCallback: function(oSettings)                
                {   
                                      
                },
                fnRowCallback: function(row, data, index, rowIndex){
                    
                },
                fnInitComplete: function(oSettings, data){
                    
                },

                "ajax": {
                    'url': config.url,
                    'type': 'POST',
                    'data': config.data
                },
            };

            var el = $("#" + config.el);
            if (! config.force) {
                var dt = $(el).dataTable($.extend(true, xdefault, config));                
            } else {
                var dt = $(el).dataTable(config);
            }

            $(el).addClass('table-condensed').removeClass('table-striped').addClass('compact nowrap hover dt-head-left');
            return dt;
        },

        aksi: function(role_update = '',role_delete = '',role_detail = '',role_detail_2 = '',role_detail_3 = ''){
            tootlip = (typeof role_detail.tooltip != "undefined")? role_detail.tooltip:"";
            tootlip2 = (typeof role_detail_2.tooltip != "undefined")? role_detail_2.tooltip:"";
            tootlip3 = (typeof role_detail_3.tooltip != "undefined")? role_detail_3.tooltip:"";
            var detail = (role_detail !='') ? '<a href="javascript:;" '+((tootlip != "")? "title='"+tootlip+"'":"")+' data-roleable="true" data-role="'+role_detail.role+'" onclick="'+role_detail.action+'(this)" class="'+((role_detail.role == '') ? '' : 'hide')+' xremove_button btn-radius btn btn-xs '+role_detail.btn_color+'"> <i class="'+role_detail.icon+'"></i> '+role_detail.title+' </a>' : '';
            var detail_2 = (role_detail_2 !='') ? '<a href="javascript:;" '+((tootlip2 != "")? "title='"+tootlip2+"'":"")+' data-roleable="true" data-role="'+role_detail_2.role+'" onclick="'+role_detail_2.action+'(this)" class="'+((role_detail.role == '') ? '' : 'hide')+' xremove_button btn-radius btn btn-xs '+role_detail_2.btn_color+'"> <i class="'+role_detail_2.icon+'"></i> '+role_detail_2.title+' </a>' : '';
            var detail_3 = (role_detail_3 !='') ? '<a href="javascript:;" '+((tootlip3 != "")? "title='"+tootlip3+"'":"")+' data-roleable="true" data-role="'+role_detail_3.role+'" onclick="'+role_detail_3.action+'(this)" class="'+((role_detail.role == '') ? '' : 'hide')+' xremove_button btn-radius btn btn-xs '+role_detail_3.btn_color+'"> <i class="'+role_detail_3.icon+'"></i> '+role_detail_3.title+' </a>' : '';
            return  '<a href="javascript:;" data-roleable="true" data-role="'+role_update+'" onclick="onEdit(this)" class="hide xremove_button btn-radius btn btn-xs btn-warning"> <i class="fa fa-edit"></i> Ubah </a>&nbsp;'+
            '<a href="javascript:;" data-roleable="true" data-role="'+role_delete+'" onclick="onDelete(this)" class="hide xremove_button btn-radius btn btn-xs btn-danger"> <i class="fa fa-trash-o"></i> Hapus </a> '+detail+detail_2+detail_3;
        },

        getRecord : function(el){
            return JSON.parse(atob($($($(el).parents('tr').children('td')[0]).children('input')).data('record')));
        },

        getRowData : function(config){
            var xdata = $.parseJSON(atob($($(config.data[0])[2]).data('record')));
            return xdata;
        },

        getRowDataMultiple : function(config){
            var xdata = $.parseJSON(atob($(config.data[0]).data('record')));
            return xdata;
        },

        toggleForm: function(config)
        {
            config = $.extend(true, {
                speed: 'fast',
                easing: 'swing',
                callback: function() {},
                tohide: 'data_table',
                toshow: 'data_form',
                animate: null,
            }, config);

            if (config.animate!==null)
            {
                if (config.animate==='fade')
                {
                    $("." + config.tohide).fadeOut(config.speed, function() {
                        $("." + config.toshow).fadeIn(config.speed, config.callback).css('display','block');
                    });
                }
                else if (config.animate==='toogle')
                {
                    $("." + config.tohide).fadeToggle(config.speed, function() {
                        $("." + config.toshow).fadeToggle(config.speed, config.callback).css('display','block');
                    });
                }
                else if (config.animate==='slide')
                {
                    $("." + config.tohide).slideUp(config.speed, function(){
                        $("." + config.toshow).slideDown(config.speed,config.callback).css('display','block');
                    });
                }
                else{
                    $("." + config.tohide).fadeOut(config.speed, function() {
                        $("." + config.toshow).fadeIn(config.speed, config.callback).css('display','block');
                    });
                }
            }
            else
            {
                $("." + config.tohide).fadeOut(config.speed, function() {
                    $("." + config.toshow).fadeIn(config.speed, config.callback).css('display','block');
                });
            }

            $('html,body').animate({
                scrollTop: 0 /*pos + (offeset ? offeset : 0)*/
            }, 'slow');
        },

        refresh: function(config)
        {
            config = $.extend(true,{
                table:null
            },config);

            if (config.table !== null)
            {
                if(config.table.constructor === Object)
                {
                    $.each(config.table,function(i,v){
                        $("#"+v).dataTable().fnReloadAjax();
                    });
                }
                else if (config.table.constructor === Array)
                {
                    $.each(config.table,function(i,v){
                        $("#"+v).dataTable().fnReloadAjax();
                    });
                }
                else
                {
                    $("#"+config.table).dataTable().fnReloadAjax();
                }
            }
            $('.disable').attr('disabled',true);
        },

        back: function(config)
        {
            config = $.extend(true, {
                speed: 'fast',
                easing: 'swing',
                callback: function() {},
                tohide: 'form_data',
                toshow: 'table_data',
                animate: null,
                loadPage: true,
                table: null,
            }, config);

            $.when(function(){
                if (config.table !==null)
                {
                    if(config.table.constructor === Object)
                    {
                        $.each(config.table,function(i,v){
                            $("#"+v).dataTable().fnReloadAjax();
                        });
                    }
                    else if (config.table.constructor === Array)
                    {
                        $.each(config.table,function(i,v){
                            $("#"+v).dataTable().fnReloadAjax();
                        });
                    }
                    else
                    {
                        $("#"+config.table).dataTable().fnReloadAjax();
                    }
                }

                if (config.animate!==null)
                {
                    if (config.animate==='fade')
                    {
                        $("." + config.tohide).fadeOut(config.speed, function() {
                            $("." + config.toshow).fadeIn(config.speed, config.callback)
                        });
                    }
                    else if (config.animate==='toogle')
                    {
                        $("." + config.tohide).fadeToggle(config.speed, function() {
                            $("." + config.toshow).fadeToggle(config.speed, config.callback)
                        });
                    }
                    else if (config.animate==='slide')
                    {
                        $("." + config.tohide).slideUp(config.speed, function(){
                            $("." + config.toshow).slideDown(config.speed,config.callback);                
                        });
                    }
                    else{
                        $("." + config.tohide).fadeOut(config.speed, function() {
                            $("." + config.toshow).fadeIn(config.speed, config.callback)
                        });
                    }
                }
                else
                {
                    $("." + config.tohide).fadeOut(config.speed, function() {
                        $("." + config.toshow).fadeIn(config.speed, config.callback)
                    });
                }
            }()).done(function(){
                if (config.loadPage===true)
                {
                    $("[data-menuid='"+menuid+"']").not('.md_notif').trigger('click');
                }
            }());           
        },

        reloadPage: function()
        {
            $("[data-menuid='"+menuid+"']").not('.md_notif').trigger('click');
        },

        decodeHtml: function(html)
        {
            var txt = document.createElement("textarea");
            txt.innerHTML = html;
            return txt.value;
        },

        save: function(config)
        {
            var xurl = null;
            if (config.addapi===true)
            {
                xurl = ($("[name=" + HELPER[config.fields][0] + "]").val() === "") ? HELPER[config.api].store : HELPER[config.api].update;
            }
            else
            {
                if (typeof HELPER.api != 'undefined') {
                    xurl = ($("[name=" + HELPER.fields[0] + "]").val() === "") ? HELPER.api.store : HELPER.api.update;
                }
            }

            // console.log(config);
            config.data.append('csrf_spi',$.cookie('csrf_lock_spi'));
            config = $.extend(true, {
                form: null,
                confirm: false,
                data:  $.extend($('[name=' + config.form + ']').serializeObject(),{
                    csrf_spi: $.cookie('csrf_lock_spi')
                }),
                method: 'POST',
                fields: 'fields',
                api: 'api',
                addapi: false, 
                url  : xurl,
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: 'application/x-www-form-urlencoded',
                // processData: false,
                success: function(response)
                {
                    HELPER.showMessage({
                        success: response.success,
                        message: response.message,
                        title: ((response.success) ? 'Sukses' : 'Gagal')
                    });
                    unblock(100);
                },
                error: function(response, status, errorname)
                {
                    HELPER.showMessage({
                        success: false,
                        title: errorname,
                        message: 'Terjadi Kesalahan Sistem, hubungi Administrator'
                    });
                    unblock(100);
                },
                complete: function(response)
                {
                    var rsp = $.parseJSON(response.responseText);
                    config.callback(rsp.success,rsp.id,rsp.record,rsp.message,response);
                },
                callback: function(arg) {}
            }, config);

            var do_save = function(_config)
            {
                loadBlock('Sedang menyimpan data...');
                $.ajax({
                    url: _config.url,
                    data: _config.data,
                    type: _config.method,
                    cache: _config.cache,
                    contentType: _config.contentType,
                    processData: _config.processData,
                    xhr: function() {
                        var myXhr = $.ajaxSettings.xhr();
                        return myXhr;
                    },
                    success: _config.success,
                    error: _config.error,
                    complete: _config.complete
                });
            }

            if (config.confirm)
            {
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
                        if (result===true) {
                            do_save(config);
                        }
                    }
                });
            }
            else
            {
                do_save(config);
            }
        },

        destroy: function(config)
        {
            config = $.extend(true, {
                table: null,
                confirm: true,
                method: 'POST',
                api: 'api',                
                data: null,
                multiple: false,
                fields: 'fields',
                callback: function(arg) {}
            }, config);

            var do_destroy = function(_config, id)
            {
                loadBlock('Sedang menghapus data');
                var dataSend = {};
                if (_config.data===null){
                    dataSend['id'] = id;
                }
                else
                {
                    dataSend['id'] = id;
                    $.each(_config.data, function(i, v) {
                        dataSend[i]=v;
                    });
                }
                $.ajax({
                    url: HELPER[config.api].destroy,
                    data: $.extend(dataSend,{csrf_spi: $.cookie('csrf_lock_spi')}),
                    type: _config.method,
                    success: function(response)
                    {
                        HELPER.showMessage({
                            success: response.success,
                            message: response.message,
                            title: ((response.success) ? 'Sukses' : 'Gagal')
                        });
                        unblock(100);
                    },
                    error: function(response, status, errorname)
                    {
                        HELPER.showMessage({
                            success: false,
                            title: 'Gagal melakukan operasi',
                            message: errorname,
                        });
                        unblock(100);
                    },
                    complete: function(response)
                    {
                        var rsp = $.parseJSON(response.responseText);
                        config.callback(rsp.success,rsp.id,rsp.record,rsp.message);
                    },
                })
            }
            
            var do_destroy_multiple = function(_config, data)
            {
                var dataSend = {};
                $.each(data,function(i,v){
                    dataSend[i] = v;
                });
                loadBlock('Sedang menghapus data');
                $.ajax({
                    url: config.url,
                    data: $.extend(dataSend,{csrf_spi: $.cookie('csrf_lock_spi')}),
                    type: _config.method,
                    success: function(response)
                    {
                        HELPER.showMessage({
                            success: response.success,
                            message: response.message,
                            title: ((response.success) ? 'Sukses' : 'Gagal')
                        });
                        unblock(100);
                    },
                    error: function(response, status, errorname)
                    {
                        HELPER.showMessage({
                            success: false,
                            title: 'Gagal melakukan operasi',
                            message: errorname,
                        });
                        unblock(100);
                    },
                    complete: function(response)
                    {
                        var rsp = $.parseJSON(response.responseText);
                        config.callback(rsp.success,rsp.id,rsp.record,rsp.message);
                    },
                })
            }
            if (config.multiple===false)
            {
                var data = null;
                $("#" + config.table).find('input[name=checkbox]').each(function(key, value)
                {
                    if ($(value).is(":checked")) {
                        data = $.parseJSON(atob($(value).data('record')));
                    }
                });
                if (data !== null)
                {
                    var id = data[HELPER[config.fields][0]];
                    if (config.confirm)
                    {
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
                                if (result===true) {
                                    do_destroy(config, id);
                                }
                            }
                        });
                    }
                    else
                    {
                        do_destroy(config, id);
                    }
                }
                else
                {
                    HELPER.showMessage({
                        title: 'Informasi',
                        message: 'Anda belum memilih data pada tabel...!',
                        image: './assets/img/information.png',
                        time: 2000
                    })
                }
            }
            else
            {
                var data = [];
                $("#" + config.table).find('input[name=checkbox]').each(function(key, value)
                {
                    if ($(value).is(":checked")) {
                        var cek   = $.parseJSON(atob($(value).data('record')));
                        data[key] = cek;
                    }
                });

                if (data.length>0)
                {
                    if (config.confirm)
                    {
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
                                if (result===true) {
                                    do_destroy_multiple(config, data);
                                }
                            }
                        });
                    }
                    else
                    {
                        do_destroy_multiple(config, data);
                    }
                }
                else
                {
                    HELPER.showMessage({
                        title: 'Informasi',
                        message: 'Anda belum memilih data pada tabel...!',
                        image: './assets/img/information.png',
                        time: 2000
                    })
                }
            }
        },

        getDataFromTable: function(config)
        {
            config = $.extend(true, {
                table: null,
                multiple: false,
                callback: function(args){}
            }, config);
            var data = '';
            var multidata = [];

            $("#"+config.table).find('input[name=checkbox]').each(function(key, value) {
                if ($(value).is(":checked")) {
                    if(config.multiple){
                        multidata.push($.parseJSON(atob($(value).data('record'))));
                    }else{
                        data = $.parseJSON(atob($(value).data('record')));
                    }
                }
            });
            if(config.multiple){
                config.callback(multidata);
            }else{
                config.callback(data);
            }
        },

        saveMultiple: function(config)
        {
            config = $.extend(true, {
                url: null,
                table: null,
                confirm: true,
                method: 'POST',
                data: null,
                message: true,
                callback: function(arg) {},
                success: function(arg) {},
                error: function(arg) {},
                complete: function(arg) {},
                cache: false,
                contentType: false,
                processData: false,
                xhr: null,
            }, config);

            var sentData = function(_config, data)
            {
                var dataSend = {};
                var localdataSend = {};
                var xdataSend = {};

                if (config.data===null){
                    $.each(data.server,function(i,v){
                        dataSend[i] = v;
                    });
                    xdataSend = dataSend;
                }else{
                    $.each(data.server,function(i,v){
                        dataSend[i] = v;
                    });
                    $.each(data.local,function(i,v){
                        localdataSend[i] = v;
                    });
                    xdataSend['server'] = dataSend;
                    xdataSend['data'] = localdataSend;
                }

                loadBlock('');
                $.ajax({
                    url: config.url,
                    data: $.extend(xdataSend,{csrf_spi: $.cookie('csrf_lock_spi')}),
                    type: config.method,
                    cache: config.cache,
                    /*contentType: config.contentTypes,
                    processData: config.processDatas,*/
                    xhr: (config.xhr===null) ? function() {
                        var myXhr = $.ajaxSettings.xhr();
                        return myXhr;
                    } : config.xhr,
                    success: function(response)
                    {
                        if (config.message==false) {
                            config.success(response);
                        }else{
                            config.success(response);
                            HELPER.showMessage({
                                success: response.success,
                                message: response.message,
                                title: ((response.success) ? 'Sukses' : 'Gagal')
                            });
                        }
                    },
                    error: function(response, status, errorname)
                    {
                        if (config.message==false) {
                            config.error(response, status, errorname);
                        }else{
                            config.error(response, status, errorname);
                            HELPER.showMessage({
                                success: false,
                                title: 'Gagal melakukan operasi',
                                message: errorname,
                            });
                        }
                    },
                    complete: function(response)
                    {
                        var rsp = $.parseJSON(response.responseText);
                        config.callback(rsp.success,rsp.id,rsp.record,rsp.message,rsp);
                        unblock(1000);
                    },
                });
            }

            var data = [];
            var xdata = [];
            $("#" + config.table).find('input[name=checkbox]').each(function(key, value)
            {
                if ($(value).is(":checked")) {
                    var cek = null;
                    if ($(value).val().length==32){
                        cek = $(value).val();
                    }else{
                        var cek   = $.parseJSON(atob($(value).data('record')));
                    }
                    data[key] = cek;
                }
                xdata['server'] = data;
                xdata['local']  = config.data;
            });
            if (xdata.server.length>0){
                if (config.confirm)
                {
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
                            if (result===true) {
                                sentData(config, xdata);
                            }
                        }
                    });
                }
                else
                {
                    sentData(config, xdata);
                }
            }
        },

        setRowDataTable: function(config)
        {
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
                from: 1,
                callbackClick: function () { },
            }, config);
            var str = HELPER.nullConverter(config.text)
            var btn_click = "";
            var btn_click_reverse = "";
            if (str.length > config.length) {
                try {
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
                } catch (e) {
                    // console.log(e);
                }
            }
            var temp_span = `${str}`;
            return temp_span;
        },

        clickTextMore: function (el) {
            if ($(el).data().hasOwnProperty('config')) { var config = JSON.parse(atob($(el).data('config'))); config.from = 0; $(el).parent().html(HELPER.textMore(config)) }
        },

        loadData: function(config) {
            config = $.extend(true, {
                debug: false,
                table: null,
                type: 'POST',
                url: null,
                server: false,
                data: null,
                fields: 'fields',
                before_load: function() {},
                after_load: function() {},
                callback: function(arg) {}
            }, config);
            config.before_load();
            loadBlock('Sedang menampilkan data');
            if (config.server===true)
            {
                var datalocal = [];
                $("#" + config.table).find('input[name=checkbox]').each(function(key, value)
                {
                    if ($(value).is(":checked")) {
                        datalocal          = $.parseJSON(atob($(value).data('record')));
                        datalocal['id']    = datalocal[HELPER.fields[0]];
                        datalocal['data']  = config.data;

                        $.ajax({
                            url: config.url,
                            data: $.extend(datalocal,{csrf_spi: $.cookie('csrf_lock_spi')}),
                            type: config.type,
                            success: function(response)
                            {},
                            complete: function(data_response)
                            {
                                var response   = $.parseJSON(data_response.responseText);
                                var dataserver = {};
                                if (response.constructor===Object)
                                {
                                    dataserver = response;
                                }
                                else if (response.constructor===Array)
                                {
                                    dataserver = response[0];
                                }
                                if (dataserver !==null) 
                                {
                                    $.when(function(){
                                        $.each(dataserver,function(i,v)
                                        {
                                            if ($("[name="+ i +"]").find("option:selected").length)
                                            {
                                                if (!$($("[name="+ i +"]")).data('select2'))
                                                {
                                                    $("[name=" + i + "]").val(v);
                                                }
                                                else
                                                {
                                                    $('[name="'+ i +'"]').select2('val',v);
                                                }
                                            }
                                            else if ($("[name=" + i + "]").attr('type') == 'checkbox') 
                                            {
                                                $('[name="' + i + '"][value="' + v + '"]').prop('checked', true);
                                            }
                                            else if ($("[name=" + i + "]").attr('type') == 'radio')
                                            {
                                                $('[name="' + i + '"][value="' + v + '"]').prop('checked', true);
                                            }
                                            else if ($("[name='"+i+"']").attr('type') == 'file')
                                            {
                                                $("[name=" + i + "]").val("");
                                            }
                                            else
                                            {
                                                $("[name=" + i + "]").val(html_entity_decode(v))
                                            }
                                        })

                                        if (Object.keys(datalocal).length>0) {
                                            $.each(datalocal,function(i,v)
                                            {
                                                if ($("[name=" + i + "]").attr('type') == 'checkbox') 
                                                {
                                                    $('[name="' + i + '"][value="' + v + '"]').prop('checked', true);
                                                }
                                                else if ($("[name=" + i + "]").attr('type') == 'radio')
                                                {
                                                    $('[name="' + i + '"][value="' + v + '"]').prop('checked', true);
                                                }
                                                else
                                                {
                                                    $("[name=" + i + "]").val(html_entity_decode(v))
                                                }
                                            })
                                        }
                                        config.callback(datalocal,dataserver);
                                        return dataserver;
                                    }()).done(unblock(100))
                                }
                                else
                                {
                                    $.when(unblock(100)).then(function(){
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
                        if (config.debug){}
                    }
                });
            }
            else
            {
                var data = null;
                $("#" + config.table).find('input[name=checkbox]').each(function(key, value) {
                    if ($(value).is(":checked")) {
                        data = $.parseJSON(atob($(value).data('record')));
                        if (config.debug) {}
                    }
                });
                if (data !== null) {
                    $.when(function(){
                        HELPER[config.fields].forEach(function(v, i, a) {
                            if ($("[name="+ v +"]").find("option:selected").length)
                            {
                                if (!$($("[name="+ v +"]")).data('select2'))
                                {
                                    $("[name=" + v + "]").val(data[v]);
                                }
                                else
                                {
                                    $('[name="'+ v +'"]').select2('val',data[v]);
                                }
                            }
                            else if ($("[name=" + v + "]").attr('type') == 'checkbox') 
                            {
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
                    $.when(unblock(500)).then(function(){
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

        ajaxCombox: function(config) {
            config = $.extend(true, {
                el      : null,
                limit   : 30,
                url     : null,
                tempData: null,
                data    : {},
                placeholder : null,
                displayField: null,
                displayField2: null,
                displayField3: null,
                allowClear: true,
                grouped: false,
                selected:null,
                multiple: false,
                callback: function(res) {}
            }, config);
            var myQ = new Queue();
            myQ.enqueue(function (next) {
                $(config.el).select2({
                    allowClear: config.allowClear,
                    multiple: config.multiple,
                    // ajax: {
                    //     url : config.url,
                    //     dataType : 'json',
                    //     type : 'post',
                    //     allowClear: config.allowClear,
                    //     // use this for select2 v. 3.5.1
                    //     data: function (data, page) {
                    //         // var search = null;
                    //         // if (params.term==null && config.sea) {}
                             
                    //       return {
                    //         q   : data, // search term
                    //         page : page,
                    //         limit : config.limit,
                    //         fdata : config.data,
                    //         selectedId: (config.selected != null ? config.selected.id : null),
                    //         csrf_spi: $.cookie('csrf_lock_spi'),
                    //       };
                    //     },
                    //     // data: function (params) {
                    //     //     // var search = null;
                    //     //     // if (params.term==null && config.sea) {}
                             
                    //     //   return {
                    //     //     q   : params.term, // search term
                    //     //     page : params.page,
                    //     //     limit : config.limit,
                    //     //     fdata : config.data,
                    //     //     selectedId: (config.selected != null ? config.selected.id : null),
                    //     //     csrf_spi: $.cookie('csrf_lock_spi'),
                    //     //   };
                    //     // },
                    //     // use this for select2 v. 3.5.1
                    //     results: function (data, page) {
                    //         page = page || 1;
                    //         return {
                    //           results: data.items,
                    //           pagination: {
                    //             more: (page * config.limit) < data.total_count
                    //           }
                    //         };
                    //     },
                    //     // processResults: function (data, params) {
                    //     // },
                    //     cache: true
                    // },
                    query: function(query) {
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
                            success: function(data) {
                                var more = (query.page * 10) < data.total_count;
                                query.callback({
                                    results: data.items,
                                    more: more
                                });
                            }
                        });
                    },
                    placeholder: (config.placeholder?config.placeholder:'- Choose -'),
                    minimumInputLength: 0,
                    // templateSelection: function (data, container) {
                    // for select2 3.5.1
                    formatSelection: function (data, container) {
                        $(data.element).attr('data-temp', data.saved);
                        if (config.tempData != null) {
                            $.each(config.tempData, function(i, v) {
                                $(data.element).attr('data-'+v.key, v.val);                        
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
                            }else{
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
                            }else{
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
                                data: {selectedId: id},
                                complete: function(data) {
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
                            $.each(config.selected, function(i,v){
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
                            data: {selectedId: config.selected},
                            complete: function(data) {
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

        ajaxCombo: function(config) {
            config = $.extend(true, {
                el      : null,
                limit   : 30,
                url     : null,
                tempData: null,
                data    : {},
                placeholder : null,
                displayField: null,
                displayField2: null,
                displayField3: null,
                grouped: false,
                selected:null,
                callback: function(res) {}
            }, config);
            var myQ = new Queue();

            myQ.enqueue(function (next) {
                $(config.el).select2({
                    ajax: {
                        url : config.url,
                        dataType : 'json',
                        type : 'post',
                        data: function (params) {
                          return {
                            csrf_spi: $.cookie('csrf_lock_spi'),
                            q   : params.term, // search term
                            page : params.page,
                            limit : config.limit,
                            fdata : config.data,
                            selectedId: (config.selected != null ? config.selected.id : null)
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
                    placeholder: (config.placeholder?config.placeholder:'- Choose -'),
                    minimumInputLength: 0,
                    templateSelection: function (data, container) {
                        $(data.element).attr('data-temp', data.saved);
                        if (config.tempData != null) {
                            $.each(config.tempData, function(i, v) {
                                $(data.element).attr('data-'+v.key, v.val);                        
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
                            }else{
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
                            }else{
                                display = data[config.displayField];
                            }
                        }

                        return display;
                    }
                });
                next()
            }, 'pertama').enqueue(function (next) {
                if (config.selected) {
                    var option = new Option(config.selected.name, config.selected.id, true, true);
                    $(config.el).append(option).trigger('change');
                }
                next()
            }, 'kedua').dequeueAll()
        },

        createCombo: function(config) {
            config = $.extend(true, {
                el: null,
                valueField: null,
                valueGroup: null,
                displayField: null,
                displayField2: null,
                displayField3: null,
                url: null,
                grouped: false,
                withNull : true,
                data : null,
                chosen : false,
                value: null, 
                callback: function() {}
            }, config);

            var myQueue = new Queue();
            myQueue.enqueue(function(next) {
                if (config.url !== null){
                    $.ajax({
                        url: config.url,
                        data : $.extend(config.data,{csrf_spi: $.cookie('csrf_lock_spi')}),
                        type:'POST',
                        complete: function(response) {
                            var html = (config.withNull === true) ? "<option value>-Pilih-</option>" : "";    
                            var data = $.parseJSON(response.responseText);
                            if (data.success) {
                                $.each(data.data, function(i, v) {
                                    if (config.grouped) {
                                        if (config.displayField3!=null){
                                            html += "<option value='" + v[config.valueField] + "'>" + v[config.displayField2] + " - " + v[config.displayField] + " ( "+ v[config.displayField3] +" ) " + "</option>";
                                        }else{
                                            html += "<option value='" + v[config.valueField] + "'>" + v[config.displayField2] + " - " + v[config.displayField] + "</option>";
                                        }
                                    } else {
                                        html += "<option value='" + v[config.valueField] + "'>" + v[config.displayField] + "</option>";
                                    }
                                });
                                if (config.el.constructor === Array){
                                    $.each(config.el,function(i,v){
                                        $('#'+v).html(html);
                                    })
                                }else{
                                    $('#' + config.el).html(html);
                                }
                                if (config.chosen){
                                    if (config.el.constructor === Array){
                                        $.each(config.el,function(i,v){
                                            $('#'+v).addClass(v);
                                            $('.'+v).select2({
                                                allowClear: true,
                                                dropdownAutoWidth : true,
                                                // width: 'auto',
                                                placeholder: "-Pilih-",
                                            });
                                        })
                                    }else{
                                        $('#' + config.el).addClass(config.el);
                                        $('.'+ config.el).select2({
                                            allowClear: true,
                                            dropdownAutoWidth : true,
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
                    var response = {success:false,message:'Url kosong'};
                    config.callback(response);
                }
                next()
            }, 'first').enqueue(function(next) {
                if (config.value != null)
                setTimeout(() => {
                    if (config.chosen) {
                        $('#' + config.el).val(config.value).trigger('change.select2');
                    } else {
                        $('#' + config.el).val(config.value).trigger('change');
                    }
                }, 600);
            }, 'second').dequeueAll();
        },

        createGroupCombo: function(config) {
            config = $.extend(true, {
                el: null,
                valueField: null,
                valueGroup: null,
                displayField: null,
                url: null,
                grouped: false,
                withNull : true,
                data : null,
                chosen : false,
                callback: function() {}
            }, config);

            if (config.url !== null){
                $.ajax({
                    url: config.url,
                    data : $.extend(config.data,{
                        csrf_spi: $.cookie('csrf_lock_spi'),
                        id: config.valueField,
                        id_group: config.valueGroup,
                    }),
                    type:'POST',
                    complete: function(response) {
                        var data = $.parseJSON(response.responseText);
                        var html = (config.withNull === true) ? "<option value>-Pilih-</option>" : "";
                        if (data.success) {
                            if (config.grouped) {
                                $.each(data.data,function(i,v){
                                    html +='<optgroup label="'+i+'" style="font-wight:bold;">';
                                        $.each(v,function(i2,v2){
                                            html += '<option value="'+v2[config.valueField]+'">'+v2[config.displayField]+'</option>';
                                        });
                                    html +='</optgroup>';
                                });
                            }else{

                            }

                            if (config.el.constructor === Array){
                                $.each(config.el,function(i,v){
                                    $('#'+v).html(html);
                                })
                            }else{
                                $('#' + config.el).html(html);
                            }

                            if (config.chosen){
                                if (config.el.constructor === Array){
                                    $.each(config.el,function(i,v){
                                        $('#'+v).addClass(v);
                                        $('select.'+v).select2({
                                            allowClear: true,
                                            dropdownAutoWidth : true,
                                            // width: 'auto',
                                            placeholder: "-Pilih-",
                                        });
                                    })
                                }else{
                                    $('#' + config.el).addClass(config.el);
                                    $('select.'+ config.el).select2({
                                        allowClear: true,
                                        dropdownAutoWidth : true,
                                        // width: 'auto',
                                         placeholder: "-Pilih-",
                                    });
                                }
                            }

                        }
                        config.callback(data);
                    }
                });
            }else{
                var response = {success:false,message:'Url kosong'};
                config.callback(response);
            }
        },

        createChangeCombo: function(config)
        {
            config = $.extend(true,{
                el:null,
                data:null,
                url:null,
                reset:null,
                callback: function() {}
            },config);

            $('#'+config.el).change(function(){
                var id = $(this).val();
                var data = {};
                if (config.reset!==null){
                    $('[name="'+config.reset+'"]').val("").select2("");
                    // $("[name="+config.reset+"]").select2().val("");
                }if (config.data===null){
                    data['id'] = id;
                }else{
                    data = config.data;
                    data['id'] = id;
                }
                $.ajax({
                    url: config.url,
                    // data: data,
                    data: $.extend(data,{csrf_spi: $.cookie('csrf_lock_spi')}),
                    type: 'POST',
                    complete: function(response){
                        var rsp = $.parseJSON(response.responseText);
                        config.callback(rsp.success,id,rsp.data,rsp.total,response);
                    },
                    callback: function(arg){}
                });
            });
        },

        setChangeCombo: function(config)
        {
            config = $.extend(true,{
                el: null,
                data: null,
                valueField: null,
                displayField: null,
                displayField2: null,
                grouped : false,
                withNull : true,
                idMode : false,
                chosen: false,
                value: null
            },config);
            
            if(config.idMode === true)
            {
                var html = (config.withNull === true) ? "<option value>-Pilih-</option>" : "";
                $.each(config.data, function(i, v) {                    
                    if (config.grouped) {
                        if (config.displayField3!=null)
                        {
                            html += "<option value='" + v[config.valueField] + "'>" + v[config.displayField2] + " - " + v[config.displayField] + " ( "+ v[config.displayField3] +" ) " + "</option>";
                        }
                        else
                        {
                            html += "<option value='" + v[config.valueField] + "'>" + v[config.displayField2] + " - " + v[config.displayField] + "</option>";
                        }
                    } else {
                        html += "<option value='" + v[config.valueField] + "'>" + v[config.displayField] + "</option>";
                    }
                });
                $('#' + config.el).html(html);
            }
            else
            {
                var html = (config.withNull === true) ? "<option value>-Pilih-</option>" : "";
                $.each(config.data, function(i, v) {                    
                    if (config.grouped) {
                        if (config.displayField3!=null)
                        {
                            html += "<option value='" + v[config.valueField] + "'>" + v[config.displayField2] + " - " + v[config.displayField] + " ( "+ v[config.displayField3] +" ) " + "</option>";
                        }
                        else
                        {
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
                    allowClear: true
                });
                if (config.value != null) {
                    setTimeout(function() {
                        $('#' + config.el).val(config.value).trigger('change.select2');
                    }, 500);
                }
            } else {
                if (config.value != null) {
                    setTimeout(function() {
                        $('#' + config.el).val(config.value).trigger('change');
                    }, 500);
                }
            }
        },

        ajax: function(config)
        {
            config = $.extend(true,{
                data: null,
                url: null,
                type: "POST",
                dataType: null,
                beforeSend: function(){},
                success: function(){},
                complete: function(){},
                error: function(){}
            },config);
            $.ajax({
                url: config.url,
                data: $.extend(config.data,{csrf_spi: $.cookie('csrf_lock_spi')}),
                type: config.type,
                dataType: config.dataType,
                xhr: config.xhr,
                beforeSend: function(data){
                    config.beforeSend(data);
                },
                success: function(data){
                    config.success(data);
                },
                complete: function(response){
                    var rsp = $.parseJSON(response.responseText);
                    config.complete(rsp,response);
                },
                error: function(error){
                    error.error(error);
                },
            })
        },

        showMessage: function(config)
        {
            config = $.extend(true, {
                success: false,
                message: 'Kesalahan Sistem, hubungi Administrator',
                title: 'Gagal',
                time: 5000,
                sticky: false,
                image: ((config.success) ? './assets/img/success.png' : './assets/img/error.png'),
                callback: function() {},
            }, config);

            toastr.options = {
                "debug": false,
                "positionClass": "toast-bottom-right",
                "onclick": null,
                "fadeIn": 300,
                "fadeOut": 100,
                "timeOut": 5000,
                "extendedTimeOut": 1000
            }
            if (config.success) {
                toastr.success(config.message, config.title);
            }else{
                toastr.error(config.message, config.title);
            }
            config.callback();
        },

        handleValidation: function(config){

            config = $.extend(true,{
                el: null,
                rules: null,
                callback: function(arg){}
            },config);

            $.each(config.rules, function(i,v){
                if (v.required) {
                    $("[name=" + i + "]").parents('.form-group').children('.control-label').append('<span class="required">* </span>')
                }
            });

            var form = $('#'+config.el);
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            //IMPORTANT: update CKEDITOR textarea with actual content before submit
            form.on('submit', function() {
                for(var instanceName in CKEDITOR.instances) {
                    CKEDITOR.instances[instanceName].updateElement();
                }
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
            $("#select2_tags").change(function() {
                form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input 
            }).select2({
                tags: ["red", "green", "blue", "yellow", "pink"]
            });

            //initialize datepicker
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                autoclose: true
            });
            $('.date-picker .form-control').change(function() {
                form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input 
            })
        },

        setRequired: function(el)
        {
            $(el).each(function(i, v) {
                $("[name=" + v + "]").attr('required', true).parents('.form-group').children('.col-form-label').append('<span aria-required="true" style="color: red;"> *</span>')
            })
        },

        print: function(config)
        {
            config = $.extend(true,{
                el: 'bodylaporan',
                page: null,
                csslink: null,
                historyprint: null,
                callback: function(){}
            },config);

            var contents = (config.el.length > 32) ? config.el : $("#"+config.el).html();
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
            if (config.csslink!=null)
            {
                if (config.csslink.constructor === Array)
                {
                    $.each(config.csslink,function(i,v){
                        frameDoc.document.write('<link href="'+v+'" rel="stylesheet" type="text/css" />');
                    })
                }
                else
                {
                    frameDoc.document.write('<link href="'+config.csslink+'" rel="stylesheet" type="text/css" />');
                }
            }
            frameDoc.document.write(contents);
            frameDoc.document.write('</body></html>');
            frameDoc.document.close();
            if (config.historyprint!=null)
            {
                $.ajax({
                    url : config.historyprint,
                    data: {
                        csrf_spi: $.cookie('csrf_lock_spi')
                    },
                    success : function(response){},
                    complete: function(response)
                    {
                        var rsp = $.parseJSON(response.responseText);
                        config.callback(rsp.success,id,rsp.data,rsp.total);
                    },
                    callback: function(arg){}
                });
            }
            setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                frame1.remove();
            }, 300);
        },

        confirm: function(config)
        {
            config = $.extend(true,{
                title: 'Informasi',
                message: null,
                confirmLabel: '<i class="fa fa-check"></i> Ya',
                confirmClassName: 'btn-success',
                cancelLabel: '<i class="fa fa-times"></i> Tidak',
                cancelClassName: 'btn-danger',
                callback: function(){}
            },config);
            bootbox.confirm({
                title: config.title,
                message: config.message,
                buttons: {
                    confirm: {
                        label: config.confirmLabel,
                        className: confirm.confirmClassName
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

        prompt: function(config)
        {
            config = $.extend(true,{
                title: null,
                inputType: null,
                confirmLabel: '<i class="fa fa-check"></i> Ya',
                confirmClassName: 'btn-success',
                cancelLabel: '<i class="fa fa-times"></i> Tidak',
                cancelClassName: 'btn-danger',
                inputOptions: null,
                size: null,
                callback: function(){}
            },config);

            bootbox.prompt({
                title: (config.title!=null) ? config.title : 'Informasi' ,
                inputType: config.inputType,
                inputOptions: (config.inputOptions!=null) ? config.inputOptions : null,
                size: (config.size!=null) ? config.size : null,
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

        toExcel: function(config)
        {
            config = $.extend(true,{
                el: null,
                title: null,
            },config);

            if (config.el.constructor===Array) {
                $.each(config.el,function(i,v){
                    if (i==0) {
                        tableToExcel(v,config.title);
                    }else{
                        tableToExcel(v,config.title+'-'+(i+2));
                    }
                });
            }else{
                tableToExcel(config.el,config.title);
            }
        },

        toWord: function(config)
        {
            config = $.extend(true,{
                el: null,
                title: null,
                paperSize: null,
                style: null,
                margin: null,
            },config);

            var html, link, blob, url, css, margin;
            margin = (config.margin!=null) ? config.margin : '1cm 1cm 1cm 1cm';
            css = (
                '<style>' +
                    '@page '+config.el+'{size: '+paperSize(config.paperSize)+'; margin: '+margin+';}' +
                    'div.'+config.el+' {page: '+config.el+';} '+config.style+
                '</style>'
            );
            
            html = window.$('#'+config.el).html();
            blob = new Blob(['\ufeff', css + html], { type: 'application/msword;charset=utf-8' });
            url = URL.createObjectURL(blob);
            link = document.createElement('A');
            link.href = url;
            // Set default file name. 
            // Word will append file extension - do not add an extension here.
            link.download = config.title;   
            document.body.appendChild(link);
            if (navigator.msSaveOrOpenBlob ) navigator.msSaveOrOpenBlob( blob, config.title+'.doc'); // IE10-11
              else link.click();  
            document.body.removeChild(link);
        },

        toDecimal : function(value, comma=''){
            var val = value;
            if ($.isNumeric(val)) {
                if (val != null) {
                    _val = val.toString();
                    val = _val.replace(/[^0-9\.-]/g,'');
                    val = parseFloat(val);
                }
                if (val != 0) {
                    return val.toLocaleString("de-DE",{
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

        getExt: function(fileName){
            var _ext = fileName.substr((fileName.lastIndexOf('.') + 1));
            var ext  = _ext.toLowerCase();
            var data = {
                image   : ['jpg','png','jpeg','gif','bmp'],
                office  : ['doc','docx','xls','xlsx','ppt','pptx'],
                pdf     : ['pdf'],
                vidio   : ['webm','mpeg4','3gpp','mov','mwv','mkv','flv','avi','mp4','m4p','mpg','mpeg','3gp']
            };

            var search = ext;
            var type = '';
            $.each(data, function(i,v){
                $.each(v, function(i2,v2){
                    if(v2 == search) {
                        type = i;
                    }
                })
            });
            return {
                type: type,
                ext: ext
            };
        },

        tinymce: function($class){
            tinymce.init({
                selector: "textarea#"+$class,
                theme: "silver",
                height: 300,
                plugins: [
                    "advlist autolink link image media filemanager code responsivefilemanager"
                ],
                toolbar: "undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | outdent indent | forecolor backcolor | image media | link unlink | code",
                image_advtab: true,
                external_filemanager_path: "./ng/",
                filemanager_title: "Responsive Filemanager",
                external_plugins: {
                    // "responsivefilemanager": "../../tinymce/plugins/responsivefilemanager/plugin.min.js",
                    "filemanager": "https://tiams.pttimah.co.id/ng/plugin.min.js"
                },
            });
        },

        setRole: function(){
            $.each(__role, function(i,v){
                $('.xremove_button[data-role="'+v.menu_kode+'"]').removeClass('hide');
            });
        },

        createFilter: function(config) {
            localStorage.removeItem("shortcut_id");
            config = $.extend(true,{
                el: null,
                table: null,
            },config);

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
                    $('#'+config.table)
                        .DataTable()
                        .column(column)
                        .search(val, false, false)
                        .draw();
                });

            });
        },

        bytesToSize: function(bytes) {
           var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
           if (bytes == 0) return '0 Byte';
           var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
           return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
        },
    
        editor: function(config)
        {
            config = $.extend(true,{
                id: null,
                placeholder: null,
                height: 80,
                readonly: false,
                value: null,
                toolbar: ['clipboard','font'],
            },config);

            var toolbar_list = [];

                toolbar_list['clipboard'] =  {name: 'clipboard', items : [ 'Undo','Redo' ]};
                toolbar_list['template'] =  {name: 'Template', items : [ 'Templates' ]};
                toolbar_list['font'] =  {name: 'Font', items: [ 'Font', 'FontSize', 'Bold', 'Italic', 'Underline', '-', 'TextColor']};
                toolbar_list['style'] =  {name: 'Style', items: [ 'Format']};
                toolbar_list['tools'] =  {name: 'tools', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']};
                toolbar_list['paragraph'] =  {name: 'paragraph',
                    items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'BGColor' ],
                    groups: [ 'list', 'indent', 'blocks', 'align', 'bidi']
                };
                toolbar_list['links'] =  {name: 'links', items : [ 'Table' ]};
                toolbar_list['insert'] =  {name: 'insert', items: [ 'Image', 'Video']};
                toolbar_list['maximize'] =  {name: 'Maximize', items : [ 'Maximize' ]};

            var toolbar = [];
                if (config.toolbar.length > 0) {
                    $.each(config.toolbar, function(i,v) {
                        toolbar.push(toolbar_list[v]);
                    });
                }

                if (config.placeholder != null) {
                    var div            = document.createElement("div");
                    div.innerHTML      = config.placeholder;
                    config.placeholder = div.innerHTML;
                }

                CKEDITOR.replace(config.id, {
                    filebrowserBrowseUrl : 'ng/dialog.php?type=2&editor=ckeditor&fldr=',
                    filebrowserUploadUrl : 'ng/dialog.php?type=2&editor=ckeditor&fldr=',
                    filebrowserImageBrowseUrl : 'ng/dialog.php?type=1&editor=ckeditor&fldr=',
                    removeDialogTabs: 'image:Link;image:advanced;image:Upload',
                    extraPlugins: 'editorplaceholder',
                    editorplaceholder: (config.placeholder != null)? config.placeholder:'',
                    height: config.height,
                    toolbar: toolbar
                });

                CKEDITOR.on('instanceReady', function() {
                    if (config.value != null) CKEDITOR.instances[config.id].setData(config.value);
                    CKEDITOR.instances[config.id].setReadOnly(config.readonly);
                });
        },

        editorValue: function(config)
        {
            config = $.extend(true,{
                id: null,
                value: null,
            },config);

            CKEDITOR.on('instanceReady', function() {
                if (config.value != null) CKEDITOR.instances[config.id].setData(config.value);
            });
        },

        editorReadOnly: function(config)
        {
            config = $.extend(true,{
                id: null,
                readonly: true,
            },config);

            CKEDITOR.on('instanceReady', function() {
                CKEDITOR.instances[config.id].setReadOnly(config.readonly);
            });
        }
    }
}();

$.fn.serializeObject = function()
{
   var o = {};
   var a = this.serializeArray();
   $.each(a, function() {
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

$.fn.randBetween = function (min,max)
{
    return Math.floor(Math.random()*(max-min+1)+min);
};

var tableToExcel = (function() {
    var uri = 'data:application/vnd.ms-excel;base64,'
        , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
        , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
        , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
    return function(table, name) {
        if (!table.nodeType) table = document.getElementById(table)
        var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
        // window.location.href = uri + base64(format(template, ctx))
        var dataFormat = uri + base64(format(template, ctx));
        var $a = $("<a>");
        $a.attr("href",dataFormat);
        $('body').append($a);
        $a.attr("download",name+'.xls');
        $a[0].click();
        $a.remove();
    }
})();

function paperSize(data_tipe)
{
    var tipe = data_tipe.toUpperCase();
    switch(tipe) {
        case 'A4' :     return '21cm 29.7cm';       break;
        case 'LETTER':  return '21.6cm 27.9cm';     break;
        case 'LEGAL' :  return '21.6cm 35.6cm';     break;
        case 'FOLIO' :  return '21.5cm 33.0cm';     break;
        case 'A0' : return '84.1cm 118.9cm';    break;
        case 'A1' : return '59.4cm 84.1cm';     break;
        case 'A2' : return '42.0cm 59.4cm';     break;
        case 'A3' : return '29.7cm 42.0cm';     break;
        case 'A4' : return '21.0cm 29.7cm';     break;
        case 'A5' : return '14.8cm 21.0cm';     break;
        case 'A6' : return '10.5cm 14.8cm';     break;
        case 'A7' : return '7.4cm 10.5cm';      break;
        case 'A8' : return '5.2cm 7.4cm';       break;
        case 'A9' : return '3.7cm 5.2cm';       break;
        case 'A10': return '2.6cm 3.7cm';       break;
        case 'B0' : return '100.0cm 141.4cm';   break;
        case 'B1' : return '70.7cm 100.0cm';    break;
        case 'B2' : return '50.0cm 70.7cm';     break;
        case 'B3' : return '35.3cm 50.0cm';     break;
        case 'B4' : return '25.0cm 35.3cm';     break;
        case 'B5' : return '17.6cm 25.0cm';     break;
        case 'B6' : return '12.5cm 17.6cm';     break;
        case 'B7' : return '8.8cm 12.5cm';      break;
        case 'B8' : return '6.2cm 8.8cm';       break;
        case 'B9' : return '4.4cm 6.2cm';       break;
        case 'B10' : return '3.1cm 4.4cm';      break;
    }
}


Number.prototype.round = function(places) {
    return +(Math.round(this + "e+" + places)  + "e-" + places);
}

function Queue(){
    this.queue = [];
}

Queue.prototype = {
    constructor: Queue,
    enqueue: function (fn, queueName) {
        this.queue.push({
            name: queueName || 'global',
            fn: fn || function (next) {
                next()
            }
        });
        return this
    },
    dequeue: function (queueName) {
        var allFns = (!queueName) ? this.queue : this.queue.filter(function (current) {
            return (current.name === queueName)
        });
        var poppedFn = allFns.pop();
        if (poppedFn) poppedFn.fn.call(this);
        return this
    },
    dequeueAll: function (queueName) {
        var instance = this;
        var queue = this.queue;
        var allFns = (!queueName) ? this.queue : this.queue.filter(function (current) {
            return (current.name === queueName)
        });
        (function recursive(index) {
            var currentItem = allFns[index];
            if (!currentItem) return;
            currentItem.fn.call(instance, function () {
                queue.splice(queue.indexOf(currentItem), 1);
                recursive(index)
            })
        }(0));
        return this
    }
};

/*maps helper*/
var map,map1,mapadi
    coords = new Array(),
    polyline = null,
    markers = new Array(),
    polylines = new Array(),
    tileServer = $("#app-mappath").val();

var MAPS = function(window, document, undefined) {
    return {
        init: function(config) {
            config.lat = (typeof config.lat !='undefined') ? config.lat : -7.8828315;
            config.lng = (typeof config.lng !='undefined') ? config.lng : 112.527768;
            var _config = $.extend({}, {
                center: L.latLng(config.lat, config.lng),
                zoom: null,
                minZoom: 4,
                maxZoom: 18,
                coordinatesInfo: true,
                trackResize: true,
                withUpdate : false,
                editable : true,
                zoomControl : false,
                editing : true,
                attribution : ''
            }, config);

            var map = L.map(_config.element, _config);
            // var url = 'http://' + tileServer + '/osm_tiles/{z}/{x}/{y}.png'; //offline
            var url = 'http://{s}.tile.osm.org/{z}/{x}/{y}.png'; //online
            var osmAttrib = '';
            var osm = new L.TileLayer(url, {
                minZoom: _config.minZoom,
                maxZoom: _config.maxZoom,
                // attribution: osmAttrib
                attribution: _config.attribution
            });
            map.setView(new L.LatLng(_config.lat, _config.lng), _config.zoom);
            map.addLayer(osm);
            // $(".leaflet-control-attribution.leaflet-control").hide();
            
            map.on('mousemove', function(event) {
                if (_config.coordinatesInfo) {
                    // $(".leaflet-top.leaflet-right").html(event.latlng.lat + ' , ' + event.latlng.lng);
                }
            });
            new L.Control.Zoom({ position: 'topright' }).addTo(map);

            //initialize update
            if (_config.withUpdate){
                window.updater = [];
                $.ajax({
                    url : BASE_URL+'sistem/mapupdater/select_updater_list',
                    data: {
                        csrf_spi: $.cookie('csrf_lock_spi')
                    },success : function(response){
                        $.each(response['data'],function(i,v){
                            var path = [];
                            $.each(JSON.parse(v.map_koordinat),function(ind, val){
                                path.push(L.latLng(val[0],val[1]));
                            })
                            window.updater.push(L.polyline(path, {opacity : 1,color : tinycolor(v.path_color).darken().darken(), weight:parseInt(v.path_thickness)+5}).addTo(map));
                            window.updater.push(L.polyline(path, {opacity : 1,color : v.path_color, weight:v.path_thickness}).addTo(map));                           
                        })
                    }
                })
                map.on('zoomend',function(event){
                    
                })
            }


            return map;
        },

        addMarker: function(config) {
            var _defaultIconMarker = function() {
                var icon = L.icon({
                    iconUrl: 'assets/leaflet/images/marker-icon.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41],
                });
                return icon;
            }
            var _config = $.extend({}, {
                point: null,
                icon: _defaultIconMarker(),
                markerId: '',
                markerName: '',
                koordinat : null,
                labelNohide : true,
                map : map,
            }, config);
            if (_config.label) {
                marker = new L.Marker([_config.point.lat, _config.point.lng], _config).bindLabel(_config.title,{noHide:_config.labelNohide}).addTo(_config.map);                
            } else {
                marker = new L.Marker([_config.point.lat, _config.point.lng], _config).addTo(_config.map);                                
            }
            if (_config.markerId) {
                $(".leaflet-marker-icon").last().prop('id', _config.markerId);
                $(".leaflet-marker-icon").last().prop('name', _config.markerName);
                $(".leaflet-marker-icon").last().attr('data-koordinat', _config.koordinat);
                $(".leaflet-marker-icon").css('border-radius',300);
            }
            markers.push(marker);
            return marker;
        },

        drawPath: function(config) {
            var _config = $.extend({}, {
                map: null,
                withMarker: true,
                markerId: '',
                markerName: '',
                point: null,
                color: '#FFFFFF'
            }, config);
            if (_config.withMarker) {
                this.addMarker(_config)
            }
            coords.push([_config.point.lat, _config.point.lng]);
            if (coords.length === 1) {
                polyline = L.polyline(coords, _config).addTo(_config.map);
            } else {
                polyline.addLatLng([_config.point.lat, _config.point.lng]);
            }
            polylines.push(polyline);
            return polyline;
        },

        resetMap: function(config) {
            var _config = $.extend({}, {
                map: null,
                polylines: polylines
            }, config);
            $.when(function() {
                if (polyline != null) {
                    $.each(_config.polylines, function() {
                        _config.map.removeLayer(this);
                    })
                }
                polyline = null;
                polylines = new Array();
                coords = new Array();
            }()).then(function() {
                $.each(markers, function() {
                    _config.map.removeLayer(this);
                })
                markers = new Array();
            }())
        },

        loadGPX: function(file) {
            var reader = new FileReader();      
            reader.onloadend = function(evt) {
                if (evt.target.readyState == FileReader.DONE) {
                    try{
                        xml = $.parseXML(evt.target.result);
                        var c = [];
                        $(xml).find('trkpt').each(function(i,v){
                            c.push([$(v).attr('lat'),$(v).attr('lon')]);
                        });
                        if (window.gpx) {
                            window.gpx.push(c);                 
                        } else {
                            window.gpx = [];
                            window.gpx.push(c);
                        }                     
                    } catch(e){
                         INFRA.showMessage({
                            message : 'Berkas yang dipilih bukan file GPX yang valid, pilih file yang lain.',
                            success : false,
                            image :  './assets/img/error.png',
                        });
                    }
                }
            }

            var files = $("#"+file)[0].files;
            if (files.length < 1) {
                INFRA.showMessage({
                    message : 'Pilih berkas GPX terlebih dahulu.',
                    success : false,
                    image :  './assets/img/error.png',
                });
            } else {
                reader.readAsBinaryString(files[0].slice(0,files[0].size));
            }           
        },

        findLayerById: function(_map,_id) {
            var retval = null;
            $.each(_map._layers,function(i,v){
                if (i === _id) {
                    retval = v;
                }
            })
            return retval;
        },

        convert2DMS : function (dms, type){
            var sign = 1, Abs=0;
            var days, minutes, seconds, direction;

            if(dms < 0)  { sign = -1; }
            Abs = Math.abs( Math.round(dms * 1000000.));
            //Math.round is used to eliminate the small error caused by rounding in the computer:
            //e.g. 0.2 is not the same as 0.20000000000284
            //Error checks
            if(type == "lat" && Abs > (90 * 1000000)){
                //alert(" Degrees Latitude must be in the range of -90. to 90. ");
                return false;
            } else if(type == "lon" && Abs > (180 * 1000000)){
                //alert(" Degrees Longitude must be in the range of -180 to 180. ");
                return false;
            }

            days = Math.floor(Abs / 1000000);
            minutes = Math.floor(((Abs/1000000) - days) * 60);
            seconds = ( Math.floor((( ((Abs/1000000) - days) * 60) - minutes) * 100000) *60/100000 ).toFixed();
            days = days * sign;
            if(type == 'lat') direction = days<0 ? 'LS' : 'LU';
            if(type == 'lon') direction = days<0 ? 'BB' : 'BT';
            //else return value     
            return (days * sign) + 'º ' + minutes + "' " + seconds + "\" " + direction;
        }


    }
}(window, document);

var decodeEntities = (function() {
  // this prevents any overhead from creating the object each time
  var element = document.createElement('div');

  function decodeHTMLEntities (str) {
    if(str && typeof str === 'string') {
      // strip script/html tags
      str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
      str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
      element.innerHTML = str;
      str = element.textContent;
      element.textContent = '';
    }

    return str;
  }

  return decodeHTMLEntities;
})();

function triggerMN(el){
    if (el == 'pemetaan') {
        $('[data-menuid="f027a7c66bbf2a86d4f45d47a4c311d4"]').trigger('click');
    }else{
        $('[data-menuid="a314170f5395bb9a1130fc14c60eb445"]').trigger('click');
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
function formatDate(date, patternStr){
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
function twoDigitPad(num) {
    return num < 10 ? "0" + num : num;
}/*
console.log(formatDate(new Date()));
console.log(formatDate(new Date(), 'dd-MMM-yyyy')); //OP's request
console.log(formatDate(new Date(), 'EEEE, MMMM d, yyyy HH:mm:ss.S aaa'));
console.log(formatDate(new Date(), 'EEE, MMM d, yyyy HH:mm'));
console.log(formatDate(new Date(), 'yyyy-MM-dd HH:mm:ss.S'));
console.log(formatDate(new Date(), 'M/dd/yyyy h:mmaaa'));*/

//
// $('#element').donetyping(callback[, timeout=1000])
// Fires callback when a user has finished typing. This is determined by the time elapsed
// since the last keystroke and timeout parameter or the blur event--whichever comes first.
//   @callback: function to be called when even triggers
//   @timeout:  (default=1000) timeout, in ms, to to wait before triggering event if not
//              caused by blur.
// Requires jQuery 1.7+
//
(function($){
    $.fn.extend({
        donetyping: function(callback,timeout){
            timeout = timeout || 1e3; // 1 second default timeout
            var timeoutReference,
                doneTyping = function(el){
                    if (!timeoutReference) return;
                    timeoutReference = null;
                    callback.call(el);
                };
            return this.each(function(i,el){
                var $el = $(el);
                // Chrome Fix (Use keyup over keypress to detect backspace)
                // thank you @palerdot
                // $el.is(':input') && $el.on('keyup keypress paste',function(e){
                $el.is(':input') && $el.on('blur',function(e){
                    // This catches the backspace button in chrome, but also prevents
                    // the event from triggering too preemptively. Without this line,
                    // using tab/shift+tab will make the focused element fire the callback.
                    if (e.type=='keyup' && e.keyCode!=8) return;
                    
                    // Check if timeout has been set. If it has, "reset" the clock and
                    // start over again.
                    if (timeoutReference) clearTimeout(timeoutReference);
                    timeoutReference = setTimeout(function(){
                        // if we made it here, our timeout has elapsed. Fire the
                        // callback
                        doneTyping(el);
                    }, timeout);
                }).on('blur',function(){
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
