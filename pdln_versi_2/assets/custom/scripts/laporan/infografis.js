var ChartsAmcharts = function() {
	
    var initChartSample1 = function() {
		
		var chart1_Data;
		
		$.ajax({
            url : BASE_URL+"laporan/laporan/get_penggunaan_apbn",
            type: "POST",
			async: false,
            dataType: 'JSON',
            success: function(data) 
            { 
                chart1_Data = data;       
            },
        });		
        var chart = AmCharts.makeChart("chart_1", {
            "type": "pie",
            "dataProvider": chart1_Data,   
            "valueField": "biaya",
            "titleField": "lembaga",
            "startDuration": 0,
            "theme": "light",
            "addClassNames": true,            
            "legend":{
                "position":"bottom",
                "align":"left",                
                "markerType" : "square",
                "autoMargins":true,
				"valueWidth": 150, 
				"valueText": " Rp. [[value]] .-",
            },
            "innerRadius": "30%",
            "defs": {
                "filter": [{
                    "id": "shadow",
                    "width": "200%",
                    "height": "200%",
                    "feOffset": {
                        "result": "offOut",
                        "in": "SourceAlpha",
                        "dx": 0,
                        "dy": 0
                    },
                    "feGaussianBlur": {
                        "result": "blurOut",
                        "in": "offOut",
                        "stdDeviation": 5
                    },
                    "feBlend": {
                        "in": "SourceGraphic",
                        "in2": "blurOut",
                        "mode": "normal"
                    }
                }]
            },
            "balloonText": "[[title]]<br><span style='font-size:14px'>Rp. <b>[[value]]</b> .- ([[percents]]%)</span>",
            // "angle": 30,
            "export": {
                "enabled": true
            }
        });
        $('#chart_1').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
            chart.addListener("init", handleInit);

            chart.addListener("rollOverSlice", function(e) {
              handleRollOver(e);
            });
        });
        function handleInit(){
          chart.legend.addListener("rollOverItem", handleRollOver);
        }

        function handleRollOver(e){
          var wedge = e.dataItem.wedge.node;
          wedge.parentNode.appendChild(wedge);
        }
    };
    var initChartSample2 = function() {
        
		var chart2_Data;
		
		$.ajax({
            url : BASE_URL+"laporan/laporan/get_jumlah_peserta",
            type: "POST",
			async: false,
            dataType: 'JSON',
            success: function(data) 
            { 
                chart2_Data = data;       
            },
        });
		
		var chart = AmCharts.makeChart("chart_2", {
            "type": "pie",
            "dataProvider": chart2_Data,   
            "valueField": "jumlah",
            "titleField": "lembaga",
            "startDuration": 0,
            "theme": "light",
            "addClassNames": true,            
            "legend":{
                "position":"bottom",
                "align":"left",                
                "markerType" : "square",
                "autoMargins":true,
				"valueWidth": 150
            },
            "innerRadius": "30%",
            "defs": {
                "filter": [{
                    "id": "shadow",
                    "width": "200%",
                    "height": "200%",
                    "feOffset": {
                        "result": "offOut",
                        "in": "SourceAlpha",
                        "dx": 0,
                        "dy": 0
                    },
                    "feGaussianBlur": {
                        "result": "blurOut",
                        "in": "offOut",
                        "stdDeviation": 5
                    },
                    "feBlend": {
                        "in": "SourceGraphic",
                        "in2": "blurOut",
                        "mode": "normal"
                    }
                }]
            },
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
            // "angle": 30,
            "export": {
                "enabled": true
            }
        });
        $('#chart_2').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
            chart.addListener("init", handleInit);

            chart.addListener("rollOverSlice", function(e) {
              handleRollOver(e);
            });
        });
        function handleInit(){
          chart.legend.addListener("rollOverItem", handleRollOver);
        }

        function handleRollOver(e){
          var wedge = e.dataItem.wedge.node;
          wedge.parentNode.appendChild(wedge);
        }
	};
    var initChartSample3 = function() {
		
		var chart3_Data;
		
		$.ajax({
            url : BASE_URL+"laporan/laporan/get_jumlah_sp",
            type: "POST",
			async: false,
            dataType: 'JSON',
            success: function(data) 
            { 
                chart3_Data = data;       
            },
        });
		
        var chart = AmCharts.makeChart("chart_3", {
            "type": "pie",
            "dataProvider": chart3_Data,   
            "valueField": "jumlah",
            "titleField": "lembaga",
            "startDuration": 0,
            "theme": "light",
            "addClassNames": true,            
            "legend":{
                "position":"bottom",
                "align":"left",                
                "markerType" : "square",
                "autoMargins":true,
				"valueWidth": 200
            },
            "innerRadius": "30%",
            "defs": {
                "filter": [{
                    "id": "shadow",
                    "width": "200%",
                    "height": "200%",
                    "feOffset": {
                        "result": "offOut",
                        "in": "SourceAlpha",
                        "dx": 0,
                        "dy": 0
                    },
                    "feGaussianBlur": {
                        "result": "blurOut",
                        "in": "offOut",
                        "stdDeviation": 5
                    },
                    "feBlend": {
                        "in": "SourceGraphic",
                        "in2": "blurOut",
                        "mode": "normal"
                    }
                }]
            },
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
            // "angle": 30,
            "export": {
                "enabled": true
            }
        });
        $('#chart_3').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
            chart.addListener("init", handleInit);

            chart.addListener("rollOverSlice", function(e) {
              handleRollOver(e); 
            });
        });
        function handleInit(){
          chart.legend.addListener("rollOverItem", handleRollOver);
        }

        function handleRollOver(e){
          var wedge = e.dataItem.wedge.node;
          wedge.parentNode.appendChild(wedge);
        }
    };
    var initChartSample4 = function() {
		
		var chart4_Data; 
		
		$.ajax({
            url : BASE_URL+"laporan/laporan/get_kunjungan_negara",
            type: "POST",
			async: false,
            dataType: 'JSON',
            success: function(data) 
            { 
                chart4_Data = data;       
            },
        });
		
        var chart = AmCharts.makeChart("chart_4", {
            "type": "pie",
            "dataProvider": chart4_Data,   
            "valueField": "jumlah",
            "titleField": "country",
            "startDuration": 0,
            "theme": "light",
            "addClassNames": true,            
            "legend":{
                "position":"bottom",
                "align":"left",                
                "markerType" : "square",
                "autoMargins":true,
				"valueWidth": 500
            },
            "innerRadius": "30%",
            "defs": {
                "filter": [{
                    "id": "shadow",
                    "width": "200%",
                    "height": "200%",
                    "feOffset": {
                        "result": "offOut",
                        "in": "SourceAlpha",
                        "dx": 0,
                        "dy": 0
                    },
                    "feGaussianBlur": {
                        "result": "blurOut",
                        "in": "offOut",
                        "stdDeviation": 5
                    },
                    "feBlend": {
                        "in": "SourceGraphic",
                        "in2": "blurOut",
                        "mode": "normal"
                    }
                }]
            },
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
            // "angle": 30,
            "export": {
                "enabled": true
            }
        });
        $('#chart_4').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
            chart.addListener("init", handleInit);

            chart.addListener("rollOverSlice", function(e) {
              handleRollOver(e); 
            });
        });
        function handleInit(){
          chart.legend.addListener("rollOverItem", handleRollOver);
        }

        function handleRollOver(e){
          var wedge = e.dataItem.wedge.node;
          wedge.parentNode.appendChild(wedge);
        }    
    };
    var initChartSample5 = function() {
		
		var chart5_Data; 
		
		$.ajax({
            url : BASE_URL+"laporan/laporan/get_jenis_penugasan",
            type: "POST",
			async: false,
            dataType: 'JSON',
            success: function(data) 
            { 
                chart5_Data = data;       
            },
        });
		
        var chart = AmCharts.makeChart("chart_5", {
            "type": "pie",
            "dataProvider": chart5_Data,   
            "valueField": "jumlah",
            "titleField": "jenis_tugas",
            "startDuration": 0,
            "theme": "light",
            "addClassNames": true,            
            "legend":{
                "position":"bottom",
                "align":"left",                
                "markerType" : "square",
                "autoMargins":true,
				"valueWidth": 500 
            },
            "innerRadius": "30%",
            "defs": {
                "filter": [{
                    "id": "shadow",
                    "width": "200%",
                    "height": "200%",
                    "feOffset": {
                        "result": "offOut",
                        "in": "SourceAlpha",
                        "dx": 0,
                        "dy": 0
                    },
                    "feGaussianBlur": {
                        "result": "blurOut",
                        "in": "offOut",
                        "stdDeviation": 5
                    },
                    "feBlend": {
                        "in": "SourceGraphic",
                        "in2": "blurOut",
                        "mode": "normal"
                    }
                }]
            },
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
            // "angle": 30,
            "export": {
                "enabled": true
            }
        });
        $('#chart_5').closest('.portlet').find('.fullscreen').click(function() {
            chart.invalidateSize();
            chart.addListener("init", handleInit);

            chart.addListener("rollOverSlice", function(e) {
              handleRollOver(e); 
            });
        });
        function handleInit(){
          chart.legend.addListener("rollOverItem", handleRollOver);
        }

        function handleRollOver(e){
          var wedge = e.dataItem.wedge.node;
          wedge.parentNode.appendChild(wedge);
        }    
    };
    return {
        init: function() {
            initChartSample1();
            initChartSample2();
            initChartSample3();
            initChartSample4();
            initChartSample5();
        }

    };
 
}();
jQuery(document).ready(function() {    
   ChartsAmcharts.init(); 
});