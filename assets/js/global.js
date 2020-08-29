	// success message
	var add_success = "Data berhasil di simpan.";
	var update_success = "Data berhasil di update.";
	var delete_success = "Data berhasil di hapus.";
	
	// warning message buat validateJs
	var null_warning = " harus di isi.";
	
	function set_warning(title){
		msg = title + this.null_warning;
		return msg;
		
	}
	
	/* // timer
	var myVar=setInterval(function(){myTimer()},1000);
	function myTimer() {
		var d=new Date();
		var monthname=new Array("Jan","Feb","Mar","Apr","Mei","Jun","Jul","Ags", "Sep","Oct","Nov","Des");
				
		var t=' '+d.getDate()+ ' ' + monthname[d.getMonth()] + ' ' +d.getFullYear()+' | ' + d.toLocaleTimeString();
		document.getElementById("date_time").innerHTML=t;
	} 
	 */
	
    