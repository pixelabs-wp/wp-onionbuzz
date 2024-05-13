var app = app || {};

(function($) {
    'use sctrict';
    app.allAdvertisingsView = Backbone.View.extend({

        tagName: 'div',
        className: 'laqm-items-list',

        render: function(){
            this.collection.each(this.addAdvertising, this);
            return this;
        },

        addAdvertising: function(advertising){
            var advertisingView = new app.singleAdvertisingView({model: advertising });
            this.$el.append(advertisingView.render().el)
        }

    });
})(jQuery);