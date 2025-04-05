<div class="modal fade" id="kt_modal_1" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog modal-dialog-scrollable mw-750px">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="fw-bold">Update Hak Akses</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form id="kt_modal_new_target_form" class="form" action="<?= route_to('hakakses.simpanhakakses') ?>" method="POST">
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <div class="fv-row mb-4">
                        <label class="fs-5 fw-semibold form-label mb-2">
                            <span class="required">Role</span>
                        </label>
                        <input class="form-control form-control-solid" placeholder="Enter a role name" name="role_name" value="" disabled readonly />
                    </div>
                    <div class="fv-row">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <tbody class="text-gray-600 fw-semibold">
                                    <tr>
                                        <td class="text-gray-800">
                                            Administrator Access
                                            <span class="ms-1" data-bs-toggle="tooltip" title="Allows a full access to the system">
                                                <i class="ki-duotone ki-information-5 text-gray-500 fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>
                                        </td>
                                        <td>
                                            <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid me-9">
                                                <input class="form-check-input" type="checkbox" value="" id="kt_roles_select_all" />
                                                <span class="form-check-label" for="kt_roles_select_all">
                                                    Pilih Semua
                                                </span>
                                            </label>
                                        </td>
                                    </tr>
                                    <input type="hidden" name="role_user" value="">
                                    <?php $no = 0; ?>
                                    <?php foreach ($list_menu as $lm) {
                                        echo '<tr>';
                                        echo '<td ' . ($lm->level == 'main_menu' ? "style='font-weight:500;font-size: 14px;'" : "") . '><div ' . ($lm->level == 'sub_menu' ? "style='margin-left:15px;'" : "") . '>' . $lm->nama_menu . '</div></td>';
                                        echo input_checkbox($no, $lm, 'akses');
                                        if ($lm->level == 'sub_menu') {
                                            echo input_checkbox($no, $lm, 'tambah');
                                            echo input_checkbox($no, $lm, 'edit');
                                            echo input_checkbox($no, $lm, 'hapus');
                                        } else {
                                            '<td colspan="3"></td>';
                                        }
                                        echo '</tr>';
                                        $no++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>