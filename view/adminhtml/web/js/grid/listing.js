define([], function () {
    'use strict';

    var mixin = {
        defaults: {
            template: 'DeveloperHub_ColorOrderCustomerGrid/ui/grid/listing'
        },
        getRowStyle: function (row) {
            var styles='';

            if(row.color_order!='')
                styles =  'background: '+row.color_order;
            else
                styles = '';

            return styles;
        }
    };

    return function(target) {
        return target.extend(mixin);
    };

});
