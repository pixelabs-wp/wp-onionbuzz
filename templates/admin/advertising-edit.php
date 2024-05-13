<div class="laqm-admin">

    <?php
    $data['templating']->render('admin/menu-top', $data);
    ?>
    <div class="laqm-content floating-area">

        <div class="laqm-item-navigation">
            <div class="laqm-item-back"><a class="laqm-btn laqm-btn-blue no-left-margin" href="?page=la_onionbuzz_advertising">&larr; Back</a></div>
            <div class="laqm-item-name"><span><?=$data['edit_advertising']['page_title']?></span></div>
            <div class="laqm-item-nextprev pull-right">
                <?php if($data['prev_item']['id'] > 0){ ?>
                    <a class="laqm-btn laqm-btn-blue with-icon" href="?page=la_onionbuzz_advertising&tab=advertising_edit&advertising_id=<?=$data['prev_item']['id']?>"><span class="icon-arrow-left"></span></a>
                <?php } ?>
                <?php if($data['next_item']['id'] > 0){ ?>
                    <a class="laqm-btn laqm-btn-blue with-icon" href="?page=la_onionbuzz_advertising&tab=advertising_edit&advertising_id=<?=$data['next_item']['id']?>"><span class="icon-arrow-right"></span></a>
                <?php } ?>
            </div>
        </div>

        <div style="clear: both;"></div>

        <div class="laqm-tabs-tools-container">

            <div class="laqm-tabs">
                <div class="laqm-tab-item active">
                    <a class="laqm-tab-item-link" href="?page=la_onionbuzz_advertising&tab=advertising_edit&advertising_id=<?=$data['edit_advertising']['id']?>">Edit Ad</a>
                </div>

            </div>
            <div class="laqm-tools floating-this">
                <div class="laqm-tools-item pull-right">
                    <a class="laqm-btn laqm-btn-green" href="#advertising/<?=$data['edit_advertising']['id']?>/save">Save</a>
                    <a class="laqm-btn with-icon" href="?page=la_onionbuzz_advertisings"><span class="icon-ico-close"></span></a>
                </div>
            </div>

            <div style="clear: both;"></div>

        </div>

        <div class="laqm-tab-content">

            <div class="laqm-setting-tab settings-general" data-content="advertising-general">

                <form id="form_edit_advertising" class="form-horizontal form-ays">

                    <div class="form-group">
                        <label class="col-sm-3 control-label-left">Title <p class="help-block">Required. For internal use only.</p></label>
                        <div class="col-sm-5">
                            <input name="title" type="text" class="form-control" value="<?=esc_html((isset($data['edit_advertising']['title']))?$data['edit_advertising']['title']:'')?>" placeholder="Title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label-left">Ad type</p></label>
                        <div class="col-sm-5">
                            <select id="advertising_type" name="type" class="form-control laqm-select-default">
                                <option value="adsense" <?=(isset($data['edit_advertising']['type']) && $data['edit_advertising']['type'] == 'adsense')?'selected':''?>>Adsense</option>
                                <option value="image" <?=(isset($data['edit_advertising']['type']) && $data['edit_advertising']['type'] == 'image')?'selected':''?>>Image</option>
                                <option value="custom" <?=(isset($data['edit_advertising']['type']) && $data['edit_advertising']['type'] == 'custom')?'selected':''?>>Custom code</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" data-type-group="adsense">
                        <label class="col-sm-3 control-label-left">Adsense Code<p class="help-block">Find code on Ad Units list on your Google Adsense Account.</p></label>
                        <div class="col-sm-9">
                            <textarea name="code_adsense" type="text" class="form-control" style="height: 150px;"><?=(isset($data['edit_advertising']['code'])?$data['edit_advertising']['code']:'')?></textarea>
                        </div>
                    </div>
                    <div class="form-group" data-type-group="custom">
                        <label class="col-sm-3 control-label-left">Custom code</label>
                        <div class="col-sm-9">
                            <textarea name="code" type="text" class="form-control" style="height: 150px;"><?=(isset($data['edit_advertising']['code'])?$data['edit_advertising']['code']:'')?></textarea>
                        </div>
                    </div>
                    <div class="form-group" data-type-group="image">
                        <label class="col-sm-3 control-label-left">Image<p class="help-block"></p></label>
                        <div class="col-sm-3">
                            <a id="media_test" class="laqm-item-image-add" href="#" <?=(isset($data['edit_advertising']['image']) && $data['edit_advertising']['image'] != '')?'style="background-image: url('.$data['edit_advertising']['image'].');"':''?>></a>
                            <input name="featured_image" value="<?=(isset($data['edit_advertising']['image']))?$data['edit_advertising']['image']:''?>" type="hidden">
                            <input name="attachment_id" value="" type="hidden">
                            <a class="remove-featured-image" href="javascript:void(0);">Remove</a>
                        </div>
                        <div class="col-sm-3"></div>
                    </div>
                    <div class="form-group" data-type-group="image">
                        <label class="col-sm-3 control-label-left">Link for image</label>
                        <div class="col-sm-9">
                            <input name="link" type="text" class="form-control" value="<?=esc_html((isset($data['edit_advertising']['link']))?$data['edit_advertising']['link']:'')?>" placeholder="http://onionbuz.com/">
                        </div>
                    </div>
                    <div class="form-group" data-type-group="image">
                        <label class="col-sm-3 control-label-left">Open in new window</label>
                        <div class="col-sm-3">
                            <div class="switch">
                                <input id="flag_newwindow" name="flag_newwindow" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" <?=(@$data['edit_advertising']['flag_newwindow'] == 1)?'checked':''?> value="<?=(isset($data['edit_advertising']['flag_newwindow'])?$data['edit_advertising']['flag_newwindow']:'')?>">
                                <label for="flag_newwindow" data-on="Yes" data-off="No"></label>
                            </div>
                        </div>
                    </div>

                </form>


            </div>

        </div>

    </div>
</div>
<?php
$data['templating']->render('admin/templates/advertising', $data);
?>