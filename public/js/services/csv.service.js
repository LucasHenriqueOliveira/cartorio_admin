(function () {
    'use strict';

    angular
        .module('app')
        .factory('Csv', Csv);

    Csv.$inject = [];

    function Csv() {
        return {
            download: function(data, name) {

                var csvContent = 'data:text/csv;charset=utf-8,';
                for (var d in data) {
                    var headers = '';
                    for (var dn in data[d]) {
                        headers += (headers ? ',' : '') + dn;
                    }
                    csvContent += headers + "\n";
                    break;
                }
                for (var d in data) {
                    var content = '';
                    for (var dn in data[d]) {
                        content += (content ? ',' : '') + data[d][dn];
                    }
                    csvContent += content + "\n";
                }

                var encodedUri = encodeURI(csvContent);
                var link = document.createElement('a');
                link.setAttribute('href', encodedUri);
                link.setAttribute('download', name + '.csv');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        };
    }
})();