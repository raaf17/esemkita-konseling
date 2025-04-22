var $id = localStorage.getItem('id_kuesioner');
$(document).ready(function () {
    $.blockUI({ message : 'Menampilkan halaman...',css: { 
        border: 'none', 
        padding: '5px', 
        backgroundColor: '#000', 
        '-webkit-border-radius': '10px', 
        '-moz-border-radius': '10px', 
        opacity: .5, 
        color: '#fff' 
    } });

    if ($('[name="is_preview"]').val()) {
        var myQueue = new Queue();
        myQueue.enqueue(function (next) {
            setTimeout(function(){
                $('#main_kuesioner').css({'display':'block'});
                $('#data_belum_diisi').css({'display':'block'});
                next();
            }, 500)
        }, '1').enqueue(function (next) {
            setTimeout(function(){
                $('.select2').each(function(i,v){
                    $('[name="'+$(v).attr('name')+'"]').sm_select()
                    $($(v).parent().find('[type="text"]')).css({'display':'none'});
                    $($(v).parent().find('.caret')).css({'display':'none'});
                });
                next();
            }, 500)
        }, '2').enqueue(function (next) {
            setTimeout($.unblockUI, 2000);
            next();
        }, '3').dequeueAll();
    }else{
        if ($id != null) {
            $.ajax({
                url: APP_URL+'kuesioner/getLocalStorage',
                data: {
                    id: $id,
                    quest_id : $('[name="question_id"]').val(),
                    csrf_spi: $.cookie('csrf_lock_spi')
                }, 
                type: "POST",
                success: function(data){
                    $('#main_kuesioner').css({'display':'block'});
                    if (data.success) {
                        $('#data_sudah_isi').css({'display':'block'})
                    }else{
                        $('#data_belum_diisi').css({'display':'block'})
                    }
                }, complete: function(data){
                    setTimeout(function(){
                        $('.select2').each(function(i,v){
                            $('[name="'+$(v).attr('name')+'"]').sm_select()
                            $($(v).parent().find('[type="text"]')).css({'display':'none'});
                            $($(v).parent().find('.caret')).css({'display':'none'});
                        });
                    }, 500)
                    setTimeout($.unblockUI, 2000);
                }
            });
        }else{
            $id = localStorage.setItem('id_kuesioner', $('[name="question_id"]').val()+'___'+md5(generateUUID()));
            $('#main_kuesioner').css({'display':'block'});
            $('#data_belum_diisi').css({'display':'block'});
            setTimeout(function(){
                $('.select2').each(function(i,v){
                    $('[name="'+$(v).attr('name')+'"]').sm_select()
                    $($(v).parent().find('[type="text"]')).css({'display':'none'});
                    $($(v).parent().find('.caret')).css({'display':'none'});
                });
            }, 500);
            setTimeout($.unblockUI, 2000);
        }
    }

    $('.modal').modal();
    $('select').formSelect();
    $('#day').focus(function () {
        $('#number-label').css('color', '#607d8b');
    }).blur(function () {
        $('#number-label').css('color', '#9e9e9e');
    });
    $('#city').focus(function () {
        $('#country-label').css('color', '#607d8b');
    }).blur(function () {
        $('#country-label').css('color', '#9e9e9e');
    });
    
});

$('.remove_error').on("keyup change", function(e) {
    $('[id="'+$(this).attr('name')+'"][data-error="Error"]').html('');
})

function onNext(){
    var is_validate = true;
    $('.validate').each(function(i,v){
        if ($(v).val() == '' || $(v).val() == null) {
            $('[id="'+$(v).attr('name')+'"][data-error="Error"]').html('<i class="material-icons red-text" style="vertical-align:bottom;">error_outline</i> <i class="red-text">Data wajib diisi</i>');
            is_validate = false;
        }
    });

    $('.validate_other').each(function(i,v){
        if ($($(v).parent().parent()).css('display') == 'block') {
            if ($(v).val() == '' || $(v).val() == null) {
                $('[id="'+$(v).attr('name')+'"][data-error="Error"]').html('<i class="material-icons red-text" style="vertical-align:bottom;">error_outline</i> <i class="red-text">Data wajib diisi</i>');
                is_validate = false;
            }
        }
    })

    if (is_validate) {
        $.blockUI({ message : 'Poses...',css: { 
            border: 'none', 
            padding: '5px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } });
        $('#button_next1').css({'display':'none'});
        $("#data_diri").fadeOut('fast', function() {
            $('.button_save').css({'display':'block'});
            if ($('[name="is_preview"]').val()) {
                $('.button_save .btn_back').css({'display':'block'});
                $('.button_save .btn_save').css({'display':'none'});
            }
            $.unblockUI();
            $("#data_pertanyaan").fadeIn('fast', function(){}).css('display','block');
        }).css({'display':'none'});
    }
}

function onBack(){
    $('.button_save').css({'display':'none'});
    $("#data_pertanyaan").fadeOut('fast', function() {
        $('#button_next1').css({'display':'block'});
        $("#data_diri").fadeIn('fast', function(){}).css('display','block');
    }).css({'display':'none'});
}

$('.validate').each(function(i,v){
    if ($(v)[0]['type'] == 'select-one') {
        $(v).on('change',(event) => {
            $($(v).parent().children('input')).removeClass('invalid');
        });
    }
});

$('.validate').on('change blur', function(i,v){
    $('[id="'+$(this).attr('name')+'"][data-error="Error"]').html("")
});

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
                $el.is(':input') && $el.on('keyup keypress paste',function(e){
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
                    // If we can, fire the event since we're leaving the field
                    doneTyping(el);
                }).on('change', function(){
                    /*if (timeoutReference) clearTimeout(timeoutReference);
                    timeoutReference = setTimeout(function(){
                        doneTyping(el);
                    }, timeout);*/
                });
            });
        }
    });
})(jQuery);

$('.savedata').donetyping(function(){
    var attr_name   = $(this).attr('name');
    var attr_data   = $(this).data();
    var val         = $(this).val();

    var type_input  = $(this)[0]['type'];
    if (type_input == 'checkbox') {
      /*  $(this).each(function(i,v){
            var newAttrName = $(v).attr('name');
            var newVal = $(v).val();
            localStorage.setItem(type_input+'___'+newAttrName+'___', val)
        });*/
    }else{
        localStorage.setItem(type_input+'___'+attr_name+'___1', val)
    }
});

$.fn.serializeObject = function()
{
   var o = {};
   var a = this.serializeArray();
   $.each(a, function() {
       if (o[this.name]) {
           if (!o[this.name].push) {
               o[this.name] = [o[this.name]];
           }
           o[this.name].push(this.value || '');
       } else {
           o[this.name] = this.value || '';
       }
   });
   return o;
};

function saveData(){
    var is_required = false;
    var is_focus    = [];
    $('.req_data').each(function(i,v){
        var type = $(v).data('type');
        var quest_cont_id = $(v).data('cont_id');
        if(type == 'text'){
            if($(v).children('div.jawaban').find('[type="'+type+'"]').val() == ''){
                is_required = true;
                $('label[id="'+quest_cont_id+'"]').html('<i class="material-icons red-text" style="vertical-align:bottom;">error_outline</i> <i class="red-text">Pertanyaan ini wajib diisi</i>');
            }
        }else{
            if($($(v).children('div.jawaban').find('[type="'+type+'"]')).is(':checked')){
                
            }else{
                $('label[id="'+quest_cont_id+'"]').html('<i class="material-icons red-text" style="vertical-align:bottom;">error_outline</i> <i class="red-text">Pertanyaan ini wajib diisi</i>');
                is_required = true;
            }
        }
        is_focus.push([quest_cont_id]);
    })
    if (is_required) {
        $('html, body').animate({ scrollTop: $('[data-cont_id="'+is_focus[0]+'"]').offset().top }, 'slow')
    }else{
        $('#modal-confirm').modal('open');
    }
}

$('.data_dd').on('change blur', function(i,v){
    $('[id="'+$(this).attr('name')+'"][data-error="Error"]').html("")
});

$("#kirim").click(function(e) {
    e.preventDefault();
    
    $('#modal-confirm').modal('close');
    var _array = {};
    $(".data_dd").each(function(i,v){
        if($(v)[0]['type'] == 'text'){
            if ($(v).val() !='') {
                _array[$(v).data('dd')] = $(v).val();
            }
        }else{
            if($(v).is(':checked')){
                _array[$(v).data('dd')] = $(v).val();
            }
        }
    });

    $.blockUI({ message : 'Proses mengirim data...',css: { 
        border: 'none', 
        padding: '5px', 
        backgroundColor: '#000', 
        '-webkit-border-radius': '10px', 
        '-moz-border-radius': '10px', 
        opacity: .5, 
        color: '#fff' 
    } });

    $.ajax({
        url: APP_URL+'kuesioner/storeUserAnswer',
        data: {
            csrf_spi: $.cookie('csrf_lock_spi'),
            data_pertanyaan : _array,
            data_parent : {
                quest_user_asw_devisi_id : $('[name="quest_user_asw_devisi_id"]').val(),
                quest_user_asw_devisi_other : $('[name="quest_user_asw_devisi_other"]').val(),
                quest_user_asw_departemen_id : $('[name="quest_user_asw_departemen_id"]').val(),
                quest_user_asw_departemen_other : $('[name="quest_user_asw_departemen_other"]').val(),
                quest_user_asw_jk : $('[name="quest_user_asw_jk"]').val(),
                quest_user_asw_email : $('[name="quest_user_asw_email"]').val(),
                quest_user_asw_quest_id : $('[name="question_id"]').val(),
                quest_user_asw_local_storage_id : localStorage.getItem('id_kuesioner'),
            }
        },
        type: "POST",
        cache: false,
        contentType: 'application/x-www-form-urlencoded',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        }, success: function(dSuc){

        }, error: function(dError){
            //console.log(dError)
        }, complete: function(dCom){
            var response    = JSON.parse(dCom.responseText);
            var message     = ((response.success) 
                ? '<i class="material-icons white-text" style="vertical-align:bottom;">check_circle</i> <i class="white-text">&nbsp;&nbsp;Berhasil mengirim data.</i>' 
                : '<i class="material-icons red-text" style="vertical-align:bottom;">error_outline</i> <i class="red-text">&nbsp;&nbsp;Gagal mengirim data.</i>');
            M.toast({html: message});
            if (response.success) {
                $('#data_belum_diisi').css({'display':'none'});
                $('#data_sudah_isi').css({'display':'block'});
                $('#data_sudah_isi').find('#description').html("Terimakasih telah mengisi form survei.");
            }
            setTimeout($.unblockUI, 2000);
        }
    });

});