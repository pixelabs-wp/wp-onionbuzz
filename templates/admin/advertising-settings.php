<div class="laqm-admin">

    <?php
    $data['templating']->render('admin/menu-top', $data);
    ?>

    <div class="laqm-content">

        <div class="laqm-breadcrumbs"></div>

        <div class="laqm-tabs-tools-container">

            <div class="laqm-tabs">
                <div class="laqm-tab-item ">
                    <a class="laqm-tab-item-link" href="?page=la_onionbuzz_advertising">All Ads</a> (<?=$data['total_ads']['total']?>)
                    <div class="pull-right">
                        <a class="laqm-btn laqm-btn-txt-large laqm-btn-blue" href="?page=la_onionbuzz_advertising&tab=advertising_edit"><span class="icon-ico-add"></span></a>
                    </div>

                </div>
                <div class="laqm-tab-item ">
                    <a class="laqm-tab-item-link" href="?page=la_onionbuzz_advertising&tab=locations">Locations</a>
                </div>
                <div class="laqm-tab-item active">
                    <a class="laqm-tab-item-link" href="?page=la_onionbuzz_advertising&tab=settings">Settings</a>
                </div>
            </div>

            <div class="laqm-tools floating-this">
                <div class="laqm-tools-item pull-right">
                    <a class="laqm-btn laqm-btn-green" href="#settings/save">Save</a>
                </div>
            </div>
            <div style="clear: both;"></div>

        </div>

        <div class="laqm-tab-content floating-area">

            <form id="form_advertising_settings" class="form-horizontal form-ays">
                <input name="form_action" type="hidden" value="submit_advertising_settings">
                <div class="form-group">
                    <label class="col-sm-3 control-label-left">No ADs for stories</label>
                    <div class="col-sm-6">
                        <input name="advertising_no_ads_stories" type="text" class="form-control" value="<?=esc_html($data['settings']['items']['no_ads_stories']['value'])?>" placeholder="Example: 3, 22, 87">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label-left">Show on mobiles</label>
                    <div class="col-sm-3">
                        <div class="switch">
                            <input id="advertising_show_on_mobiles" name="advertising_show_on_mobiles" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" <?=(@$data['settings']['items']['show_on_mobiles']['value'] == 1)?'checked':''?> value="<?=(isset($data['settings']['items']['show_on_mobiles']['value'])?$data['settings']['items']['show_on_mobiles']['value']:'')?>">
                            <label for="advertising_show_on_mobiles" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label-left">Show for logged-in users<p class="help-block"></p></label>
                    <div class="col-sm-3">
                        <div class="switch">
                            <input id="advertising_show_for_loggedin" name="advertising_show_for_loggedin" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" <?=(@$data['settings']['items']['show_for_loggedin']['value'] == 1)?'checked':''?> value="<?=(isset($data['settings']['items']['show_for_loggedin']['value'])?$data['settings']['items']['show_for_loggedin']['value']:'')?>">
                            <label for="advertising_show_for_loggedin" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>
            </form>

        </div>



    </div>
</div>