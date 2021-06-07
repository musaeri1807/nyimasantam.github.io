function seribu(ongkos) {
    var titik = '.';
    var nilai = new String(ongkos); 
    var pecah = []; 
    while(nilai.length > 3) 
    { 
        var asd = nilai.substr(nilai.length-3); 
        pecah.unshift(asd); 
        nilai = nilai.substr(0, nilai.length-3); 
    } 

    if(nilai.length > 0) { pecah.unshift(nilai); } 
    nilai = pecah.join(titik);
    return nilai;  
}