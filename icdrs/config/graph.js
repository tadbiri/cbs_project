/**
     * Convert datetime to timestamp.
     * 
     * @param {string} dateTime eg: '2020-01-10 00:07:00'
     * 
     * @return {number} eg: 1578602220000
     * 
     */
    function dateTimeToTimestamp(dateTime) {
        return Date.parse(dateTime);
    }
    
    /**
    * Convert timestamp to dateTIme.
    * 
    * @param {number} timestamp eg: 1578602220000
    * 
    * @return {string} eg: '2020-01-10 00:07:00'
    */
    function timestampToDatetime(timestamp) {
       let date = new Date(timestamp);
       let _year = date.getFullYear();
       _year = _year.toString().padStart(4, 0);
       let _month = date.getMonth() + 1;
       _month = _month.toString().padStart(2, 0);
       let _day = date.getDate();
       _day = _day.toString().padStart(2, 0);
       let _hour = date.getHours();
       _hour = _hour.toString().padStart(2, 0);
       let _minute = date.getMinutes();
       _minute = _minute.toString().padStart(2, 0);
       let _second = date.getSeconds();
       _second = _second.toString().padStart(2, 0);
       return `${_year}-${_month}-${_day} ${_hour}:${_minute}:${_second}`;
    }
    /**
       * Show error for chart.
       */
    showRuntimeError = function (layoutRender, message) {
        $(`div[id='${layoutRender.chartName}-chart-runtime-message']`).show();
        $(`div[id='${layoutRender.chartName}-chart-runtime-message']`).find('span').eq(0).html(message);
    }
    
    /**
     * Show duration time for chart.
     */
    showDuration = function (layoutRender, message) {
        $(`div[id='${layoutRender.chartName}-chart-duration']`).removeClass('hide');
        $(`div[id='${layoutRender.chartName}-chart-duration']`).find('span').eq(0).html(message);
    }
    
    /**
     * Show next time to update for chart.
     */
    showNextTimeUpdate = function (layoutRender) {
        $(`div[id='${layoutRender.chartName}-chart-next-update']`).find('span').eq(0).attr('data-second', layoutRender.chartAverageType.timerLayountIntervalPerSecond);
        
        // Check timer is off.
        var timeString = 'Timer is TurnOff';
        if(layoutRender.chartAverageType.timerLayountIntervalPerSecond != 0){
            var date = new Date(0);
            date.setSeconds(layoutRender.chartAverageType.timerLayountIntervalPerSecond);
            timeString = date.toISOString().substr(11, 8);
        }
    
        $(`div[id='${layoutRender.chartName}-chart-next-update']`).find('span').eq(0).html(timeString);
    }
    
    /**
     * Get layoutRender and restart again chart.
     */
    restartTimerTick = function (layoutRender, api_url) {
        let _chartAverageType = layoutRender.chartAverageType;
    
        // Stop timer if it's started.
        if (layoutRender.timerHandler != null) {
            clearInterval(layoutRender.timerHandler);
            layoutRender.timerHandler = null;
        }
    
        // Calc again interval and run again timer.
        let _interval = _chartAverageType.timerLayountIntervalPerSecond * 1000;
        // A tick to run.
        tick(layoutRender, api_url);
        /**
         * Run timer.
         * Check for that in selected averageType timer is off of not.
         */
        if(_interval != 0){
            layoutRender.timerHandler = setInterval(() => { tick(layoutRender, api_url) }, _interval);
        }
    }
    
    /**
     * Get a layoutRender object by ChartName.
     */
    getLayoutRenderByNames = function (chartName) {
        for (layoutRender of layoutRenders) {
            if (layoutRender.chartName == chartName) {
                return layoutRender;
            }
        }
        return null;
    }
    
    /**
     * Stop timer and set flag off.
     */
    tickOff = function (layoutRender) {
        layoutRender.status = false;
        clearInterval(layoutRender.timerHandler);
        layoutRender.timerHandler = null;
        $(`div[id='${layoutRender.chartName}-chart-next-update']`).find('span').eq(0).html('NaN');
        $(`div[id='${layoutRender.chartName}-chart-duration']`).find('span').eq(0).html('NaN');
        //layoutRender.chartObject.clear();
    }
    
    
    
    let tick = function (layoutRender, apiUrl) {
        // Detect state, chart type is chagned for fetch?
        let oldType = layoutRender.chartAverageTypePrevious.name
        let newType = layoutRender.chartAverageType.name;
    
        layoutRender.chartAverageTypePrevious = layoutRender.chartAverageType;
        let isChangedType = false;
        if(newType != oldType || layoutRender.chartObject == null){
            isChangedType = true;
            // Get current chart-item.
            let _seq = $(`.chart-item[data-chart-name='${layoutRender.chartName}']`).attr('data-seq');
            let _prefixId = $(`.chart-item[data-chart-name='${layoutRender.chartName}']`).attr('data-prefix-id');
            let _currentGraphTagId = `${_prefixId}${_seq}`;
            // Remove current item.
            $(`#${_currentGraphTagId}`).remove();
            // Calc next graph tag id.
            _seq = parseInt(_seq);
            _seq++;
            let _newGraphTagId = `${_prefixId}${_seq}`;
    
            // Add new item.
            $(`.chart-holder[data-chart-name='${layoutRender.chartName}']`).html(`
              <div class='chart-style-div chart-item chart-item-${layoutRender.chartName}' data-chart-name='${layoutRender.chartName}' 
                     data-prefix-id='${_prefixId}' data-seq='${_seq}'
                     id='${_newGraphTagId}'>
              <div>`);
            layoutRender.graphTagId = _newGraphTagId;
        }
    
        // Show next fetch time.
        showNextTimeUpdate(layoutRender);
    
        // Ajax call.
        $.ajax({
            url: apiUrl,
            data: {
                type: layoutRender.chartAverageType.name,
                chart: layoutRender.pureChartName
            },
            cache: false,
            method: "GET",
            async: true,
            success: function (response) {
                // If state that not any data to show. jsut show this error.
                if (response.dataset == null) {
                    showRuntimeError(layoutRender, 'No any cache available for it.');
                    tickOff(layoutRender);
                    return;
                }
    
                // No any problem, enable this flag.
                $(`div[id='${layoutRender.chartName}-chart-runtime-message']`).hide();
                layoutRender.status = true;
    
                // Show time duration for chart.
                showDuration(layoutRender, `${response.dataset.xAxis.startDateTime} to ${response.dataset.xAxis.endDateTime}`);
    
                // Dispose previous chart.
                if(isChangedType){
                    if (layoutRender.chartObject != null) {
                        layoutRender.chartObject.chart.dispose();
                        layoutRender.chartObject = null;
                    }
                    layoutRender.chartObject = new GraphBuilder(layoutRender, response.dataset);
                }else{
                    // Update chart.
                    layoutRender.chartObject.updateGraph(layoutRender, response.dataset);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                // Detect network failure.
                showRuntimeError(layoutRender, JSON.stringify(XMLHttpRequest.responseJSON));
                tickOff(layoutRender);
            }
        });
    }
    
    /**
     * Update next update show time for all chart.
     * Just update active items.
     */
    let TimerUpdater = setInterval(() => {
        for (layoutRender of layoutRenders) {
            // In case chart is not active or referesh timer is off do not update next update. 
            if (!layoutRender.status | layoutRender.chartAverageType.timerLayountIntervalPerSecond == 0) {
                continue;
            }
            let _second = $(`div[id='${layoutRender.chartName}-chart-next-update']`).find('span').eq(0).attr('data-second');
            _second = parseInt(_second);
            _second--;
    
            var date = new Date(1970, 0, 1, 0, 0, 0);
            date.setSeconds(_second);
            let _timeFormat = date.toString().split(' ')[4];
            $(`div[id='${layoutRender.chartName}-chart-next-update']`).find('span').eq(0).attr('data-second', _second);
            $(`div[id='${layoutRender.chartName}-chart-next-update']`).find('span').eq(0).html(_timeFormat);
        }
    }, 1000);
    
    class GraphBuilder {
        /**
         * Generate a new graph.
         * 
         * @param {Object} layoutRender type of dataset.
         * @param {Array} dataset an object for dataset.
         */
        constructor(layoutRender, dataset) {
            let _setting = {
                /**
                 * Two bellow keys used in layout file.
                 * Do not used in graph.js file so it's can be ignored in here.
                 */
                title: null,
                showTitle: true,
                /**
                 * Load this keys from chart API configure.
                 */
                heightPerPixel: layoutRender.chartBoxHeightPerPixel,
                legendPerPixel: layoutRender.chartLengendHeightPerPixel,
    
                /**
                 * This is all configs that be needed in graph.js methods.
                 * All values of it is default value.
                 */
                descriptionPlace: DescriptionPlace.IN_TOOLTIP,
                showYAxisNumber: true,
                showLegend: true,
                showInformationSection: true, 
                showOptionSection: true,
                textColorOfChart: 0x838383,
                entityToShow: [],
                /**
                 * It's a key that exist for detect default config.
                 * Do not matter it's value what is that.
                 * Just existing of it is Enough.
                 */
                defaultConfig: null,
    
            };
    
            // Check that chartSetting configuration variable is defined or not?
            // In case that is not define at all, just follow default configuration.
            if(!(typeof chartSetting === 'undefined')){
                /**
                 * The `chartSetting` configuration variable is exist.
                 * 
                 * Check for specific config for chart.
                 * In case that not any config found for chart, just follow default config for it.
                 */
                if(chartSetting.hasOwnProperty(layoutRender.chartName)){
                    // A config found for chart.
                    _setting = chartSetting[layoutRender.chartName];
                }
            }

            this.layoutRender = layoutRender;
            this.xAxisResponse = dataset.xAxis;
            // Create this.root element.
            this.root = am5.Root.new(layoutRender.graphTagId);
            
            // Set background color.
            var myTheme = am5.Theme.new(this.root);
            myTheme.rule("Label").setAll({
                fill: am5.color(_setting.textColorOfChart),
            });

            // Set themes 
            this.root.setThemes([
                am5themes_Animated.new(this.root),
                myTheme
            ]);
            

            // Create chart
            this.chart = this.root.container.children.push(am5xy.XYChart.new(this.root, {
                panX: true,
                panY: false,
                wheelX: "panX",
                wheelY: "zoomX",
                maxTooltipDistance: 0,
                pinchZoomX: true,
                height: _setting.heightPerPixel,
            }));
            $(`.chart-item-${layoutRender.chartName}`).css('height', `${_setting.heightPerPixel+_setting.legendPerPixel}px`);

            if(_setting.showInformationSection){
                $(`.information-section-${layoutRender.chartName}`).show();
            }else{
                $(`.information-section-${layoutRender.chartName}`).hide();
            }
    
            if(_setting.showOptionSection){
                $(`.option-section-${layoutRender.chartName}`).show();
            }else{
                $(`.option-section-${layoutRender.chartName}`).hide();
            }
    
            /**
             * Create xAxis for chart.
             *
             * At the last line of this section, config for format dateTime 
             * In bellow of chart changed to show month as a number. 
             */
            this.xAxis = this.chart.xAxes.push(am5xy.DateAxis.new(this.root, {
                maxDeviation: 0,
                baseInterval: {
                    timeUnit: 'minute',
                    count: 1
                },
                renderer: am5xy.AxisRendererX.new(this.root, {}),
                tooltip: am5.Tooltip.new(this.root, {}),
                groupData: true
            }));
            this.xAxis.get("dateFormats")["day"] = "MM/dd";
    
            // Configure yAxis for chart.
            this.yAxis = this.chart.yAxes.push(am5xy.ValueAxis.new(this.root, {
                renderer: am5xy.AxisRendererY.new(this.root, {}),
                opacity: (_setting.showYAxisNumber)?1:0,
            }));
            
            this.xAxis.get("renderer").grid.template.setAll({
                strokeWidth: 1,
                visible:true,
                stroke: am5.color(0x000000),
            });
            this.yAxis.get("renderer").grid.template.setAll({
                strokeWidth: 1,
                visible:true,   
                stroke: am5.color(0x000000),
            });

            /**
             * Every data entity in 'amchart' libaray define as `series`.
             * After initial an entity push it to a list to use it
             * For next update and etc.
             */
            this.seriesList = new Array();
            for (let entity of dataset.entities) {
                 /**
                 * Check that current chart follow from default config or not?
                 */
                if(!_setting.hasOwnProperty('defaultConfig')){
                    /**
                     * It's not a default config. it's must be defined in layout file js section.
                     * 
                     * Ignore in entity that not set in  entityToShow array of chart setting.
                     */
                    if(!_setting.entityToShow.includes(entity.label)){
                        continue;
                    }
                }
                // Entity name with description or not?
                let _entityName = entity.label;
                let _entityNamePure = _entityName; 
                if(_setting.descriptionPlace == DescriptionPlace.IN_LEGEND){
                    _entityName = `${entity.label}: ${entity.description}`;
                }

                // Entity tooltip with description or not?
                let _entityDescription = '';
                if(_setting.descriptionPlace == DescriptionPlace.IN_TOOLTIP){
                    _entityDescription = ` => ${entity.description}`;
                }   
                let _connect = null;
                if(this.layoutRender.isAnalytic){
                    _connect = false;
                }else{
                    if(this.layoutRender.graphType == 'continued'){
                        _connect = true;        
                    }else{
                        _connect = false;
                    }
                }
                let _series = this.chart.series.push(am5xy.LineSeries.new(this.root, {
                    name: _entityName,
                    namePure: _entityNamePure,
                    description: _entityDescription,
                    xAxis: this.xAxis,
                    yAxis: this.yAxis,
                    valueYField: "value",
                    valueYGrouped: "low",
                    valueXField: "date",
                    legendValueText: "{valueY}",
                    tooltip: am5.Tooltip.new(this.root, {
                        pointerOrientation: "horizontal",
                        labelText: "{namePure}: ({valueY}){description}"
                    }),
                    // Implement grap.
                    connect: _connect,
                    // Implement color.
                    fill: am5.color(parseInt(entity.colorCode, 16)),
                    stroke: am5.color(parseInt(entity.colorCode, 16)),
                }));
                _series.strokes.template.setAll({
                    strokeWidth: 2,
                });
                _series.fills.template.setAll({
                    fillOpacity: 0.2,
                    visible: true
                });
    
                /**
                 * Dataset that receive from server must not related structure 
                 * For 'machart' lib, to use dataset for chart, dataset must be transform
                 * To a related structure.
                 */
                let _data = this.transformToGraphFormal(entity.data, dataset.xAxis);
                _series.data.setAll(_data);
    
                // Make stuff animate on load
                _series.appear();
                this.seriesList.push(_series);
            }
    
            // Add cursor
            var cursor = this.chart.set("cursor", am5xy.XYCursor.new(this.root, {
                behavior: "none"
            }));
            cursor.lineY.set("visible", false);
    
           /* // Add scrollbar
            this.chart.set("scrollbarX", am5.Scrollbar.new(this.root, {
                orientation: "horizontal"
            }));
            
            this.chart.set("scrollbarY", am5.Scrollbar.new(this.root, {
                orientation: "vertical"
            }));
            */
            // Add legend
            if(_setting.showLegend){
                this.legend = this.chart.children.push(am5.Legend.new(this.root, {
                    centerX: 0,
                    y: am5.percent(100),
                    height: _setting.legendPerPixel,
                }));
            }else{
               this.legend = this.chart.children.push(am5.Legend.new(this.root, {opacity:0,}));
            }
    
            // When legend item container is hovered, dim all the series except the hovered one.
            this.legend.itemContainers.template.events.on("pointerover", function (e) {
    
                // Detect current series of current chart.
                let series = e.target.dataItem.dataContext;
    
                let chartName  = e.target._root.dom.id.split('_')[0];
                let seriesList = layoutRenders[layoutRendersDict[chartName]].chartObject.seriesList;
    
                for (let _series of seriesList) {
                    if (series != _series) {
                        _series.strokes.template.setAll({
                            strokeOpacity: 0.15,
                            stroke: am5.color(0x000000)
                        });
                    } else {
                        _series.strokes.template.setAll({
                            strokeWidth: 2
                        });
                    }
                }
            })
    
            // When legend item container is unhovered, make all series as they are
            this.legend.itemContainers.template.events.on("pointerout", function (e) {
                let chartName  = e.target._root.dom.id.split('_')[0];
                let seriesList = layoutRenders[layoutRendersDict[chartName]].chartObject.seriesList;
                for (let series of seriesList) {
                    series.strokes.template.setAll({
                        strokeOpacity: 2,
                        strokeWidth: 2,
                        stroke: series.get("fill")
                    });
                }
            })
    
            // It's is important to set legend data after all the events are set on template, otherwise events won't be copied
            this.legend.data.setAll(this.chart.series.values);
    
            // Make stuff animate on load
            this.chart.appear(1000, 100);
        }
    
        transformToGraphFormal(data, xAxis) {
            /**
             * To make realated dataset for each entity,
             * Start date from first.
             */
            this.date = new Date(xAxis.startDateTime);
    
            // Hold final result.
            let _result = new Array();
    
            // Calc period for chart and add data,
            let _startTimestamp = dateTimeToTimestamp(xAxis.startDateTime);
            let _endTimestamp = dateTimeToTimestamp(xAxis.endDateTime);
            // Iterate for each point of timestamp.
            for (let i = 0; _startTimestamp <= _endTimestamp; _startTimestamp += 60000, i++) {
                // Get current point date.
                let _date = this.date.getTime();
    
                // Increase date one step for next point.
                am5.time.add(this.date, "minute", 1);
    
                // To impelement Gap.
                if (data[i] == 'NaN' || isNaN(data[i])) {
                    if(this.layoutRender.graphType == 'continued'){
                        _result.push({
                            date: _date,
                            value: 0
                        });
                    }else{
                        // Gap.
                        _result.push({
                            date: _date
                        });
                    }
                } else {
                    _result.push({
                        date: _date,
                        value: data[i]
                    });
                }
            }
            return _result;
        }
    
        updateGraph(layoutRender, dataset){;
            /**
             * Calculate fresh result item count to import to series.
             */
            let graphxAxis = layoutRender.chartObject.xAxisResponse;
            let serverxAxis = dataset.xAxis;
            let graphEndTimestamp = dateTimeToTimestamp(graphxAxis.endDateTime);
            let serverEndTimestamp = dateTimeToTimestamp(serverxAxis.endDateTime);
            let freshResultsCount = ((serverEndTimestamp-graphEndTimestamp)/1000)/60;
            
            //console.log('freshResultsCount:', freshResultsCount);

            /**
             * Update last points.
             */
            // Get point count to update.
            let refreshLastPointBasedMinute = dataset.xAxis.refreshLastPointBasedMinute;
    
            // Iterate on series, entity, graph-item or anything that you called :) 
            for (let ent of dataset.entities.entries()) {
                // Ignore in entity that not set in  entityToShow array of chart setting.
                let entity = ent[1]; 
                let seriesIndex = -1;
                for(let seriesI=0; seriesI<this.seriesList.length; seriesI++){
                    if(this.seriesList[seriesI]._settings.name == entity.label){
                        seriesIndex = seriesI;
                        break;
                    }
                }
                if(seriesIndex == -1){
                    continue;
                }
                let series = this.seriesList[seriesIndex];
    
                // Remove last specificted points by this loop. 
                for(let i=0; i<refreshLastPointBasedMinute;i++){
                    let pointCount = this.seriesList[seriesIndex].data.length;
                    let x = this.seriesList[seriesIndex].data.removeIndex(pointCount-1);
                    //console.log(`R${timestampToDatetime(x.date)}-> ${x.value} | ${seriesIndex}`);
                }  
                // Get timestamp of last point after remove action.
                let timestamp_point = series.data._values[series.data._values.length - 1].date;
                // Get last specificted points data and set them.
    
                let lastPoints = entity.data.slice(0, entity.data.length-freshResultsCount);
                lastPoints = lastPoints.slice(refreshLastPointBasedMinute*-1);
                for(let i=0; i<refreshLastPointBasedMinute;i++){
                    timestamp_point += 60000;
                    //console.log(`A${timestampToDatetime(timestamp_point)}-> ${lastPoints[i]} | ${seriesIndex}`);
                    // To impelement Gap.
                    if (lastPoints[i] == 'NaN' || isNaN(lastPoints[i])) {
                        if(this.layoutRender.graphType == 'continued'){
                            this.seriesList[seriesIndex].data.push({
                                date: timestamp_point,
                                value: 0
                            });
                        }else{
                            // Gap.
                            this.seriesList[seriesIndex].data.push({
                                date: timestamp_point
                            });
                        }
                    } else {
                        this.seriesList[seriesIndex].data.push({
                            date: timestamp_point,
                            value: lastPoints[i]
                        });
                    }
                }
                //console.log('update');
                }
                if('FailedChart' == layoutRender.chartName){
                    //console.log(`freshResultsCount: ${freshResultsCount}`);
            }
            
            // In case that not any fresh data found, ignore update operation.
            if(freshResultsCount == 0){
                return;
            }
            // Update chart period time with chart period time that received from new data.
            layoutRender.chartObject.xAxisResponse = dataset.xAxis;
            // Iterate on series, entity, graph-item or anything that you called :) 
            for (let ent of dataset.entities.entries()) {
                // Ignore in entity that not set in  entityToShow array of chart setting.
                let entity = ent[1]; 
                let seriesIndex = -1;
                for(let seriesI=0; seriesI<this.seriesList.length; seriesI++){
                    if(this.seriesList[seriesI]._settings.name == entity.label){
                        seriesIndex = seriesI;
                        break;
                    }
                }
                if(seriesIndex == -1){
                    continue;
                }
                // Fetch fresh result pure data.
                let series = this.seriesList[seriesIndex];
                let freshResults = entity.data.slice(freshResultsCount*-1);
                for(let freshResult of freshResults){
                    let data = freshResult;
                    let timestamp_point = series.data._values[series.data._values.length - 1].date;
                    timestamp_point += 60000;
    
                    // To impelement Gap.
                    if (data == 'NaN' || isNaN(data)) {
                        if(this.layoutRender.graphType == 'continued'){
                            this.seriesList[seriesIndex].data.push({
                                date: timestamp_point,
                                value: 0
                            });
                        }else{
                            // Gap.
                            this.seriesList[seriesIndex].data.push({
                                date: timestamp_point
                            });
                        }
                    } else {
                        this.seriesList[seriesIndex].data.push({
                            date: timestamp_point,
                            value: data
                        });
                    }
                }
            // For remove first items from graph.
            let chartPointCount = series.data._values.length;
            let serverPointCount = serverxAxis.pointCount;
            let pointCountToRemove = chartPointCount-serverPointCount;
            for(let i=0;i<pointCountToRemove;i++){
                this.seriesList[seriesIndex].data.shift();
            }
            }
        
        }
    }