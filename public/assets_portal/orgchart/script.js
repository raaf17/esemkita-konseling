'use strict';

(function($){

  $(function() {

    var datascource = {
      'name': ' Kepala Satuan<br/>Pengawas Internal',
      'title': 'Vice President 2',
      'children': [ {
        'children': [
            { 'name': 'Bo Miao', 'title': 'department manager' },
            { 'name': 'Su Miao', 'title': 'department manager',
              'children': [
                { 'name': 'Tie Hua', 'title': 'senior engineer' },
                { 'name': 'Hei Hei', 'title': 'senior engineer',
                  'children': [
                    { 'name': 'Pang Pang', 'title': 'engineer' },
                    { 'name': 'Xiang Xiang', 'title': 'UE engineer' }
              ]
            }
          ]
        },
        { 'name': 'Hong Miao', 'title': 'department manager' },
        { 'name': 'Chun Miao', 'title': 'department manager' }
      ]
      },
        { 'name': 'Bo Miao', 'title': 'department manager' },
        { 'name': 'Su Miao', 'title': 'department manager',
          'children': [
            { 'name': 'Tie Hua', 'title': 'senior engineer' },
            { 'name': 'Hei Hei', 'title': 'senior engineer',
              'children': [
                { 'name': 'Pang Pang', 'title': 'engineer' },
                { 'name': 'Xiang Xiang', 'title': 'UE engineer' }
              ]
            }
          ]
        },
        { 'name': 'Hong Miao', 'title': 'department manager' },
        { 'name': 'Chun Miao', 'title': 'department manager' }
      ]
    };

    var oc = $('#chart-container').orgchart({
      'data' : datascource,
      'nodeContent': 'title',
      'pan': true,
      'zoom': false
    });

  });

  $('.node').each(function(i,v){
    var isempty = $(v).find('.content').html();
    if (isempty == '') {
      $(this).html('&nbsp;').css({
        'border-right' : '2px solid #e17572',
        'width' : '0px',
        'margin-right' : '6px',
        'font-size' : '20px',
        'background-color': 'transparent'
      })
    }
  })
  $('.edge').css('display','none');
})(jQuery);