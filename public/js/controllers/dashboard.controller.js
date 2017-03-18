(function () {
    'use strict';

    angular
        .module('app')
        .controller('DashboardController', DashboardController);

    DashboardController.$inject = ['$scope', '$rootScope', 'DataService', 'App', 'Csv'];

    function DashboardController($scope, $rootScope, DataService, App, Csv) {
        $scope.showLastWeekImpressions = false;
        $scope.showLastWeekRevenue = false;
        $scope.showLastWeekECPM = false;
        $scope.optionPerformance = 'ecpm';
        $scope.optionEffectiveRevenue = 'mob';
        $scope.optionPerformance = 'mob';

        $scope.query = {
            publisher: '',
            start: '',
            end: ''
        };

        var curr = new Date;
        var dd = curr.getDate();
        var mm = curr.getMonth()+1;
        var yyyy = curr.getFullYear();
        var first = curr.getDate() - curr.getDay();

        $scope.query.start = yyyy + '-' + mm + '-' + first;
        $scope.query.end = yyyy + '-' + mm + '-' + dd;

        $scope.downloadImpressions = function() {
            Csv.download($scope.impressions, 'impressions');
        };
        $scope.downloadPerformance = function() {
            Csv.download($scope.performance, 'performance');
        };
        $scope.downloadEffectiveRevenue = function() {
            Csv.download($scope.effective_revenue, 'effective_revenue');
        };
        $scope.downloadEarnedRevenue = function() {
            Csv.download($scope.earned_revenue, 'earned_revenue');
        };
        $scope.downloadSiteStats = function() {
            Csv.download($scope.site_stats, 'site_stats');
        };


        var chartOption = function(type) {
            return {
                responsive: true,
                legend: {
                    display: true,
                    position: 'top'
                },
                events: false,
                animation: {
                    duration: 500,
                    easing: "easeOutQuart",
                    onComplete: function () {
                        var ctx = this.chart.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function (dataset) {

                            for (var i = 0; i < dataset.data.length; i++) {
                                var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
                                    total = dataset._meta[Object.keys(dataset._meta)[0]].total,
                                    mid_radius = model.innerRadius + (model.outerRadius - model.innerRadius)/2,
                                    start_angle = model.startAngle,
                                    end_angle = model.endAngle,
                                    mid_angle = start_angle + (end_angle - start_angle)/2;

                                var x = mid_radius * Math.cos(mid_angle);
                                var y = mid_radius * Math.sin(mid_angle);

                                ctx.fillStyle = '#fff';
                                if (i == 3){ // Darker text color for lighter background
                                    ctx.fillStyle = '#444';
                                }
                                var percent = String(Math.round(dataset.data[i]/total*100)) + "%";
                                switch (type) {
                                    case 'impressions':
                                        ctx.font = '10px Arial';
                                        ctx.fillText((parseFloat(dataset.data[i])).toLocaleString('en'), model.x + x, model.y + y);
                                        break;
                                    case 'ecpm':
                                        ctx.fillText('$'+dataset.data[i], model.x + x, model.y + y);
                                        break;
                                    case 'revenue':
                                        ctx.fillText((parseFloat(dataset.data[i])).toLocaleString('en', { style: 'currency', currency: 'USD' }), model.x + x, model.y + y);
                                        break;
                                }
                                // Display percent in another line, line break doesn't work for fillText
                                ctx.fillText(percent, model.x + x, model.y + y + 15);
                            }
                        });
                    }
                }
            };
        };

        var getDashboard = function() {
            DataService.getDashboard($scope.query).then(function(response) {
                if(response.error === false) {
                    $scope.impressions_week = response.statics_week.impressions;
                    $scope.ecpm_week = response.statics_week.ecpm;
                    $scope.revenue_week = response.statics_week.revenue;

                    $scope.impressions_last_week = response.statics_last_week.impressions;
                    $scope.ecpm_last_week = response.statics_last_week.ecpm;
                    $scope.revenue_last_week = response.statics_last_week.revenue;

                    $scope.impressions = response.impressions;

                    $scope.effective_revenue = response.effective_revenue;
                    $scope.display = ($scope.effective_revenue[1].ecpm);


                    // impressions
                    $scope.labels = [];
                    $scope.data = [];
                    $scope.impressions.forEach(function(entry){
                        $scope.labels.push(entry.date);
                        $scope.data.push(parseInt(entry.impression));
                    });

                    $scope.data = [$scope.data];
                    $scope.series = ['Impression'];
                    $scope.onClick = function (points, evt) {
                        console.log(points, evt);
                    };
                    $scope.datasetOverride = [{ yAxisID: 'y-axis-1' }];
                    $scope.options = {
                        responsive: true,
                        scales: {
                            yAxes: [
                                {
                                    id: 'y-axis-1',
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    ticks: {
                                        userCallback: function(value, index, values) {
                                            return parseFloat(value).toLocaleString('en');
                                        }
                                    }
                                }
                            ]
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    return parseFloat(tooltipItem.yLabel).toLocaleString('en');
                                }
                            }
                        }
                    };

                    $scope.site_stats = response.site_stats;

                    $scope.site_stats.forEach(function(element, index, array){
                        if(element.geo == 'in'){
                            $scope.site_stats_impressions_in = element.impressions;
                            $scope.site_stats_ecpm_in = element.ecpm;
                        } else if(element.geo == 'us'){
                            $scope.site_stats_impressions_us = element.impressions;
                            $scope.site_stats_ecpm_us = element.ecpm;
                        }
                    });


                    // sitestats - impressions
                    $scope.labels3 = ["United States", "International"];
                    $scope.data3 = [$scope.site_stats_impressions_us, $scope.site_stats_impressions_in];
                    $scope.options3 = chartOption('impressions');
                    $scope.colours3 = ['#EA537E', '#195D80'];


                    // sitestats - ecpm
                    $scope.labels4 = ["United States", "International"];
                    $scope.data4 = [$scope.site_stats_ecpm_us, $scope.site_stats_ecpm_in];
                    $scope.options4 = chartOption('ecpm');
                    $scope.colours4 = ['#72C02C', '#3498DB'];

                    // earned_revenue
                    $scope.earned_revenue = response.earned_revenue;
                    $scope.earned_revenue_quantity = ($scope.earned_revenue[1].revenue);
                    $scope.earned_revenue_label = ($scope.earned_revenue[1].product);

                    $scope.labels5 = [$scope.earned_revenue_label];
                    $scope.data5 = [$scope.earned_revenue_quantity];
                    $scope.options5 = chartOption('revenue');
                    $scope.colours5 = ['#70BFB3','#72C02C', '#3498DB'];


                    var horizontalBarOptions = {
                        responsive: true,
                        hover: {
                            animationDuration: 0
                        },
                        animation: {
                            duration: 500,
                            onComplete: function () {
                                var ctx = this.chart.ctx;
                                ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, 'normal', Chart.defaults.global.defaultFontFamily);
                                ctx.fillStyle = this.chart.config.options.defaultFontColor;
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'bottom';
                                this.data.datasets.forEach(function (dataset) {
                                    for (var i = 0; i < dataset.data.length; i++) {
                                        var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model;
                                        var value = '';
                                        if(model.x > 950) {
                                            value = -20;
                                        } else {
                                            value = 22;
                                        }
                                        ctx.fillText('$'+dataset.data[i], model.x + value, model.y + 8);
                                    }
                                });
                            }
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    return '$'+ tooltipItem.xLabel;
                                }
                            }
                        }
                    };

                    // performance
                    $scope.performance = response.performance;
                    $scope.labels2 = [];
                    $scope.data2 = [];

                    $scope.performance.forEach(function(element, index, array){
                        if(element.device == 'mob'){
                            $scope.labels2.push(element.slot);
                            $scope.data2.push(element.ecpm);
                        }
                    });
                    $scope.data2 = [$scope.data2];

                    $scope.series2 = ['eCPM'];
                    $scope.options2 = horizontalBarOptions;
                    $scope.colours2 = ['#195D80'];

                } else {
                    $scope.message = response.message;
                }
            });
        };

        $scope.$watch('query', function() {
            $scope.publisherName = $scope.query.publisher ? $rootScope.app.publishers[$scope.query.publisher] : '';
            getDashboard();
        }, true);


        $scope.getEffectiveRevenue = function(type){
            $scope.effective_revenue.forEach(function(element, index, array){
                if(element.device == type){
                    $scope.display = element.ecpm;
                }
            });
            $scope.optionEffectiveRevenue = type;
        };

        $scope.getEarnedRevenue = function(type){
            $scope.earned_revenue.forEach(function(element, index, array){
                if(element.device == type){
                    $scope.data5 = [element.revenue];
                }
            });
        };

        $scope.getPerformance = function(type) {
            $scope.labels2 = [];
            $scope.data2 = [];

            $scope.performance.forEach(function(element, index, array){
                if(element.device == type){
                    $scope.labels2.push(element.slot);
                    $scope.data2.push(element.ecpm);
                }
            });
            $scope.data2 = [$scope.data2];
            $scope.optionPerformance = type;
        };

        $scope.getOptionPerformance = function(type) {
            $scope.optionPerformance = type;
        };

        setTimeout(function(){
            jQuery(document).ready(function(){
                var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
                    $BODY = $('body'),
                    $MENU_TOGGLE = $('#menu_toggle'),
                    $SIDEBAR_MENU = $('#sidebar-menu'),
                    $SIDEBAR_FOOTER = $('.sidebar-footer'),
                    $LEFT_COL = $('.left_col'),
                    $RIGHT_COL = $('.right_col'),
                    $NAV_MENU = $('.nav_menu'),
                    $FOOTER = $('footer');

                // TODO: This is some kind of easy fix, maybe we can improve this
                var setContentHeight = function () {
                    // reset height
                    $RIGHT_COL.css('min-height', $(window).height());

                    var bodyHeight = $BODY.outerHeight(),
                        footerHeight = $BODY.hasClass('footer_fixed') ? -10 : $FOOTER.height(),
                        leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
                        contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

                    // normalize content
                    contentHeight -= $NAV_MENU.height() + footerHeight;

                    $RIGHT_COL.css('min-height', contentHeight);
                };

                $SIDEBAR_MENU.find('a').on('click', function(ev) {
                    console.log('clicked - sidebar_menu');
                    var $li = $(this).parent();

                    if ($li.is('.active')) {
                        $li.removeClass('active active-sm');
                        $('ul:first', $li).slideUp(function() {
                            setContentHeight();
                        });
                    } else {
                        // prevent closing menu if we are on child menu
                        if (!$li.parent().is('.child_menu')) {
                            $SIDEBAR_MENU.find('li').removeClass('active active-sm');
                            $SIDEBAR_MENU.find('li ul').slideUp();
                        }else
                        {
                            if ( $BODY.is( ".nav-sm" ) )
                            {
                                $SIDEBAR_MENU.find( "li" ).removeClass( "active active-sm" );
                                $SIDEBAR_MENU.find( "li ul" ).slideUp();
                            }
                        }
                        $li.addClass('active');

                        $('ul:first', $li).slideDown(function() {
                            setContentHeight();
                        });
                    }
                });

                // toggle small or large menu
                $MENU_TOGGLE.on('click', function() {
                    console.log('clicked - menu toggle');

                    if ($BODY.hasClass('nav-md')) {
                        $SIDEBAR_MENU.find('li.active ul').hide();
                        $SIDEBAR_MENU.find('li.active').addClass('active-sm').removeClass('active');
                    } else {
                        $SIDEBAR_MENU.find('li.active-sm ul').show();
                        $SIDEBAR_MENU.find('li.active-sm').addClass('active').removeClass('active-sm');
                    }

                    $BODY.toggleClass('nav-md nav-sm');

                    setContentHeight();
                });

                // check active menu
                $SIDEBAR_MENU.find('a[href="' + CURRENT_URL + '"]').parent('li').addClass('current-page');

                $SIDEBAR_MENU.find('a').filter(function () {
                    return this.href == CURRENT_URL;
                }).parent('li').addClass('current-page').parents('ul').slideDown(function() {
                    setContentHeight();
                }).parent().addClass('active');

                // recompute content when resizing
                $(window).smartresize(function(){
                    setContentHeight();
                });

                setContentHeight();

                // fixed sidebar
                if ($.fn.mCustomScrollbar) {
                    $('.menu_fixed').mCustomScrollbar({
                        autoHideScrollbar: true,
                        theme: 'minimal',
                        mouseWheel:{ preventDefault: true }
                    });
                }
            });

        }, 300);

        //jQuery(document).ready(function($) {
        //    $('.counter').counterUp({
        //        delay: 10,
        //        time: 1000
        //    });
        //});

        init_daterangepicker('#master_range');
        init_daterangepicker('#reportrange');
        init_daterangepicker('#reportrange_page_views');
        init_daterangepicker('#reportrange_effective_revenue');
        init_daterangepicker('#reportrange_site_stats');
        init_daterangepicker('#reportrange_earned_revenue');
        init_daterangepicker('#reportrange_ad_performance');

    }

})();