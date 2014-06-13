var nookApp = angular.module('nookApp', []);

// To add specific range
nookApp.filter('range', function() {
    return function(input, total) {
        total = parseInt(total);
        for (var i=0; i<total; i++)
            input.push(i);
        return input;
    };
});

// To filter the grid
nookApp.filter('filterGrid', function(){
    return function(productFamilies, allocatedBy) {
        if(allocatedBy && allocatedBy.dataIndex != 'all') {
            productFamilies = _.select(productFamilies, function(productFamily) {
                if(allocatedBy.dataIndex == "devices")
                    return productFamily.type == "hd" || productFamily.type == "hd+";
                else
                    return productFamily.type == allocatedBy.dataIndex;
            });
        }
        return productFamilies;
    };
});

function GridCtrl($scope) {
    $scope.table1Visibility = '';
    $scope.table2Visibility = 'hide';

    $scope.allocatedBySelectModel = [
        {dataIndex:"all", value:"All devices and Accessories"},
        {dataIndex:"devices", value:"Devices"},
        {dataIndex:"accessories", value:"Accessories"},
        {dataIndex:"hd", value:"HD"},
        {dataIndex:"hd+", value:"HD+"}
    ];

    $scope.allocatedBy = $scope.allocatedBySelectModel[0];

    $scope.periodSelectModel = [
        {dataIndex:"21", value:"21 Days", select:true},
        {dataIndex:"month", value:"Monthly"},
        {dataIndex:"quarter", value:"Quarterly"},
        {dataIndex:"year", value:"Yearly"}
    ];
    $scope.period = $scope.periodSelectModel[0];

    $scope.onPeriodChange = function() {
        $scope.gridData = $scope.getGridData();
    };

    $scope.onAllocatedByChange = function() {
    };

    $scope.getHeaders = function() {

        var headers = [];
        headers.push({dataIndex: 'customer', text: 'Customer/Channel Partners'});
        headers.push({dataIndex: 'products', text: 'Products'});
        var timeStamp;
        if($scope.period)
            timeStamp = $scope.period.dataIndex; //"year"

        if(timeStamp == "month") {
            for(var i=0; i<12; i++) {
                headers.push({dataIndex: 'period' + (i+1), text: moment().add('months', i).format("MMM-YYYY")});
            }
        } else if(timeStamp == "quarter") {
            //var currentQuarter = Math.floor(moment().month() / 3) + 1;
            var startDate = moment("20120301", "YYYYMMDD");
            for(var i=0; i<4; i++) {
                headers.push({dataIndex: 'period' + (i+1), text: startDate.add('months', 1).format("MMM YYYY") + ' - ' + startDate.add('months', 2).format("MMM YYYY")});
            }
        } else if(timeStamp == "year") {
            for(var i=0; i<4; i++) {
                headers.push({dataIndex: 'period' + (i+1), text: moment().add('year', i).format("YYYY")});
            }
        } else {
            for(var i=-7; i<14; i++) {
                headers.push({dataIndex: 'period' + (i+8), text: moment().add('days', i).format("MM/DD/YYYY")});
            }
        }
        return headers;
    };

    $scope.getPeriods = function(name) {
        var record = {
            name:name
        };

        for(var i=1; i <= $scope.getHeaders().length-2;i++) {
            record['period' + i] = 20;
            record['count' + i] = 100;
        }

        return record;
    };

    $scope.getGridData = function() {
        return {
            headers:$scope.getHeaders(),
            periodLength:$scope.getHeaders().length-2,
            records: [
                {
                    customer:"Walmart",
                    status:'Current',
                    productFamilies:[
                        {
                            type: 'accessories',
                            name: "Accessories",
                            products:[
                                $scope.getPeriods("Charger"),
                                $scope.getPeriods("Headset")
                            ]
                        },
                        {
                            type: 'hd',
                            name: "Nook HD",
                            products:[
                                $scope.getPeriods("Nook HD 16GB SNOW"),
                                $scope.getPeriods("Nook HD 32GB SNOW"),
                                $scope.getPeriods("Nook HD 16GB SMOKE"),
                                $scope.getPeriods("Nook HD 32GB SMOKE")
                            ]
                        },
                        {
                            type: 'hd+',
                            name: "Nook HD+",
                            products:[
                                $scope.getPeriods("Nook HD+ 16GB")
                            ]
                        }
                    ]
                },
                {
                    customer:"Walmart",
                    status:'Cumulative',
                    productFamilies:[
                        {
                            type: 'hd',
                            name: "Nook HD",
                            products:[
                                $scope.getPeriods("Nook HD 16GB SNOW"),
                                $scope.getPeriods("Nook HD 32GB SNOW"),
                                $scope.getPeriods("Nook HD 16GB SMOKE"),
                                $scope.getPeriods("Nook HD 32GB SMOKE")
                            ]
                        },
                        {
                            type: 'hd+',
                            name: "Nook HD+",
                            products:[
                                $scope.getPeriods("Nook HD+ 16GB")
                            ]
                        }
                    ]
                }
            ]
        };
    }

    $scope.gridData = $scope.getGridData();
}