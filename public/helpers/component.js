const COMPONENT = function () {
    return {
        judul_penugasan: function (config) {
            config = $.extend(true,{
                title: '-',
                auditee: "", // must be array or json array
                limitAuditee: 4
            },config);
            // encode json
            var universe    = new Array();
            var auditeeList = new Array();
            if (config.auditee != "" && !$.isArray(config.auditee)) {
                auditeeList = JSON.parse(config.auditee);
            }
            // set universe
            if (auditeeList.length > 0) {
                universe.push([`<ul class="breadcrumb breadcrumb-transparent breadcrumb-arrow-right font-weight-bold font-size-xs p-0 mb-0">`]);
                // implement limitation of shown auditee
                if (auditeeList.length > config.limitAuditee) {
                    // set first auditee
                    universe.push([`<li class="breadcrumb-item text-muted">...</li>`]);
                    // start counting list lengt - limit - start & end (2)
                    for (let index = auditeeList.length-config.limitAuditee; index <= auditeeList.length-1; index++) {
                        if (index > 0) {
                            universe.push([`
                                <li class="breadcrumb-item text-${(index < auditeeList.length - 1)? `muted`:`dark`}">
                                    ${auditeeList[index]}
                                </li>
                            `]);
                        }
                    }
                } else {
                    $.each(auditeeList, (i, v) => {
                        universe.push([`
                            <li class="breadcrumb-item text-${(i < auditeeList.length - 1)? `muted`:`dark`}">
                                ${v}
                            </li>
                        `]);
                    });
                }
                universe.push([`</ul>`]);
                
            }
            // set html
            var html = `
                <div class="flex flex-column">
                    <label class="font-weight-bold mb-0">
                        ${config.title}
                    </label>
                    ${universe.join('')}
                </div>
            `;
            return html;
        },
        example: function (config = []) {
            var customComponent = $("<div class='custom-component'></div>");

            return customComponent;
        },
        form_template_exmaple : function (config) {
            var order = config.order,
                number_card = config.number_card,
                description = config.description ?? '',
                grade1 = config.grade1 ?? '',
                grade2 = config.grade2 ?? '',
                grade3 = config.grade3 ?? '',
                grade4 = config.grade4 ?? '',
                grade5 = config.grade5 ?? '';

            var html = `
                <div class="card card-bordered mb-5" id="stack-${order}" data-aspect-id="${config.aspect_id}">
                    <div class="card-header">
                        <h3 class="card-title fs-3 fw-bold">${number_card}</h3>
                        <div class="card-toolbar">
                            <i class="ki-solid ki-trash fs-1 text-danger" onclick="deleteStackFormTemplate(this)"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-7">
                            <div class="col-12 mb-5">
                                <label class="fs-6 mb-3 fw-bold">Pertanyaan</label>
                                <input type="text" id="description" name="description" class="form-control" placeholder="Pertanyaan"/ value="${description}">
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-3 my-auto">
                                <p class="fs-6 fw-bold mb-0">
                                    (1) Poor
                                </p>
                            </div>
                            <div class="col">
                                <input type="text" id="grade1" name="grade1" class="form-control" placeholder="Poor" value="${grade1}"/>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-3 my-auto">
                                <p class="fs-6 fw-bold mb-0">
                                    (2) Weak
                                </p>
                            </div>
                            <div class="col">
                                <input type="text" id="grade2" name="grade2" class="form-control" placeholder="Weak" value="${grade2}"/>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-3 my-auto">
                                <p class="fs-6 fw-bold mb-0">
                                    (3) Capable
                                </p>
                            </div>
                            <div class="col">
                                <input type="text" id="grade3" name="grade3" class="form-control" placeholder="Capable" value="${grade3}"/>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-3 my-auto">
                                <p class="fs-6 fw-bold mb-0">
                                    (4) Very Capable
                                </p>
                            </div>
                            <div class="col">
                                <input type="text" id="grade4" name="grade4" class="form-control" placeholder="Very Capable" value="${grade4}"/>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-3 my-auto">
                                <p class="fs-6 fw-bold mb-0">
                                    (5) Excellent
                                </p>
                            </div>
                            <div class="col">
                                <input type="text" id="grade5" name="grade5" class="form-control" placeholder="Excellent" value="${grade5}"/>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            return html;
        },
        detail_page: function (config) {
            config = $.extend(true,{
                data: '',
            },config);

            let kaSpi = config.data.penugasan[0].penugasan_teams.find(team => team.penugasan_teams_tipe === 'ka_spi');
            var acronymKaSpi = kaSpi.user_name ? kaSpi.user_name.split(/\s/).reduce((response, word) => response += word.slice(0, 1), '') : ''
            
            let kaAss = config.data.penugasan[0].penugasan_teams.find(team => team.penugasan_teams_tipe === 'ka_ass');
            var acronymkaAss = kaAss.user_name ? kaAss.user_name.split(/\s/).reduce((response, word) => response += word.slice(0, 1), '') : ''
            
            let kaQc = config.data.penugasan[0].penugasan_teams.find(team => team.penugasan_teams_tipe === 'ka_qc');
            var acronymkaQc = kaQc.user_name ? kaQc.user_name.split(/\s/).reduce((response, word) => response += word.slice(0, 1), '') : ''
            
            let spvTeam = config.data.penugasan[0].penugasan_teams.find(team => team.penugasan_teams_tipe === 'supervisor');
            var acronymspvTeam = spvTeam.user_name ? spvTeam.user_name.split(/\s/).reduce((response, word) => response += word.slice(0, 1), '') : ''
            
            let kaTimTeam = config.data.penugasan[0].penugasan_teams.find(team => team.penugasan_teams_tipe === 'ketua_tim');
            var acronymkaTimTeam = kaTimTeam.user_name ? kaTimTeam.user_name.split(/\s/).reduce((response, word) => response += word.slice(0, 1), '') : ''

            let angTimTeam = config.data.penugasan[0].penugasan_teams.filter(team => team.penugasan_teams_tipe === 'anggota_tim');
            let angTimUserNames = angTimTeam.map(team => team.user_name).join(', ');

            // let anggotaHtml = angTimTeam.map(team => {
            //     var name = team.user_name;
            //     var acronym = name.split(/\s/).reduce((response, word) => response += word.slice(0, 1), '');
            //     var colors = ['primary', 'success', 'warning'];
            //     var randomColor = colors[Math.floor(Math.random() * colors.length)];
            //     return `<div class="symbol symbol-circle symbol-${randomColor}" data-toggle="tooltip" title="${name}" data-original-title="${name}">
            //         <span class="symbol-label" style="width: 31px !important; height: 31px !important; margin-right: -5px;">${acronym.length < 2 ? acronym : acronym.substr(0,2)}</span>
            //     </div>`;
            // }).join('');

            let anggotaHtml = angTimTeam.map(team => {
                var name = team.user_name;
                var acronym = name.split(/\s/).reduce((response, word) => response += word.slice(0, 1), '');
                var colors = ['primary', 'success', 'warning'];
                var randomColor = colors[Math.floor(Math.random() * colors.length)];
                return `                            
                    <div class="symbol symbol-circle symbol-${randomColor} mb-4" style="display: flex; align-items: center; justify-content: start; text-align: center;" data-toggle="tooltip" title="${team.user_name}" data-original-title="${team.user_name}">
                        <span class="symbol-label" style="width: 31px !important; height: 31px !important; display: inline-flex; align-items: center; justify-content: center;">${acronym.length < 2 ? acronym : acronym.substr(0,2)}</span>
                        <span style="margin-left: 5px; font-weight: 600">${team.user_name ?? '-'}</span>
                    </div>`;
            }).join('');

            // <p>${config.data.pkat.pkpt_id}</p>
            // <p id="penugasan_sasaran">${config.data.penugasan[0].penugasan_sasaran}</p>
            var html = `
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="font-weight-bold text-dark">Informasi Umum</h5>
                        <br>
                        <div class="form-group">
                            <label class="text-muted">Tahun</label>
                            <p id="select_tahun">${config.data.pkat.pkpt_tahun}</p>
                        </div>
                        <div class="form-group">
                            <label class="text-muted">Entity</label>
                            <p id="penugasan_universe_nama">${config.data.penugasan[0].unit_nama}</p>
                        </div>
                        <div class="form-group">
                            <label class="text-muted">Pemeriksaan/Penugasan</label>
                            <p id="penugasan_nama">${config.data.penugasan[0].penugasan_nama}</p>
                        </div>
                        <div class="form-group">
                            <label class="text-muted">Tipe Penugasan</label>
                            <p id="tipe_penugasan_nama">${config.data.penugasan[0].tipe_penugasan_nama}</p>
                        </div>
                        <div class="form-group">
                            <label class="text-muted">Tujuan Penugasan</label>
                            ${config.data.penugasan[0].penugasan_sasaran}
                        </div>
                        <div class="form-group">
                            <label class="text-muted">Inline With</label>
                            ${config.data.penugasan[0].penugasan_inline_with}
                        </div>
                        <div class="form-group">
                            <label class="text-muted">Periode Audit</label>
                            <p id="periode_audit">${moment(config.data.penugasan[0].penugasan_periode_audit).format('DD MMMM YYYY')} s/d ${moment(config.data.penugasan[0].penugasan_periode_audit_akhir).format('DD MMMM YYYY')} (${config.data.penugasan[0].jumlah_hari} Hari)</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="font-weight-bold text-dark">Tim dan Wewenang</h5>
                        <br>
                        <div class="form-group">
                            <p class="text-muted" style="margin-bottom: 4px;">Kepala SPI</p>
                            <div class="symbol symbol-circle symbol-primary" style="display: flex; align-items: center; justify-content: start; text-align: center;" data-toggle="tooltip" title="${kaSpi.user_name}" data-original-title="${kaSpi.user_name}">
                                <span class="symbol-label" style="width: 31px !important; height: 31px !important; display: inline-flex; align-items: center; justify-content: center;">${acronymKaSpi.length < 2 ? acronymKaSpi : acronymKaSpi.substr(0,2)}</span>
                                <span style="margin-left: 5px; font-weight: 600">${kaSpi.user_name}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <p class="text-muted" style="margin-bottom: 4px;">Kabid. Assurance</p>
                            <div class="symbol symbol-circle symbol-primary" style="display: flex; align-items: center; justify-content: start; text-align: center;" data-toggle="tooltip" title="${kaAss.user_name}" data-original-title="${kaAss.user_name}">
                                <span class="symbol-label" style="width: 31px !important; height: 31px !important; display: inline-flex; align-items: center; justify-content: center;">${acronymkaAss.length < 2 ? acronymkaAss : acronymkaAss.substr(0,2)}</span>
                                <span style="margin-left: 5px; font-weight: 600">${kaAss.user_name}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <p class="text-muted" style="margin-bottom: 4px;">Supervisor</p>
                            <div class="symbol symbol-circle symbol-primary" style="display: flex; align-items: center; justify-content: start; text-align: center;" data-toggle="tooltip" title="${spvTeam.user_name}" data-original-title="${spvTeam.user_name}">
                                <span class="symbol-label" style="width: 31px !important; height: 31px !important; display: inline-flex; align-items: center; justify-content: center;">${acronymspvTeam.length < 2 ? acronymspvTeam : acronymspvTeam.substr(0,2)}</span>
                                <span style="margin-left: 5px; font-weight: 600">${spvTeam.user_name}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <p class="text-muted" style="margin-bottom: 4px;">Ketua Tim</p>
                            <div class="symbol symbol-circle symbol-primary" style="display: flex; align-items: center; justify-content: start; text-align: center;" data-toggle="tooltip" title="${kaTimTeam.user_name}" data-original-title="${kaTimTeam.user_name}">
                                <span class="symbol-label" style="width: 31px !important; height: 31px !important; display: inline-flex; align-items: center; justify-content: center;">${acronymkaTimTeam.length < 2 ? acronymkaTimTeam : acronymkaTimTeam.substr(0,2)}</span>
                                <span style="margin-left: 5px; font-weight: 600">${kaTimTeam.user_name}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <p class="text-muted" style="margin-bottom: 4px;">Anggota Tim</p>
                            ${anggotaHtml}
                        </div>
                    </div>
                </div>
            `;
            return html;
        },
    }
}();
