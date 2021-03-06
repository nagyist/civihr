define([
    'job-contract/filters/filters'
], function (filters) {
    'use strict';

    filters.filter('capitalize',['$log', function ($log) {
        $log.debug('Filter: capitalize');

        return function(input) {
            return (!!input) ? input.replace(/([^\W_]+[^\s-]*) */g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) : '';
        }
    }]);
});
