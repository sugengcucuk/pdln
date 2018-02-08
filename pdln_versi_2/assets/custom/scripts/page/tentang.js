var Contact = function () {

    return {
        //main function to initiate the module
        init: function () {
			var map;
			$(document).ready(function(){
			  map = new GMaps({
				div: '#gmapbg',
				lat: -6.168961,
				lng: 106.825991
			  });
			   var marker = map.addMarker({
					lat: -6.168961,
					lng: 106.825991,
		            title: 'Biro KTLN Sekretariat Negara',
		            infoWindow: {
		                content: "<b>Biro Kerja Sama Teknik Luar Negeri</b><br>Sekretaris Negara Republik Indonesia"
		            }
		        });

			   marker.infoWindow.open(map, marker);  
			});
        }
    };

}();

jQuery(document).ready(function() {    
   Contact.init(); 
});

