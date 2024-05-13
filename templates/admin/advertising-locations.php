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
                <div class="laqm-tab-item active">
                    <a class="laqm-tab-item-link" href="?page=la_onionbuzz_advertising&tab=locations">Locations</a>
                </div>
                <div class="laqm-tab-item ">
                    <a class="laqm-tab-item-link" href="?page=la_onionbuzz_advertising&tab=settings">Settings</a>
                </div>
            </div>
            <div class="laqm-tools floating-this">
                <div class="laqm-tools-item pull-right">
                    <a class="laqm-btn laqm-btn-green" href="#locations/save">Save</a>
                </div>
            </div>
            <div style="clear: both;"></div>

        </div>

        <div class="laqm-tab-content floating-area">
            <form id="form_advertising_locations" class="form-horizontal form-ays">

                <input name="form_action" type="hidden" value="submit_advertising_locations">
                <div class="form-group">
                    <label class="col-sm-3 control-label-left">Before each story</label>
                    <div class="col-sm-5">
                        <select id="advertising_location_before_story" name="advertising_location_before_story" class="form-control laqm-select-default">
                            <option value="0" >Not Assigned</option>
                            <?php
                            foreach ($data['items'] as $k=>$v){ ?>
                                <option value="<?=$data['items'][$k]['id']?>" <?=(isset($data['locations']['items']['before_story']['advertising_id']) && $data['items'][$k]['id'] == $data['locations']['items']['before_story']['advertising_id'])?'selected':''?>><?=$data['items'][$k]['title']?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label-left">After each story</label>
                    <div class="col-sm-5">
                        <select id="advertising_location_after_story" name="advertising_location_after_story" class="form-control laqm-select-default">
                            <option value="0" >Not Assigned</option>
                            <?php
                            foreach ($data['items'] as $k=>$v){ ?>
                                <option value="<?=$data['items'][$k]['id']?>" <?=(isset($data['locations']['items']['after_story']['advertising_id']) && $data['items'][$k]['id'] == $data['locations']['items']['after_story']['advertising_id'])?'selected':''?>><?=$data['items'][$k]['title']?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label-left">Under the result area<p class="help-block">For quizzes only. Will be displayed after quiz is finished</p></label>
                    <div class="col-sm-5">
                        <select id="advertising_location_under_result" name="advertising_location_under_result" class="form-control laqm-select-default">
                            <option value="0" >Not Assigned</option>
                            <?php
                            foreach ($data['items'] as $k=>$v){ ?>
                                <option value="<?=$data['items'][$k]['id']?>" <?=(isset($data['locations']['items']['under_result']['advertising_id']) && $data['items'][$k]['id'] == $data['locations']['items']['under_result']['advertising_id'])?'selected':''?>><?=$data['items'][$k]['title']?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label-left">Between story 'items'<p class="help-block">Item is question in Quizzes or list items in Lists etc.</p></label>
                    <div class="col-sm-5">
                        <select id="advertising_location_between_items" name="advertising_location_between_items" class="form-control laqm-select-default">
                            <option value="0" >Not Assigned</option>
                            <?php
                            foreach ($data['items'] as $k=>$v){ ?>
                                <option value="<?=$data['items'][$k]['id']?>" <?=(isset($data['locations']['items']['between_items']['advertising_id']) && $data['items'][$k]['id'] == $data['locations']['items']['between_items']['advertising_id'])?'selected':''?>><?=$data['items'][$k]['title']?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label-left">Display AD after each<p class="help-block">Set how often AD will be displayed between story items</p></label>
                    <div class="col-sm-2">
                        <input name="advertising_location_between_count" type="text" class="form-control" value="<?=esc_html((isset($data['locations']['items']['between_count']['value']))?$data['locations']['items']['between_count']['value']:'')?>" placeholder="5">
                    </div>
                </div>

            </form>
        </div>



    </div>
</div>