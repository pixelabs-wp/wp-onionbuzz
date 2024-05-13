var app = app || {};

(function($) {
    'use sctrict';
    app.Router = Backbone.Router.extend({
        routes: {
            '': 'index',
            'advertising/:id/save' : 'save',
            'locations/save' : 'save',
            'settings/save' : 'save',
            'advertisings(/:query)(/:page)(/:ob/:ot)' : 'advertisings',
            'saved': 'saved',
            'notsaved': 'notsaved',
            '*default2': 'default2'
        },
        options:{
            'page': 'la_onionbuzz_advertising',
            'tab': '',
            'advertising_id': 0
        },
        hashed:{
            'items': 'advertisings',
            'advertising_id': 'advertising_id',
            'query': 'all',
            'current_page': 1,
            'orderby': 'date_added',
            'ordertype' : 'desc'
        },
        initialize: function() {

            //this.bind( "all", this.change )
            this.options.page = this.getParameterByName('page');
            this.options.tab = this.getParameterByName('tab');
            this.options.quiz_id = this.getParameterByName('advertising_id');
            if($("input[name='featured_image']").val() != ''){
                $('.remove-featured-image').show();
            }

        },
        renavigateHashed: function(type){
            appRouter.navigate('/' + this.hashed.items + '/' + this.hashed.query + '/' + this.hashed.current_page + '/' + this.hashed.orderby + '/' + this.hashed.ordertype, {trigger: true});

        },
        index: function(){


            console.log(this.options.tab);
            if(this.options.page == 'la_onionbuzz_advertising' && !this.options.tab){
                //appRouter.navigate('/feeds', { trigger: true });
                appRouter.renavigateHashed();
            }
            if(this.options.page == 'la_onionbuzz_advertising' && this.options.tab == 'advertising_edit'){
                //appRouter.navigate('/results/'+this.options.quiz_id, { trigger: true });
            }


        },
        advertisings: function(query, page, ob, ot){

            var advertisingGroup = new app.AdvertisingsCollection([]);
            $('#laqm-advertisings-list').html("<div class='uil-rolling-css' style='transform:scale(0.2);'><div><div></div><div></div></div></div>");
            advertisingGroup.fetch({
                data: {
                    action: 'ob_advertisings',
                    section: 'advertisings',
                    do: 'loadlist',
                    query: query,
                    page: page,
                    orderby: ob,
                    ordertype: ot
                },
                success: function(collection, object, jqXHR) {
                    //console.log(collection.page); // this is in #13 /static/admin/js/collections/allQuizzes.js
                    //console.log(collection.total_items);
                    //console.log(collection.total_pages);

                    var advertisingGroupView = new app.allAdvertisingsView({collection: advertisingGroup});
                    $('#laqm-advertisings-list').html(advertisingGroupView.render().el);

                    if (advertisingGroup.length == 0){
                        $('#laqm-advertisings-list').html('You have not created anything yet.');
                    }

                    pgOptions = {
                        totalPages: parseInt(collection.total_pages),
                        visiblePages: 10,
                        currentPage: parseInt(collection.page),
                        activeClass: 'active',
                        first: '<a class="prev" href="javascript:void(0);">First</a>',
                        last: '<a class="prev" href="javascript:void(0);">Last</a>',
                        prev: '<a class="prev" href="javascript:void(0);">Prev</a>',
                        next: '<a class="next" href="javascript:void(0);">Next</a>',
                        page: '<a class="active" href="javascript:void(0);">{{page}}</a>',
                        onPageChange: function (num, type) {
                            appRouter.hashed.current_page = num;
                            appRouter.renavigateHashed();
                            console.log(type + ':' + num);
                        }
                    };
                    if(collection.total_pages > 0) {
                        $.jqPaginator('.laqm-pagination', pgOptions);
                    }


                },
                error: function(jqXHR, statusText, error) {
                    $('#laqm-advertisings-list').html(error);
                }
            });

        },

        get_tinymce_content: function (id) {
            var content;
            var inputid = id;
            var editor = tinyMCE.get(inputid);
            var textArea = jQuery('textarea#' + inputid);
            if (textArea.length>0 && textArea.is(':visible')) {
                content = textArea.val();
            } else {
                content = editor.getContent();
            }
            console.log('get_tinymce_content');
            return content;
        },
        save: function(id){
            //console.log(this.options.tab);

            if(this.options.tab == 'advertising_edit') {

                if(!$("input[name='title']").val().trim()){
                    new PNotify({
                        title: 'Error',
                        text: 'Please, add a title',
                        icon: 'glyphicon glyphicon-info-sign',
                        type: 'error',
                        hide: true,
                        buttons: {
                            closer: true,
                            sticker: false
                        },
                        history: {
                            history: false
                        }
                    });
                    appRouter.navigate("#", {trigger: true});
                    return false;
                }

                var id = id;
                var type = 'save';
                var data = {
                    'action': 'ob_advertising',
                    'id': id,
                    'title': $("input[name='title']").val(),
                    'atype': $("select[name='type'] option:selected").val(),
                    'code_adsense': $("textarea[name='code_adsense']").val(),
                    'code': $("textarea[name='code']").val(),
                    'featured_image': $("input[name='featured_image']").val(),
                    'attachment_id': $("input[name='attachment_id']").val(),
                    'link': $("input[name='link']").val(),
                    'flag_newwindow': ($("input[name='flag_newwindow']").is(":checked"))?1:0
                };
                $('#onionbuzz_loader').addClass("is-active");

                jQuery.post(ajaxurl + '?type=' + type, data, function (response) {
                    response = jQuery.parseJSON(response);
                    if (response.success == 1) {

                        $('#onionbuzz_loader').removeClass("is-active");

                        $('.laqm-item-name span').html(data.title);

                        appRouter.navigate("saved", {trigger: true});
                        $('.form-ays').trigger('reinitialize.areYouSure');
                        if (response.id > 0) {
                            if(id == 0) {
                                window.location.href = '?page=la_onionbuzz_advertising&tab=advertising_edit&advertising_id=' + response.id + '';
                            }
                        }
                    }
                    else {
                        $('#onionbuzz_loader').removeClass("is-active");
                        appRouter.navigate("notsaved", {trigger: true});
                    }
                });
            }

            if(this.options.tab == 'locations'){
                var type = 'locations_save';
                var data = {
                    'action': 'ob_advertising',
                    'before_story': $("select[name='advertising_location_before_story'] option:selected").val(),
                    'after_story': $("select[name='advertising_location_after_story'] option:selected").val(),
                    'under_result': $("select[name='advertising_location_under_result'] option:selected").val(),
                    'between_items': $("select[name='advertising_location_between_items'] option:selected").val(),
                    'between_count': $("input[name='advertising_location_between_count']").val()
                };

                $('#onionbuzz_loader').addClass("is-active");
                jQuery.post(ajaxurl + '?type=' + type, data, function (response) {
                    response = jQuery.parseJSON(response);

                    if (response.success == 1) {
                        appRouter.navigate("saved", {trigger: true});
                        $('.form-ays').trigger('reinitialize.areYouSure');
                        $('#onionbuzz_loader').removeClass("is-active");
                    }
                    else {
                        appRouter.navigate("notsaved", {trigger: true});
                        $('#onionbuzz_loader').removeClass("is-active");
                    }
                });
            }
            if(this.options.tab == 'settings'){
                var type = 'settings_save';
                var data = {
                    'action': 'ob_advertising',
                    'no_ads_stories': $("input[name='advertising_no_ads_stories']").val(),
                    'show_on_mobiles': ($("input[name='advertising_show_on_mobiles']").is(":checked"))?1:0,
                    'show_for_loggedin': ($("input[name='advertising_show_for_loggedin']").is(":checked"))?1:0
                };

                $('#onionbuzz_loader').addClass("is-active");
                jQuery.post(ajaxurl + '?type=' + type, data, function (response) {
                    response = jQuery.parseJSON(response);

                    if (response.success == 1) {
                        appRouter.navigate("saved", {trigger: true});
                        $('.form-ays').trigger('reinitialize.areYouSure');
                        $('#onionbuzz_loader').removeClass("is-active");
                    }
                    else {
                        appRouter.navigate("notsaved", {trigger: true});
                        $('#onionbuzz_loader').removeClass("is-active");
                    }
                });
            }
        },
        saved: function(){
            new PNotify({
                title: 'Info',
                text: 'Changes saved.',
                icon: 'glyphicon glyphicon-info-sign',
                type: 'info',
                hide: true,
                buttons: {
                    closer: true,
                    sticker: false
                },
                history: {
                    history: false
                }
            });
        },
        notsaved: function(){
            new PNotify({
                title: 'Error',
                text: 'Changes not saved. Try again...',
                icon: 'glyphicon glyphicon-info-sign',
                type: 'error',
                hide: true,
                buttons: {
                    closer: true,
                    sticker: false
                },
                history: {
                    history: false
                }
            });
        },
        getParameterByName: function(name, url) {
            if (!url) {
                url = window.location.href;
            }
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        },
        default2: function(default2){
            console.log("Quiz route: " +  default2);

        }

    });

    var appRouter = new app.Router();

    Backbone.history.start();

    $('#advertising_search').bind("enterKey",function(e){
        if($(this).val() != ''){
            //appRouter.navigate("feeds/"+$(this).val(), {trigger: true});
            appRouter.hashed.current_page = 1;
            appRouter.hashed.query = $(this).val();
            appRouter.renavigateHashed();
        }
        else{
            //appRouter.navigate("feeds", {trigger: true});
            appRouter.hashed.query = 'all';
            appRouter.renavigateHashed();
        }
    });
    $('#advertising_search').keyup(function(e){
        if(e.keyCode == 13)
        {
            $(this).trigger("enterKey");
        }
    });
    $('#advertising_sort').change(function(){
        appRouter.hashed.orderby = $(this).val();
        appRouter.hashed.ordertype = $(this).find(':selected').data('type');
        appRouter.renavigateHashed();
    });

    /*$(".button-show-add-form").on("click", function () {
        $(".container-add-form[data-question-id='0']").toggle();
    });*/
    /*$(".submit-add-form").on("click", function(){
        var $container_form = $(this).closest(".container-add-form");
        var edit_question_id = $container_form.data("question-id");

        //console.log(appRouter.options.tab,edit_question_id);
        if(appRouter.options.tab == 'quiz_questions'){
            if(!$container_form.find("input[name='question_title']").val().trim() && !$container_form.find("input[name='featured_image']").val().trim() ){
                new PNotify({
                    title: 'Error',
                    text: 'Please, add at least Title or Image',
                    icon: 'glyphicon glyphicon-info-sign',
                    type: 'error',
                    hide: true,
                    buttons: {
                        closer: true,
                        sticker: false
                    },
                    history: {
                        history: false
                    }
                });
                //appRouter.navigate("#", {trigger: true});
                return false;
            }

            var id = edit_question_id;
            var type = 'save';
            var data = {
                'action': 'ob_quiz_question',
                'id': id,
                'quiz_id': appRouter.options.quiz_id,
                'flag_published': ($container_form.find("input[name='question_published']").is(":checked"))?1:0,
                'title': $container_form.find("input[name='question_title']").val(),
                //'description': $container_form.find("textarea[name='question_description']").val(),
                'description': appRouter.get_tinymce_content('question_description'),
                'featured_image': $container_form.find("input[name='featured_image']").val(),
                'image_caption': $container_form.find("[name='question_image_caption']").val(),
                'attachment_id': $container_form.find("[name='attachment_id']").val(),
                'secondary_image': $container_form.find("input[name='secondary_image']").val(),
                'secondary_image_caption': $container_form.find("[name='secondary_image_caption']").val(),
                'secondary_attachment_id': $container_form.find("[name='secondary_attachment_id']").val(),
                'mediagrid_type': 'flex2',
                'answers_type': "list",
                'explanation_title': '',
                'explanation' : '',
                'explanation_image' : '',
                'flag_explanation' : 0,
                'flag_pagebreak' : 0,
                'flag_casesensitive' : 0
            };
            //console.log(data);
            $('#onionbuzz_loader').addClass("is-active");

            jQuery.post(ajaxurl + '?type=' + type, data, function (response) {

                response = jQuery.parseJSON(response);

                //console.log(response);

                if (response.success == 1) {
                    $('#onionbuzz_loader').removeClass("is-active");
                    //$('.laqm-item-name span').html(data.title);

                    new PNotify({
                        title: 'Info',
                        text: 'Changes saved.',
                        icon: 'glyphicon glyphicon-info-sign',
                        type: 'info',
                        hide: true,
                        buttons: {
                            closer: true,
                            sticker: false
                        },
                        history: {
                            history: false
                        }
                    });

                    $container_form.find('.form-ays').trigger('reinitialize.areYouSure');
                    if (response.id > 0) {
                        //$container_form.data("question-id", response.id);
                        $container_form.find("input").val("");
                        //$container_form.find("textarea").val("");
                        tinymce.get('question_description').setContent("");
                        $container_form.find(".remove-featured-image-ajaxform").trigger("click");
                        Backbone.history.stop();
                        Backbone.history.start();
                    }
                }
                else {
                    Backbone.history.stop();
                    Backbone.history.start();
                }
            });
        }

    });*/


})(jQuery);


