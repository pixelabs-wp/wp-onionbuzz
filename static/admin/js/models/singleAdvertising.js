var app = app || {};

(function($) {
    'use sctrict';
    app.singleAdvertising = Backbone.Model.extend({
        defaults: {
            id: 0,
            type: 0, // adsense, banner, custom
            user_id: 0,
            user_name: 'Unknown user',
            title: 'Title',
            image: 'images/ad.jpg',
            locations_count: 0,
            date_updated: '0000-00-00 00:00:00',
            date_added: '0000-00-00 00:00:00',
            flag_published: 1
        },

        validate: function(attributes){
            if(attributes.title === undefined){
                return "Set title for model";
            }
        },

        initialize: function(){
            //console.log('This model has been initialized.');
            this.on('change', function(){ //this.on('change:title', function(){
                console.log('Changes in model');
            });
        },
        test: function () {
            return this.get('title') + 'is showing.';
        }
    });
})(jQuery);