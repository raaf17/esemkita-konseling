var PUSHER = function() {
    var callCannel;
    return {
        setConfig: function(config) {
            config = $.extend(true, {
                pusherKey: null,
                pusherChannel: null,
                userId: null,
                env: null
            }, config);

            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = (typeof config.env != 'undefined' && config.env != null)? ((config.env == 'development')?true:false):false;

            var pusher = new Pusher(config.pusherKey, {
                cluster: 'ap1'
            });
            
            callCannel = pusher.subscribe(config.pusherChannel);
            callCannel.bind(`my-event-${config.userId}`, function(data) {
                if (data.total > 0) {
                    var content = {
                        message: data.message,
                        title: data.title,
                        icon: 'icon flaticon-exclamation-2'
                    };
            
                    var notify = $.notify(content, {
                        type: 'primary',
                        allow_dismiss: true,
                        newest_on_top: true,
                        mouse_over:  true,
                        showProgressbar:  false,
                        spacing: 10,
                        timer: 2000,
                        placement: {
                            from: 'bottom',
                            align: 'right'
                        },
                        offset: {
                            x: '30',
                            y: '30'
                        },
                        delay: 1000,
                        z_index: 10000,
                        animate: {
                            enter: 'animate__animated animate__fadeInLeft',
                            exit: 'animate__animated animate__fadeOutLeft'
                        }
                    });
                }
            });
        },
        callNotification: function(config) {
            config = $.extend(true, {
                userId: null
            }, config);

            HELPER.ajax({
                url: `${APP_URL}Notification/sendFistNotification`,
                data: {userId: config.userId}
            });
        }
    }
}();