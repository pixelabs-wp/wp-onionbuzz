var app = app || {};

(function($) {
    'use sctrict';
    app.singleAdvertisingView = Backbone.View.extend({

        tagName: 'div',
        className: 'laqm-item',

        template: _.template( $('#advertisingItem').html() ),

        render: function(){
            var advertisingTemplate = this.template(this.model.toJSON());
            this.$el.html(advertisingTemplate);
            return this;
        },
        events:{
            'click .trigger-edit': 'editAdvertising',
            'click .trigger-delete': 'deleteAdvertising'
        },

        editAdvertising: function(){
            var editAdvertisingId = this.$el.find('.trigger-edit').data('id');
            window.location.href = '?page=la_onionbuzz_advertising&tab=advertising_edit&advertising_id='+editAdvertisingId;
        },
        deleteAdvertising: function() {

            var deleteAdvertisingId = this.$el.find('.trigger-delete').data('id');
            var thismodel = this.model;
            var thisview = this;

            $.confirm({
                escapeKey: true,
                backgroundDismiss: true,
                animation: 'right',
                closeAnimation: 'scale',
                title: 'Please, confirm',
                content: 'Do You want to delete this?',
                type: 'red',
                buttons: {
                    ok: {
                        text: "Delete",
                        btnClass: 'btn-danger',
                        keys: ['enter'],
                        action: function(){


                            var type = 'delete';
                            var data = {
                                'action': 'ob_advertising',
                                'id': deleteAdvertisingId
                            };
                            jQuery.post(ajaxurl+'?type='+type, data, function(response) {
                                response = jQuery.parseJSON(response);
                                if(response.success == 1){
                                    //thismodel.destroy(); it make ajax http://wordpress/wp-admin/admin-ajax.php/14 with DELETE request
                                    thisview.remove();//Delete view
                                }
                                else{
                                    appRouter.navigate("notdeleted",{trigger: true});
                                }
                            });
                        }
                    },
                    cancel: function(){
                        //console.log('the user clicked cancel');
                    }
                }
            });

        }

    });
})(jQuery);