<?php

/*
 * Cobiss API :-)
 *
 * Options:
 * 		?q=Gospodar Prstanov Bratovščina prstana
 *		?q=86-11-17209-4
 * 		?format=json (default: html) (actually format=html means html, anything else means json)
 *
 * TO-DO:
 *		- Parsanje exact match strani
 */

include 'Cobiss.php';

header('Content-Type: text/html; charset=utf-8');

$format = isset($_GET['format']) ? $_GET['format'] : 'html';
if (!isset($_GET['q'])) die($format == 'html' ? '' : []);
$query = $_GET['q'];

// /*
$c = new Cobiss('Ptuj');
$response = $c->search($query);
/* Test cases:
 *		"Gospodar prstanov Bratovščina prstana"
 * 		"Pika Nogavička"
 *		"Harry Potter"
 *		"Wikileaks od znotraj" (exact match)
 */

//echo json_encode($results, JSON_UNESCAPED_UNICODE);
// */


// 
/* Offline testing mode
$results = json_decode('
	["<tr>\r\n                <td class=\"small\" valign=\"top\"><input type=\"checkbox\" name=\"ch1\" value=\"1\" class=\"tblchckbx1\" \/><\/td>\r\n                <td class=\"small\" valign=\"top\">1.<\/td>\r\n                <td class=\"small\" valign=\"top\"><img src=\"..\/opac\/img\/rank3.gif\" class=\"rank\" alt=\"ranking\" \/><\/td>\r\n                <td class=\"small vecje\" valign=\"top\">Rowling, J. K.<\/td>\r\n                <td class=\"small vecje\" valign=\"top\"><a href=\"http:\/\/cobiss6.izum.si\/scripts\/cobiss?ukaz=DISP&amp;id=1348336139279200&amp;rec=1&amp;sid=1\">Harry Potter. [2], Dvorana skrivnosti<\/a><\/td>\r\n                <td class=\"small ico\" valign=\"top\"><img src=\"..\/opac\/img\/ic_knjiga.gif\" width=\"16\" height=\"16\" class=\"ico2\" alt=\"type\" \/>(znanstveno)fantasti\u010dna proza<\/td>\r\n                <td class=\"small\" valign=\"top\">slv<\/td>\r\n                <td class=\"small\" valign=\"top\">2000<\/td>\r\n                <td class=\"small ico\" valign=\"top\"><img src=\"..\/opac\/img\/st-green.gif\" width=\"10\" height=\"10\" class=\"ico\" alt=\"availability\" \/>prosto - na dom<\/td>\r\n                <td class=\"small\" valign=\"top\"><a href=\"http:\/\/metaiskalnik.izum.si\/sfxlcl41?url_ver=Z39.88-2004&url_ctx_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Actx&ctx_ver=Z39.88-2004&ctx_enc=info%3Aofi%2Fenc%3AUTF-8&rft_val_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Abook&rft.aulast=Rowling&rft.aufirst=J.%20K.&rft.btitle=Harry%20Potter&rft.isbn=961-6018-85-X&rft.place=Ljubljana&rft.pub=Epta&rft.date=2000&rft.edition=1.%20ponatis&rft.tpages=280&rft.genre=book&rft.sici=110264832&rfr_id=COBISS_OPAC&sfx.ignore_date_threshold=1\" target=\"meta\"><img src=\"..\/opac\/img\/sfx.gif\" width=\"12\" height=\"12\"\r\n alt=\"Povezave do informacijskih servisov\" title=\"Povezave do informacijskih servisov\" class=\"cond2\" \/><\/a>&nbsp;<a href=\"http:\/\/www.worldcat.org\/oclc\/443128733\" target=\"wcat\"><img\r\n src=\"..\/opac\/img\/worldcat2.gif\" width=\"12\" height=\"12\"\r\n alt=\"Pregled zapisa v katalogu WorldCat\" title=\"Pregled zapisa v katalogu WorldCat\" class=\"cond2\" \/><\/a><\/td>\r\n            <\/tr>","<tr>\r\n                <td class=\"small2\" valign=\"top\"><input type=\"checkbox\" name=\"ch2\" value=\"2\" class=\"tblchckbx1\" \/><\/td>\r\n                <td class=\"small2\" valign=\"top\">2.<\/td>\r\n                <td class=\"small2\" valign=\"top\"><img src=\"..\/opac\/img\/rank3.gif\" class=\"rank\" alt=\"ranking\" \/><\/td>\r\n                <td class=\"small2 vecje\" valign=\"top\">Rowling, J. K.<\/td>\r\n                <td class=\"small2 vecje\" valign=\"top\"><a href=\"http:\/\/cobiss6.izum.si\/scripts\/cobiss?ukaz=DISP&amp;id=1348336139279200&amp;rec=2&amp;sid=1\">Harry Potter. [Del 2], Dvorana skrivnosti<\/a><\/td>\r\n                <td class=\"small2 ico\" valign=\"top\"><img src=\"..\/opac\/img\/ic_knjiga.gif\" width=\"16\" height=\"16\" class=\"ico2\" alt=\"type\" \/>roman<\/td>\r\n                <td class=\"small2\" valign=\"top\">slv<\/td>\r\n                <td class=\"small2\" valign=\"top\">2000<\/td>\r\n                <td class=\"small2 ico\" valign=\"top\"><img src=\"..\/opac\/img\/st-green.gif\" width=\"10\" height=\"10\" class=\"ico\" alt=\"availability\" \/>prosto - na dom<\/td>\r\n                <td class=\"small2\" valign=\"top\"><a href=\"http:\/\/metaiskalnik.izum.si\/sfxlcl41?url_ver=Z39.88-2004&url_ctx_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Actx&ctx_ver=Z39.88-2004&ctx_enc=info%3Aofi%2Fenc%3AUTF-8&rft_val_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Abook&rft.aulast=Rowling&rft.aufirst=J.%20K.&rft.btitle=Harry%20Potter&rft.isbn=961-6018-85-X&rft.place=Ljubljana&rft.pub=Epta&rft.date=2000&rft.edition=3.%20ponatis&rft.tpages=280&rft.genre=book&rft.sici=115947520&rfr_id=COBISS_OPAC&sfx.ignore_date_threshold=1\" target=\"meta\"><img src=\"..\/opac\/img\/sfx.gif\" width=\"12\" height=\"12\"\r\n alt=\"Povezave do informacijskih servisov\" title=\"Povezave do informacijskih servisov\" class=\"cond2\" \/><\/a>&nbsp;<a href=\"http:\/\/www.worldcat.org\/oclc\/443128733\" target=\"wcat\"><img\r\n src=\"..\/opac\/img\/worldcat2.gif\" width=\"12\" height=\"12\"\r\n alt=\"Pregled zapisa v katalogu WorldCat\" title=\"Pregled zapisa v katalogu WorldCat\" class=\"cond2\" \/><\/a><\/td>\r\n            <\/tr>","<tr>\r\n                <td class=\"small\" valign=\"top\"><input type=\"checkbox\" name=\"ch3\" value=\"3\" class=\"tblchckbx1\" \/><\/td>\r\n                <td class=\"small\" valign=\"top\">3.<\/td>\r\n                <td class=\"small\" valign=\"top\"><img src=\"..\/opac\/img\/rank3.gif\" class=\"rank\" alt=\"ranking\" \/><\/td>\r\n                <td class=\"small vecje\" valign=\"top\">Rowling, J. K.<\/td>\r\n                <td class=\"small vecje\" valign=\"top\"><a href=\"http:\/\/cobiss6.izum.si\/scripts\/cobiss?ukaz=DISP&amp;id=1348336139279200&amp;rec=3&amp;sid=1\">Harry Potter. [Del 2], Dvorana skrivnosti<\/a><\/td>\r\n                <td class=\"small ico\" valign=\"top\"><img src=\"..\/opac\/img\/ic_knjiga.gif\" width=\"16\" height=\"16\" class=\"ico2\" alt=\"type\" \/>roman<\/td>\r\n                <td class=\"small\" valign=\"top\">slv<\/td>\r\n                <td class=\"small\" valign=\"top\">2003<\/td>\r\n                <td class=\"small ico\" valign=\"top\"><img src=\"..\/opac\/img\/st-green.gif\" width=\"10\" height=\"10\" class=\"ico\" alt=\"availability\" \/>prosto - na dom<\/td>\r\n                <td class=\"small\" valign=\"top\"><a href=\"http:\/\/metaiskalnik.izum.si\/sfxlcl41?url_ver=Z39.88-2004&url_ctx_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Actx&ctx_ver=Z39.88-2004&ctx_enc=info%3Aofi%2Fenc%3AUTF-8&rft_val_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Abook&rft.aulast=Rowling&rft.aufirst=J.%20K.&rft.btitle=Harry%20Potter&rft.isbn=961-6375-28-8&rft.place=Ljubljana&rft.pub=Epta&rft.date=2003&rft.edition=1.%20bro%C5%A1.%20izd.&rft.tpages=280&rft.genre=book&rft.sici=127146752&rfr_id=COBISS_OPAC&sfx.ignore_date_threshold=1\" target=\"meta\"><img src=\"..\/opac\/img\/sfx.gif\" width=\"12\" height=\"12\"\r\n alt=\"Povezave do informacijskih servisov\" title=\"Povezave do informacijskih servisov\" class=\"cond2\" \/><\/a>&nbsp;<a href=\"http:\/\/www.worldcat.org\/oclc\/446775529\" target=\"wcat\"><img\r\n src=\"..\/opac\/img\/worldcat2.gif\" width=\"12\" height=\"12\"\r\n alt=\"Pregled zapisa v katalogu WorldCat\" title=\"Pregled zapisa v katalogu WorldCat\" class=\"cond2\" \/><\/a><\/td>\r\n            <\/tr>","<tr>\r\n                <td class=\"small2\" valign=\"top\"><input type=\"checkbox\" name=\"ch4\" value=\"4\" class=\"tblchckbx1\" \/><\/td>\r\n                <td class=\"small2\" valign=\"top\">4.<\/td>\r\n                <td class=\"small2\" valign=\"top\"><img src=\"..\/opac\/img\/rank3.gif\" class=\"rank\" alt=\"ranking\" \/><\/td>\r\n                <td class=\"small2 vecje\" valign=\"top\">Rowling, J. K.<\/td>\r\n                <td class=\"small2 vecje\" valign=\"top\"><a href=\"http:\/\/cobiss6.izum.si\/scripts\/cobiss?ukaz=DISP&amp;id=1348336139279200&amp;rec=4&amp;sid=1\">Harry Potter. [Del 2], Dvorana skrivnosti<\/a><\/td>\r\n                <td class=\"small2 ico\" valign=\"top\"><img src=\"..\/opac\/img\/ic_knjiga.gif\" width=\"16\" height=\"16\" class=\"ico2\" alt=\"type\" \/>roman<\/td>\r\n                <td class=\"small2\" valign=\"top\">slv<\/td>\r\n                <td class=\"small2\" valign=\"top\">2003<\/td>\r\n                <td class=\"small2 ico\" valign=\"top\"><img src=\"..\/opac\/img\/st-red.gif\" width=\"10\" height=\"10\" class=\"ico\" alt=\"availability\" \/>rezervirano<\/td>\r\n                <td class=\"small2\" valign=\"top\"><a href=\"http:\/\/metaiskalnik.izum.si\/sfxlcl41?url_ver=Z39.88-2004&url_ctx_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Actx&ctx_ver=Z39.88-2004&ctx_enc=info%3Aofi%2Fenc%3AUTF-8&rft_val_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Abook&rft.aulast=Rowling&rft.aufirst=J.%20K.&rft.btitle=Harry%20Potter&rft.isbn=961-6018-85-X&rft.place=Ljubljana&rft.pub=Epta&rft.date=2003&rft.edition=4.%20ponatis&rft.tpages=280&rft.genre=book&rft.sici=127617024&rfr_id=COBISS_OPAC&sfx.ignore_date_threshold=1\" target=\"meta\"><img src=\"..\/opac\/img\/sfx.gif\" width=\"12\" height=\"12\"\r\n alt=\"Povezave do informacijskih servisov\" title=\"Povezave do informacijskih servisov\" class=\"cond2\" \/><\/a>&nbsp;<a href=\"http:\/\/www.worldcat.org\/oclc\/446775529\" target=\"wcat\"><img\r\n src=\"..\/opac\/img\/worldcat2.gif\" width=\"12\" height=\"12\"\r\n alt=\"Pregled zapisa v katalogu WorldCat\" title=\"Pregled zapisa v katalogu WorldCat\" class=\"cond2\" \/><\/a><\/td>\r\n            <\/tr>","<tr>\r\n                <td class=\"small\" valign=\"top\"><input type=\"checkbox\" name=\"ch5\" value=\"5\" class=\"tblchckbx1\" \/><\/td>\r\n                <td class=\"small\" valign=\"top\">5.<\/td>\r\n                <td class=\"small\" valign=\"top\"><img src=\"..\/opac\/img\/rank3.gif\" class=\"rank\" alt=\"ranking\" \/><\/td>\r\n                <td class=\"small vecje\" valign=\"top\">Rowling, J. K.<\/td>\r\n                <td class=\"small vecje\" valign=\"top\"><a href=\"http:\/\/cobiss6.izum.si\/scripts\/cobiss?ukaz=DISP&amp;id=1348336139279200&amp;rec=5&amp;sid=1\">Harry Potter. [Del 2], Dvorana skrivnosti<\/a><\/td>\r\n                <td class=\"small ico\" valign=\"top\"><img src=\"..\/opac\/img\/ic_knjiga.gif\" width=\"16\" height=\"16\" class=\"ico2\" alt=\"type\" \/>(znanstveno)fantasti\u010dna proza<\/td>\r\n                <td class=\"small\" valign=\"top\">slv<\/td>\r\n                <td class=\"small\" valign=\"top\">2000<\/td>\r\n                <td class=\"small ico\" valign=\"top\"><img src=\"..\/opac\/img\/st-green.gif\" width=\"10\" height=\"10\" class=\"ico\" alt=\"availability\" \/>prosto - na dom<\/td>\r\n                <td class=\"small\" valign=\"top\"><a href=\"http:\/\/metaiskalnik.izum.si\/sfxlcl41?url_ver=Z39.88-2004&url_ctx_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Actx&ctx_ver=Z39.88-2004&ctx_enc=info%3Aofi%2Fenc%3AUTF-8&rft_val_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Abook&rft.aulast=Rowling&rft.aufirst=J.%20K.&rft.btitle=Harry%20Potter&rft.isbn=961-6018-85-X&rft.place=Ljubljana&rft.pub=Epta&rft.date=2000&rft.tpages=280&rft.genre=book&rft.sici=107416320&rfr_id=COBISS_OPAC&sfx.ignore_date_threshold=1\" target=\"meta\"><img src=\"..\/opac\/img\/sfx.gif\" width=\"12\" height=\"12\"\r\n alt=\"Povezave do informacijskih servisov\" title=\"Povezave do informacijskih servisov\" class=\"cond2\" \/><\/a>&nbsp;<a href=\"http:\/\/www.worldcat.org\/oclc\/443128733\" target=\"wcat\"><img\r\n src=\"..\/opac\/img\/worldcat2.gif\" width=\"12\" height=\"12\"\r\n alt=\"Pregled zapisa v katalogu WorldCat\" title=\"Pregled zapisa v katalogu WorldCat\" class=\"cond2\" \/><\/a><\/td>\r\n            <\/tr>","<tr>\r\n                <td class=\"small2\" valign=\"top\"><input type=\"checkbox\" name=\"ch6\" value=\"6\" class=\"tblchckbx1\" \/><\/td>\r\n                <td class=\"small2\" valign=\"top\">6.<\/td>\r\n                <td class=\"small2\" valign=\"top\"><img src=\"..\/opac\/img\/rank3.gif\" class=\"rank\" alt=\"ranking\" \/><\/td>\r\n                <td class=\"small2 vecje\" valign=\"top\">Rowling, J. K.<\/td>\r\n                <td class=\"small2 vecje\" valign=\"top\"><a href=\"http:\/\/cobiss6.izum.si\/scripts\/cobiss?ukaz=DISP&amp;id=1348336139279200&amp;rec=6&amp;sid=1\">Harry Potter. [Del 6], Princ me\u0161ane krvi<\/a><\/td>\r\n                <td class=\"small2 ico\" valign=\"top\"><img src=\"..\/opac\/img\/ic_knjiga.gif\" width=\"16\" height=\"16\" class=\"ico2\" alt=\"type\" \/>(znanstveno)fantasti\u010dna proza<\/td>\r\n                <td class=\"small2\" valign=\"top\">slv<\/td>\r\n                <td class=\"small2\" valign=\"top\">2008<\/td>\r\n                <td class=\"small2 ico\" valign=\"top\"><img src=\"..\/opac\/img\/st-green.gif\" width=\"10\" height=\"10\" class=\"ico\" alt=\"availability\" \/>prosto - na dom<\/td>\r\n                <td class=\"small2\" valign=\"top\"><a href=\"http:\/\/metaiskalnik.izum.si\/sfxlcl41?url_ver=Z39.88-2004&url_ctx_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Actx&ctx_ver=Z39.88-2004&ctx_enc=info%3Aofi%2Fenc%3AUTF-8&rft_val_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Abook&rft.aulast=Rowling&rft.aufirst=J.%20K.&rft.btitle=Harry%20Potter&rft.isbn=978-961-01-0700-2&rft.place=Ljubljana&rft.pub=Mladinska%20knjiga&rft.date=2008&rft.edition=1.%20izd.&rft.tpages=498&rft.series=Zbirka%20Harry%20Potter&rft.genre=book&rft.sici=241994752&rfr_id=COBISS_OPAC&sfx.ignore_date_threshold=1\" target=\"meta\"><img src=\"..\/opac\/img\/sfx.gif\" width=\"12\" height=\"12\"\r\n alt=\"Povezave do informacijskih servisov\" title=\"Povezave do informacijskih servisov\" class=\"cond2\" \/><\/a>&nbsp;<a href=\"http:\/\/www.worldcat.org\/oclc\/449566026\" target=\"wcat\"><img\r\n src=\"..\/opac\/img\/worldcat2.gif\" width=\"12\" height=\"12\"\r\n alt=\"Pregled zapisa v katalogu WorldCat\" title=\"Pregled zapisa v katalogu WorldCat\" class=\"cond2\" \/><\/a><\/td>\r\n            <\/tr>","<tr>\r\n                <td class=\"small\" valign=\"top\"><input type=\"checkbox\" name=\"ch7\" value=\"7\" class=\"tblchckbx1\" \/><\/td>\r\n                <td class=\"small\" valign=\"top\">7.<\/td>\r\n                <td class=\"small\" valign=\"top\"><img src=\"..\/opac\/img\/rank3.gif\" class=\"rank\" alt=\"ranking\" \/><\/td>\r\n                <td class=\"small vecje\" valign=\"top\">Rowling, J. K.<\/td>\r\n                <td class=\"small vecje\" valign=\"top\"><a href=\"http:\/\/cobiss6.izum.si\/scripts\/cobiss?ukaz=DISP&amp;id=1348336139279200&amp;rec=7&amp;sid=1\">Harry Potter. [Del 1], Kamen modrosti<\/a><\/td>\r\n                <td class=\"small ico\" valign=\"top\"><img src=\"..\/opac\/img\/ic_knjiga.gif\" width=\"16\" height=\"16\" class=\"ico2\" alt=\"type\" \/>roman<\/td>\r\n                <td class=\"small\" valign=\"top\">slv<\/td>\r\n                <td class=\"small\" valign=\"top\">2008<\/td>\r\n                <td class=\"small ico\" valign=\"top\"><img src=\"..\/opac\/img\/st-red.gif\" width=\"10\" height=\"10\" class=\"ico\" alt=\"availability\" \/>izposojeno<\/td>\r\n                <td class=\"small\" valign=\"top\"><a href=\"http:\/\/metaiskalnik.izum.si\/sfxlcl41?url_ver=Z39.88-2004&url_ctx_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Actx&ctx_ver=Z39.88-2004&ctx_enc=info%3Aofi%2Fenc%3AUTF-8&rft_val_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Abook&rft.aulast=Rowling&rft.aufirst=J.%20K.&rft.btitle=Harry%20Potter&rft.isbn=978-961-01-0543-5&rft.place=Ljubljana&rft.pub=Mladinska%20knjiga&rft.date=2008&rft.edition=1.%20izd.&rft.tpages=228&rft.series=Zbirka%20Harry%20Potter&rft.genre=book&rft.sici=237836544&rfr_id=COBISS_OPAC&sfx.ignore_date_threshold=1\" target=\"meta\"><img src=\"..\/opac\/img\/sfx.gif\" width=\"12\" height=\"12\"\r\n alt=\"Povezave do informacijskih servisov\" title=\"Povezave do informacijskih servisov\" class=\"cond2\" \/><\/a>&nbsp;<a href=\"http:\/\/www.worldcat.org\/oclc\/449361016\" target=\"wcat\"><img\r\n src=\"..\/opac\/img\/worldcat2.gif\" width=\"12\" height=\"12\"\r\n alt=\"Pregled zapisa v katalogu WorldCat\" title=\"Pregled zapisa v katalogu WorldCat\" class=\"cond2\" \/><\/a><\/td>\r\n            <\/tr>","<tr>\r\n                <td class=\"small2\" valign=\"top\"><input type=\"checkbox\" name=\"ch8\" value=\"8\" class=\"tblchckbx1\" \/><\/td>\r\n                <td class=\"small2\" valign=\"top\">8.<\/td>\r\n                <td class=\"small2\" valign=\"top\"><img src=\"..\/opac\/img\/rank3.gif\" class=\"rank\" alt=\"ranking\" \/><\/td>\r\n                <td class=\"small2 vecje\" valign=\"top\">Rowling, J. K.<\/td>\r\n                <td class=\"small2 vecje\" valign=\"top\"><a href=\"http:\/\/cobiss6.izum.si\/scripts\/cobiss?ukaz=DISP&amp;id=1348336139279200&amp;rec=8&amp;sid=1\">Harry Potter. [Del 3], Jetnik iz Azkabana<\/a><\/td>\r\n                <td class=\"small2 ico\" valign=\"top\"><img src=\"..\/opac\/img\/ic_knjiga.gif\" width=\"16\" height=\"16\" class=\"ico2\" alt=\"type\" \/>(znanstveno)fantasti\u010dna proza<\/td>\r\n                <td class=\"small2\" valign=\"top\">slv<\/td>\r\n                <td class=\"small2\" valign=\"top\">2011<\/td>\r\n                <td class=\"small2 ico\" valign=\"top\"><img src=\"..\/opac\/img\/st-green.gif\" width=\"10\" height=\"10\" class=\"ico\" alt=\"availability\" \/>prosto - na dom<\/td>\r\n                <td class=\"small2\" valign=\"top\"><a href=\"http:\/\/metaiskalnik.izum.si\/sfxlcl41?url_ver=Z39.88-2004&url_ctx_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Actx&ctx_ver=Z39.88-2004&ctx_enc=info%3Aofi%2Fenc%3AUTF-8&rft_val_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Abook&rft.aulast=Rowling&rft.aufirst=J.%20K.&rft.btitle=Harry%20Potter&rft.isbn=978-961-01-1837-4&rft.place=Ljubljana&rft.pub=Mladinska%20knjiga&rft.date=2011&rft.edition=1.%20izd.&rft.tpages=349&rft.series=Zbirka%20%C5%BDepnice&rft.genre=book&rft.sici=256452096&rfr_id=COBISS_OPAC&sfx.ignore_date_threshold=1\" target=\"meta\"><img src=\"..\/opac\/img\/sfx.gif\" width=\"12\" height=\"12\"\r\n alt=\"Povezave do informacijskih servisov\" title=\"Povezave do informacijskih servisov\" class=\"cond2\" \/><\/a><\/td>\r\n            <\/tr>","<tr>\r\n                <td class=\"small\" valign=\"top\"><input type=\"checkbox\" name=\"ch9\" value=\"9\" class=\"tblchckbx1\" \/><\/td>\r\n                <td class=\"small\" valign=\"top\">9.<\/td>\r\n                <td class=\"small\" valign=\"top\"><img src=\"..\/opac\/img\/rank3.gif\" class=\"rank\" alt=\"ranking\" \/><\/td>\r\n                <td class=\"small vecje\" valign=\"top\">Rowling, J. K.<\/td>\r\n                <td class=\"small vecje\" valign=\"top\"><a href=\"http:\/\/cobiss6.izum.si\/scripts\/cobiss?ukaz=DISP&amp;id=1348336139279200&amp;rec=9&amp;sid=1\">Harry Potter. [Del 1], Kamen modrosti<\/a><\/td>\r\n                <td class=\"small ico\" valign=\"top\"><img src=\"..\/opac\/img\/ic_knjiga.gif\" width=\"16\" height=\"16\" class=\"ico2\" alt=\"type\" \/>(znanstveno)fantasti\u010dna proza<\/td>\r\n                <td class=\"small\" valign=\"top\">slv<\/td>\r\n                <td class=\"small\" valign=\"top\">2011<\/td>\r\n                <td class=\"small ico\" valign=\"top\"><img src=\"..\/opac\/img\/st-red.gif\" width=\"10\" height=\"10\" class=\"ico\" alt=\"availability\" \/>izposojeno<\/td>\r\n                <td class=\"small\" valign=\"top\"><a href=\"http:\/\/metaiskalnik.izum.si\/sfxlcl41?url_ver=Z39.88-2004&url_ctx_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Actx&ctx_ver=Z39.88-2004&ctx_enc=info%3Aofi%2Fenc%3AUTF-8&rft_val_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Abook&rft.aulast=Rowling&rft.aufirst=J.%20K.&rft.btitle=Harry%20Potter&rft.isbn=978-961-01-0543-5&rft.place=Ljubljana&rft.pub=Mladinska%20knjiga&rft.date=2011&rft.edition=1.%20izd.&rft.tpages=242&rft.series=Zbirka%20%C5%BDepnice&rft.genre=book&rft.sici=256448256&rfr_id=COBISS_OPAC&sfx.ignore_date_threshold=1\" target=\"meta\"><img src=\"..\/opac\/img\/sfx.gif\" width=\"12\" height=\"12\"\r\n alt=\"Povezave do informacijskih servisov\" title=\"Povezave do informacijskih servisov\" class=\"cond2\" \/><\/a><\/td>\r\n            <\/tr>","<tr>\r\n                <td class=\"small2\" valign=\"top\"><input type=\"checkbox\" name=\"ch10\" value=\"10\" class=\"tblchckbx1\" \/><\/td>\r\n                <td class=\"small2\" valign=\"top\">10.<\/td>\r\n                <td class=\"small2\" valign=\"top\"><img src=\"..\/opac\/img\/rank3.gif\" class=\"rank\" alt=\"ranking\" \/><\/td>\r\n                <td class=\"small2 vecje\" valign=\"top\">Rowling, J. K.<\/td>\r\n                <td class=\"small2 vecje\" valign=\"top\"><a href=\"http:\/\/cobiss6.izum.si\/scripts\/cobiss?ukaz=DISP&amp;id=1348336139279200&amp;rec=10&amp;sid=1\">Harry Potter. [Del 4], Ognjeni kelih<\/a><\/td>\r\n                <td class=\"small2 ico\" valign=\"top\"><img src=\"..\/opac\/img\/ic_knjiga.gif\" width=\"16\" height=\"16\" class=\"ico2\" alt=\"type\" \/>(znanstveno)fantasti\u010dna proza<\/td>\r\n                <td class=\"small2\" valign=\"top\">slv<\/td>\r\n                <td class=\"small2\" valign=\"top\">2011<\/td>\r\n                <td class=\"small2 ico\" valign=\"top\"><img src=\"..\/opac\/img\/st-green.gif\" width=\"10\" height=\"10\" class=\"ico\" alt=\"availability\" \/>prosto - na dom<\/td>\r\n                <td class=\"small2\" valign=\"top\"><a href=\"http:\/\/metaiskalnik.izum.si\/sfxlcl41?url_ver=Z39.88-2004&url_ctx_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Actx&ctx_ver=Z39.88-2004&ctx_enc=info%3Aofi%2Fenc%3AUTF-8&rft_val_fmt=info%3Aofi%3Afmt%3Akev%3Amtx%3Abook&rft.aulast=Rowling&rft.aufirst=J.%20K.&rft.btitle=Harry%20Potter&rft.isbn=978-961-01-1812-1&rft.place=Ljubljana&rft.pub=Mladinska%20knjiga&rft.date=2011&rft.edition=1.%20izd.&rft.tpages=602&rft.series=Zbirka%20%C5%BDepnice&rft.genre=book&rft.sici=256411392&rfr_id=COBISS_OPAC&sfx.ignore_date_threshold=1\" target=\"meta\"><img src=\"..\/opac\/img\/sfx.gif\" width=\"12\" height=\"12\"\r\n alt=\"Povezave do informacijskih servisov\" title=\"Povezave do informacijskih servisov\" class=\"cond2\" \/><\/a><\/td>\r\n            <\/tr>"]
');
$nrResults = 'X';
// */


/* OUTPUT */
$data = Cobiss::parse($response);
if ($format === 'html') {

	//var_dump($results);
	echo 'Iskalni niz: ' . htmlspecialchars($query) . '<br>';
	echo 'Število najdenih rezultatov: ' . $response['nrResults'] . '<br>';
	echo '<style>td { padding: 0px 15px; }</style>';
	echo '<table style="padding: 0px 5px;">' . PHP_EOL;

	/* // This does not take into account that $results may be an exact match!
	foreach ($results as $r) {
		//echo PHP_EOL . $r . PHP_EOL;
		//var_dump($matches);

		print '<tr><td>' . implode('</td><td>', Cobiss::parseResult($r)) . '</td></tr>';
	}*/
	foreach ($data as $r) print '<tr><td>' . implode('</td><td>', $r) . '</td></tr>';

	echo '</table>';
} else {
	//$data = [];
	//foreach ($results as $r) $data[] = Cobiss::parseResult($r);
	echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}