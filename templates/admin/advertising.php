<div class="laqm-admin">

    <?php
    $data['templating']->render('admin/menu-top', $data);
    ?>

    <div class="laqm-content">

        <div class="laqm-breadcrumbs"></div>

        <div class="laqm-tabs-tools-container">

            <div class="laqm-tabs">
                <div class="laqm-tab-item active">
                    <a class="laqm-tab-item-link" href="?page=la_onionbuzz_advertising">All Ads</a> (<?=$data['total_ads']['total']?>)
                    <div class="pull-right">
                        <a class="laqm-btn laqm-btn-txt-large laqm-btn-blue" href="?page=la_onionbuzz_advertising&tab=advertising_edit"><span class="icon-ico-add"></span></a>
                    </div>

                </div>
                <div class="laqm-tab-item ">
                    <a class="laqm-tab-item-link" href="?page=la_onionbuzz_advertising&tab=locations">Locations</a>
                </div>
                <div class="laqm-tab-item ">
                    <a class="laqm-tab-item-link" href="?page=la_onionbuzz_advertising&tab=settings">Settings</a>
                </div>
            </div>

            <div style="clear: both;"></div>

        </div>
        <div class="laqm-search-filters">
            <div class="laqm-filters">

                <div class="laqm-select laqm-select-blue arrow-grey-down laqm-item-sortby laqm-inlineblock">
                    <select id="advertising_sort" name="search-sort">
                        <option value="date_added" data-type="desc">Newest on top</option>
                        <option value="date_added" data-type="asc">Oldest on top</option>
                        <option value="title" data-type="asc">Title (a-z)</option>
                        <option value="title" data-type="desc">Title (z-a)</option>
                    </select>
                </div>


                <div style="clear: both;"></div>
            </div>
            <div class="laqm-search">
                <input id="advertising_search" class="laqm-search-input" placeholder="Search">
            </div>
            <div style="clear: both;"></div>
        </div>

        <div class="laqm-tab-content">

            <div id="laqm-advertisings-list" class="laqm-items-list">
            </div>

        </div>


        <div class="laqm-pagination"></div>

    </div>
</div>
<?php
$data['templating']->render('admin/templates/advertising', $data);
?>