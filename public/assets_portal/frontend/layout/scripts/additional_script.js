$(function(){	
	var	no_index = 0;

	function getPage(el) {
		var data = $(el).data();
		$('.content_view').css('display','none');
		$('.li_sub').removeClass('active');
		$('#'+data.id).css('display','block');
		$(el).parent().addClass('active')
	}

	function addFoto()
	{
		var html = '';
			no_index++;

			html = 
				'<tr class="no_row" id="'+no_index+'">'+
					'<td class="t-center no_'+no_index+'"></td>'+
					'<td class="t-center">'+
						'<input type="hidden" id="foto_sort_'+no_index+'" name="foto_sort['+no_index+']">'+
						'<div class="custom-file">'+
							'<input type="file" class="custom-file-input" data-index="'+no_index+'" id="pelanggan_foto_'+no_index+'" name="pelanggan_foto['+no_index+']" accept="image/png, image/jpeg, image/gif" onchange="readImage(this)">'+
							'<label class="custom-file-label label_foto_'+no_index+'" for="pelanggan_foto_'+no_index+'"></label>'+
						'</div>'+
					'</td>'+
					'<td class="t-center">'+
						'<input type="text" class="form-control m-inputt keterangan_'+no_index+'" id="foto_keterangan_'+no_index+'" name="foto_keterangan['+no_index+']" placeholder="Keterangan berkas ...">'+
					'</td>'+
					'<td class="t-center"><a href="javascript:;" class="btn btn-sm red" data-id="'+no_index+'" onclick="dellFoto(this)"><i class="fa fa-trash-o"></i></a></td>'+
				'</tr>'
			;
		$('#table_foto tbody').append(html);
		$('.no_row').each(function(i,v) {
			$('.no_'+no_index).html(i+1);
			$('.keterangan_'+no_index).attr('placeholder','Keterangan berkas ke-'+(i+1)+' ...');
			$('#foto_sort_'+no_index).val((i+1));
		})
	}

	function dellFoto(el)
	{
		var data = $(el).data();
		$(el).parent().parent().remove();
		$('.no_row').each(function(i,v) {
			var row_id = $(this)[0].id;
			$('.no_'+row_id).html(i+1);
			$('.keterangan_'+row_id).attr('placeholder','Keterangan berkas ke-'+(i+1)+' ...');
			$('#foto_sort_'+row_id).val((i+1));
		})
	}

	function readImage(el)
	{
		if (el.files && el.files[0]) {
			var data = $(el).data();
	        $('.label_foto_'+data.index).html(el.files[0].name);
	    }
	}


	/*HELPER.ajax({
		url: APP_URL+'Faq/getThumb',
		complete: function(dCom) {
			var html_faq = new Array();
			html_faq.push([
				'<div class="col-md-12 margin-bottom-10">'+
					'<h2 class="title-header-yellow">FAQ</h2>'+
					'<div class="portlet">'+
						'<div class="portlet-body form">'+
							'<div class="form-body" style="border: 1px solid #eee; background-color: #fff;">'
			]);
			$.each(dCom, function(i,v){
				html_faq.push([
					'<div class="row margin-bottom-10">'+						
						'<div class="col-md-12">'+
							'<div class="timeline-body-head">'+
								'<div class="timeline-body-head-caption" style="text-align: justify;">'+
									'<a href="javascript:;" class="timeline-body-title font-blue-hoki" onclick="HELPER.loadPage(this)" data-menuid="0005" data-con="Faq-View" data-title="FAQ&#39;s" style="font-size: 13px;">'+(i+1)+'. '+v.faq_title+'.</a>'+
								'</div>'+
							'</div>'+
							'<div style="border-bottom: 1px solid #eee;">&nbsp;</div>'+
						'</div>'+						
					'</div>'
				])
			})
			html_faq.push([
							'</div>'+
							'<div class="form-actions form-actions-color">'+
								'<div class="row">'+
									'<div class="col-md-12" style="text-align: right; font-weight: 600;">'+
										'<a href="javascript:;"  onclick="HELPER.loadPage(this)" data-menuid="0005" data-con="Faq-View" data-title="FAQ&#39;s" style="text-decoration: none;">Lihat Semua</a>'+
									'</div>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>'
			]);
			$('.faq').html(html_faq.join(''));
		}
	})	*/	

	$('.berita_kegiatan').html(
		'<div class="col-md-12 margin-bottom-10">'+
			'<div class="blog-talks">'+
				'<h2 class="title-header-green">Berita & Kegiatan</h2>'+
				'<div class="tab-style-1">'+
					'<ul class="nav nav-tabs">'+
						'<li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true">Berita</a></li>'+
						'<li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">Kegiatan</a></li>'+
					'</ul>'+
					'<div class="tab-content" style="padding: 0px;">'+
						'<div id="tab-1" class="tab-pane  fade active in">'+
							'<div class="portlet">'+
								'<div class="portlet-body form">'+
									'<div class="form-body" style="border: 1px solid #eee; background-color: #fff;">'+
										'<div class="recent-news margin-bottom-10" id="content_berita_side"></div>'+
									'</div>'+
									'<div class="form-actions form-actions-color">'+
										'<div class="row">'+
											'<div class="col-md-12" style="text-align: right; font-weight: 600;">'+
												'<a href="javascript:;" data-id="" onclick="HELPER.loadPage(this)" data-menuid="00041" data-con="Berita-View" data-title="Berita" style="text-decoration: none;">Lihat Semua</a>'+
											'</div>'+
										'</div>'+
									'</div>'+
								'</div>'+
							'</div>'+
						'</div>'+
						'<div id="tab-2" class="tab-pane row-fluid fade">'+
							'<div class="portlet">'+
								'<div class="portlet-body form">'+
									'<div class="form-body" style="border: 1px solid #eee; background-color: #fff;">'+
										'<div class="recent-news margin-bottom-10" id="content_kegiatan_side"></div>'+
									'</div>'+
									'<div class="form-actions form-actions-color">'+
										'<div class="row">'+
											'<div class="col-md-12" style="text-align: right; font-weight: 600;">'+
												'<a href="javascript:;" data-id="" onclick="HELPER.loadPage(this)" data-menuid="00041" data-con="Berita-View" data-title="Berita" style="text-decoration: none;">Lihat Semua</a>'+
											'</div>'+
										'</div>'+
									'</div>'+
								'</div>'+
							'</div>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>'+
		'</div>'
	);

	HELPER.ajax({
		url: APP_URL+'Beranda/findData',
		data: {
			filter: 'barita_category IN(1,3)',
			total_inpage: 3
		},
		complete: function(dCom) {
			var html = new Array();
				$.each(dCom.data, function(i,v){
					var image = (v.berita_file != '')? './files/thumbs/berita_kegiatan/'+v.berita_file:'./files/thumbs/berita_kegiatan/default.jpg';
					html.push([
						'<div class="row margin-bottom-10" style="border-bottom: 1px solid #eee;">'+
							'<div class="col-md-3">'+
								'<img class="img-responsive" alt="" src="'+image+'">'+
							'</div>'+
							'<div class="col-md-8 recent-news-inner">'+
								'<h3><a href="javascript:;" data-dataid="'+v.berita_id+'" onclick="HELPER.loadPage(this)" data-menuid="00041" data-con="Berita-View" data-isberita="1">'+cutString((HELPER.decodeHtml(v.berita_title)).replace(/(<([^>]+)>)/ig,""), 20)+'</a></h3>'+
								'<span style="color: #aeaeae; font-size: 11px;"><i class="fa fa-calendar"></i> '+v.berita_date+'</span>'+
								'<p style="text-align: justify;">'+cutString((HELPER.decodeHtml(v.berita_content_thumb)).replace(/(<([^>]+)>)/ig,""), 55)+'</p>'+
							'</div>'+
						'</div>'
					])						
				})

				$('#content_berita_side').html(html.join(''));
		}
	});

	HELPER.ajax({
		url: APP_URL+'Beranda/findData',
		data: {
			filter: 'barita_category IN(2,3)',
			total_inpage: 3
		},
		complete: function(dCom) {
			var html = new Array();
				$.each(dCom.data, function(i,v){
					var image = (v.berita_file != '')? './files/thumbs/berita_kegiatan/'+v.berita_file:'./files/thumbs/berita_kegiatan/default.jpg';
					html.push([
						'<div class="row margin-bottom-10" style="border-bottom: 1px solid #eee;">'+
							'<div class="col-md-3">'+
								'<img class="img-responsive" alt="" src="'+image+'">'+
							'</div>'+
							'<div class="col-md-8 recent-news-inner">'+
								'<h3><a href="javascript:;" data-dataid="'+v.berita_id+'" onclick="HELPER.loadPage(this)" data-menuid="00042" data-con="Kegiatan-View" data-isberita="1">'+cutString((HELPER.decodeHtml(v.berita_title)).replace(/(<([^>]+)>)/ig,""), 20)+'</a></h3>'+
								'<span style="color: #aeaeae; font-size: 11px;"><i class="fa fa-calendar"></i> '+v.berita_date+'</span>'+
								'<p style="text-align: justify;">'+cutString((HELPER.decodeHtml(v.berita_content_thumb)).replace(/(<([^>]+)>)/ig,""), 55)+'</p>'+
							'</div>'+
						'</div>'
					])						
				})

				$('#content_kegiatan_side').html(html.join(''));
		}
	});

	function cutString(el, el_length)
	{
		return (el.length > el_length) ? el.substr(0, el_length-1) + '&hellip;' : el;
	}

	function isLogin()
	{
		$('#modal_login').modal('show')
	}
})