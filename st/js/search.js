/*
   Ajax Library v1.1 by Aleksandr Belov — http://twosphere.ru
   Date: 29.12.2013
   Released under the MIT license.
*/
var Ajax={_init:function(){return this.createXmlHttpRequest()},createXmlHttpRequest:function(){var a;try{a=new ActiveXObject("Microsoft.XMLHTTP")}catch(b){try{a=new ActiveXObject("Msxml2.XMLHTTP")}catch(c){a=!1}}a||"undefined"!=typeof XMLHttpRequest&&(a=new XMLHttpRequest);a||(location.href="http://twosphere.ru/badbrowser");return a},post:function(a,b){var c=this._init();c&&(c.open("POST",a.url,!0),c.setRequestHeader("X-Requested-With","XMLHttpRequest"),c.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),
c.send(Ajax.dataEncode(a.data)),c.onreadystatechange=function(){if(4==c.readyState&&200==c.status){var a=$.parseJSON(c.responseText);b(a)}})},simple_get:function(a,b){var c=this._init();c&&(c.open("GET",a.url+"?"+Ajax.dataEncode(a.data),!0),c.setRequestHeader("X-Requested-With","XMLHttpRequest"),c.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),c.send(),c.onreadystatechange=function(){4==c.readyState&&200==c.status&&b(c.responseText)})},get:function(a,b){var c=this._init();c&&
(c.open("GET",a.url+"?"+Ajax.dataEncode(a.data),!0),c.setRequestHeader("X-Requested-With","XMLHttpRequest"),c.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),c.send(null),c.onreadystatechange=function(){if(4==c.readyState&&200==c.status){var a=$.parseJSON(c.responseText);b(a)}})},dataEncode:function(a){var b="";if(a){for(var c in a)a.hasOwnProperty(c)&&(b+="&"+c.toString()+"="+encodeURIComponent(a[c]));if("&"==b.charAt(0))return b.substring(1,b.length)}return b}},Search={interval:null,
debug:!1,category:1,previous_value:"",default_count:10,offset:0,all_count:0,last_hash:null,setIntervalSearch:function(a){this.interval||(this.interval=setInterval(function(){Search.search()},a||500))},removeIntervalSearch:function(){this.interval&&clearInterval(this.interval)},changeIntervalSearch:function(a){this.interval&&this.removeIntervalSearch();this.setIntervalSearch(a)},search:function(a){var b=document.getElementById("input_text");if(b.value!=this.previous_value||a)this.previous_value=
b.value,this.last_hash=this.createHash(32),Search.offset=0,Search.all_count=0,Page.loadingAnimate(!0),a=this.createParams(b.value),Ajax.post(a,function(a){Page.insertResult(a)});0!=b.value.length?$("#reset_text_input").css({display:"block"}):$("#reset_text_input").css({display:"none"})},addNewResults:function(a){$(".search_result_add_new:last").off("click").attr("disabled","disabled");var b=document.getElementById("input_text");Page.loadingAnimate(!0);a=this.createParams(b.value,a);Ajax.post(a,
function(a){Page.addResult(a)})},setCategory:function(a){if(this.category==a||1>a)return 0;this.category=a;$(".jq-select-element__sub")[a-1].click();0<document.getElementById("input_text").value.trim().length?this.search(!0):(Page.loadingAnimate(!0),a={data:{category:a}},a.url=Settings.base_url+"/"+Settings.methods_dir+"/"+Settings.materials_base+"."+Settings.get_popular,Ajax.post(a,Page.insertPopular));return!0},changeCategory:function(a){this.category!=a&&(document.getElementById("input_text").value?
$("html,body").stop().scrollTo("#search_result_title_text",100,{offset:-99}):$("html,body").stop().scrollTo(".search_result_wrap_popular",100,{offset:-80}));setTimeout(function(){Search.setCategory(a)},100)},invertLanguage:function(a){return function(a){var c={q:"\u0439","]":"\u044a","'":"\u044d",w:"\u0446",a:"\u0444",z:"\u044f",e:"\u0443",s:"\u044b",x:"\u0447",r:"\u043a",d:"\u0432",c:"\u0441",t:"\u0435",f:"\u0430",v:"\u043c",y:"\u043d",g:"\u043f",b:"\u0438",u:"\u0433",h:"\u0440",n:"\u0442",i:"\u0448",
j:"\u043e",m:"\u044c",o:"\u0449",k:"\u043b",",":"\u0431",p:"\u0437",l:"\u0434",".":"\u044e","[":"\u0445",";":"\u0436","/":".","`":"\u0451","\u0439":"q","\u0444":"a","\u044f":"z","\u0446":"w","\u044b":"s","\u0447":"x","\u0443":"e","\u0432":"d","\u0441":"c","\u043a":"r","\u0430":"f","\u043c":"v","\u0435":"t","\u043f":"g","\u0438":"b","\u043d":"y","\u0440":"h","\u0442":"n","\u0433":"u","\u043e":"j","\u044c":"m","\u0448":"i","\u043b":"k","\u0431":",","\u0449":"o","\u0434":"l","\u044e":".","\u0445":"[",
"\u044d":"'","\u0451":"`","\u044a":"]"};return a.replace(/(.)/g,function(a){return c[a]||a})}(a)},createParams:function(a){var b={};b.url=Settings.base_url+"/"+Settings.methods_dir+"/"+Settings.materials_base+"."+Settings.search_method;b.data={q:this.checkLength(a),category:this.category,count:this.default_count,offset:this.offset,hash:this.last_hash};return b},checkLength:function(a){return 500>=a.length?a:a.substring(0,500)},changeHistory:function(a,b){window.history.pushState&&window.history.pushState(null,
null,"?q="+a+"&category="+b)},createHash:function(a){for(var b="",c=0;c<a;++c)b+="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"[this.getRandomInt(0,61)];return b},getRandomInt:function(a,b){return Math.floor(Math.random()*(b-a+1))+a},setSearch:function(a,b){$("html,body").stop().scrollTo(0,100,{offset:-99,onAfter:function(){document.getElementById("input_text").value=decodeURIComponent(a)}});b&&document.getElementById("input_text").focus();if(!a.length){document.getElementById("input_text").value=
"";Search.previous_value="";$(".search_result_wrap").hide();$(".search_result_wrap_popular").show(0);var c={data:{category:Search.category}};c.url=Settings.base_url+"/"+Settings.methods_dir+"/"+Settings.materials_base+"."+Settings.get_popular;Ajax.post(c,Page.insertPopular)}}},Settings={base_url:"http://"+location.host,methods_dir:"methods",materials_base:"materials",search_method:"search",addauthor_method:"add_author",sendticket_method:"new_ticket",addedition_method:"add_edition",get_popular:"getpopular"},
Page={insertResult:function(a){Search.last_hash==a.hash&&(Page.loadingAnimate(!1),a.emptyQuery?($(".search_result_wrap").fadeOut(50),$(".search_result_wrap_popular").css({display:"block"})):($(".search_result_wrap_popular").css({display:"none"}),$(".search_result_wrap").fadeIn(50),a.found?Page.insertData(a):$(".search_result_layout").html("<div style='text-align: center; padding: 5px; font-size: 18px; font-family: \"Roboto Regular\", Arial, sans-serif'>"+a.text+"</div><div style='text-align: center; margin-top: 10px;'><button class='popup_submit' style='white-space: nowrap; width: auto; padding: 1px 20px; display: inline-block;' onclick='Search.setSearch(\"\", true)'>\u041d\u0430\u0447\u0430\u0442\u044c \u043d\u043e\u0432\u044b\u0439 \u043f\u043e\u0438\u0441\u043a</button></div>")))},
addResult:function(a){Page.loadingAnimate(!1);Page.deleteUpdateButton();a.found&&Page.addData(a)},loadingAnimate:function(a){var b=$(".search_load_spin");$("#input_text")[0].value.length?$("#search_loading").css({right:"28px"}):$("#search_loading").css({right:"0px"});a?b.stop().fadeIn(10):b.stop().fadeOut(50)},deleteUpdateButton:function(){$(".search_result_add_new:last").remove()},addEvent:function(){var a=$(this).data(),b;for(b in a)a.hasOwnProperty(b)&&(a[b]="authors"===b?decodeURIComponent(a[b]).split(","):
decodeURIComponent(a[b]));a.authors=void 0===a.authors?[]:a.authors;Fave.add(a);this.title="\u0423\u0434\u0430\u043b\u0438\u0442\u044c \u0438\u0437 \u0438\u0437\u0431\u0440\u0430\u043d\u043d\u043e\u0433\u043e";$(this).removeClass("fave_add").addClass("fave_remove");$(this).off("click").off("mouseover").off("mouseout");$(this).on("click",Page.removeEvent).on("mouseover",Page.mouseoverEventOff).on("mouseout",Page.mouseoutEventOff)},removeEvent:function(){var a=$(this).data(),b;for(b in a)a.hasOwnProperty(b)&&
(a[b]="authors"===b?decodeURIComponent(a[b]).split(","):decodeURIComponent(a[b]));Fave.removeById(a.id);this.title="\u0414\u043e\u0431\u0430\u0432\u0438\u0442\u044c \u0432 \u0438\u0437\u0431\u0440\u0430\u043d\u043d\u043e\u0435";$(this).removeClass("fave_remove").addClass("fave_add");$(this).off("click").off("mouseover").off("mouseout");$(this).on("click",Page.addEvent).on("mouseover",Page.mouseoverEventOn).on("mouseout",Page.mouseoutEventOn)},mouseoverEventOn:function(){$(this).removeClass("fave_add_none").addClass("fave_add")},
mouseoutEventOn:function(){$(this).removeClass("fave_add").addClass("fave_add_none")},mouseoverEventOff:function(){$(this).removeClass("fave_added").addClass("fave_remove")},mouseoutEventOff:function(){$(this).removeClass("fave_remove").addClass("fave_added")},insertData:function(a,b){var c;b?Page.insertItemsAsHtml(a,!0):(c="http://twosphere.ru/?q="+htmlspecialchars(encodeURIComponent(Search.previous_value))+"&category="+Search.category,c="<div class='search_result_header'><div class='search_result_title'><span id='search_result_title_text'>\u0420\u0435\u0437\u0443\u043b\u044c\u0442\u0430\u0442 \u043f\u043e\u0438\u0441\u043a\u0430: "+
a.text+"."+(a.lang_cl?"<br><div style='font-size: 14px;padding: 7px 0 0 1px;color:#6B6B6B;'>\u0412 \u0437\u0430\u043f\u0440\u043e\u0441\u0435 \u00ab"+htmlspecialchars(Search.previous_value).replace(/(%27)/i, '\'')+"\u00bb \u0432\u043e\u0441\u0441\u0442\u0430\u043d\u043e\u0432\u043b\u0435\u043d\u0430 \u0440\u0430\u0441\u043a\u043b\u0430\u0434\u043a\u0430 \u043a\u043b\u0430\u0432\u0438\u0430\u0442\u0443\u0440\u044b.</div>":"")+"</span></div><span title='\u0421\u043a\u043e\u043f\u0438\u0440\u043e\u0432\u0430\u0442\u044c \u0441\u0441\u044b\u043b\u043a\u0443 \u043d\u0430 \u0440\u0435\u0437\u0443\u043b\u044c\u0442\u0430\u0442 \u043f\u043e\u0438\u0441\u043a\u0430' class='search_result_link clear_fix' onclick='Page.openLinkPopup(\"copy_link\", \""+
encodeURIComponent(c)+'", "\u0421\u0441\u044b\u043b\u043a\u0430 \u043d\u0430 \u0440\u0435\u0437\u0443\u043b\u044c\u0442\u0430\u0442 \u043f\u043e\u0438\u0441\u043a\u0430", 400); Search.changeHistory("'+htmlspecialchars(encodeURIComponent(Search.previous_value))+'", '+Search.category+")'></span></div>",$(".search_result_layout").empty().append(c),Search.all_count+=a.items_count,Page.insertItemsAsHtml(a),Page.addContinueButton(a))},addData:function(a){Search.all_count+=a.items_count;Page.insertItemsAsHtml(a);
Page.addContinueButton(a)},insertItemsAsHtml:function(a,b){var c,e;if(b){f=$(".content_wrapper");f.css({padding:"23px 23px 1px"});0<a.items.length?f.append("<span class='fave_title_text' style='display: inline-block;'>\u0418\u0437\u0431\u0440\u0430\u043d\u043d\u043e\u0435("+a.items.length+"):</span><div title='\u041e\u0447\u0438\u0441\u0442\u0438\u0442\u044c \u0432\u0441\u0435' class='fave_remove_all' onclick='Fave.removeAll()'><span class='fave_remove_overlay clear_fix'></span></div>"):f.append("<span class='fave_no_elements'>\u0412\u044b \u043d\u0438\u0447\u0435\u0433\u043e \u043d\u0435 \u0434\u043e\u0431\u0430\u0432\u0438\u043b\u0438 \u0432 \u0418\u0437\u0431\u0440\u0430\u043d\u043d\u043e\u0435</span>");
for(var d in a.items)if(c=Fave.findById(a.items[d].id)?!0:!1,e="<div class='search_result_wrap' style='margin-bottom: 23px; overflow: visible; display: block;'><div class='search_result_layout' style='padding: 7px 27px 0;'><section class='search_result_item' style='border: 0;'><div class='search_result_item_layout'><div class='fave_add_toggle fl_l'><div data-id="+encodeURIComponent(a.items[d].id)+" data-name="+encodeURIComponent(a.items[d].name)+" data-photo_big="+encodeURIComponent(a.items[d].photo_big)+
" data-download_url="+encodeURIComponent(a.items[d].download_url)+" "+(0!=a.items[d].authors.length?"data-authors="+encodeURIComponent(a.items[d].authors.join(",")):"")+" data-category="+encodeURIComponent(a.items[d].category)+" data-count_dl="+encodeURIComponent(a.items[d].count_dl)+" title='\u0423\u0434\u0430\u043b\u0438\u0442\u044c \u0438\u0437 \u0438\u0437\u0431\u0440\u0430\u043d\u043d\u043e\u0433\u043e' class='fave_add_circle fave_add"+(c?"ed":"_none")+"' id='data_fave_"+a.items[d].id+"'></div></div><div class='search_result_item_content fl_l'><div class='search_result_item_name'><a target='_blank' class='flat_link_block flat_link' href='"+
a.items[d].download_url+"'>"+a.items[d].name+"</a></div><div class='search_result_item_sum'></div><div class='search_result_item_buttons' style='margin-top: 0px;'><div class='search_result_item_buttons_btn'><div class='search_result_item_button_category category"+a.items[d].category+" noactive'></div></div><div class='search_result_item_buttons_btn'><a target='_blank' href='"+a.items[d].download_url+"' title='\u0421\u043a\u0430\u0447\u0430\u0442\u044c \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442' class='search_result_item_button button_dl'></a></div><div class='search_result_item_buttons_btn'><div title='\u041f\u043e\u043b\u0443\u0447\u0438\u0442\u044c \u0441\u0441\u044b\u043b\u043a\u0443 \u043d\u0430 \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442' class='search_result_item_button button_link' onclick='Page.openLinkPopup(\"copy_link\", \""+
encodeURIComponent(a.items[d].download_url)+'", "'+decodeURIComponent("\u0421\u0441\u044b\u043b\u043a\u0430 \u043d\u0430 \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442")+"\", 400)'></div></div><div class='search_result_item_buttons_btn'><div title='\u041f\u043e\u0441\u043c\u043e\u0442\u0440\u0435\u0442\u044c \u043f\u0440\u0435\u0432\u044c\u044e \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442\u0430' onclick='Page.openPreviewPopup(\"img_preview\", \""+encodeURIComponent(a.items[d].photo_big)+"\", \"\u0414\u043e\u043a\u0443\u043c\u0435\u043d\u0442\", 450)' class='search_result_item_button button_preview'></div></div><div class='search_result_item_buttons_btn' style='margin-right: 4px'><span class='data_author'>\u0410\u0432\u0442\u043e\u0440"+
(1<a.items[d].authors.length?"\u044b":"")+": </span></div>"+Page.insertAuthors(a.items[d].authors,!1,!0)+"<div class='search_result_item_buttons_btn'><div title='\u0414\u043e\u0431\u0430\u0432\u0438\u0442\u044c \u0430\u0432\u0442\u043e\u0440\u0430' class='search_result_item_button button_add' onclick='Page.openAddPopup(\"add_new_author\", \""+a.items[d].id+'", "'+encodeURIComponent(a.items[d].name)+"\")' style='display: inline-block;'></div></div></div></div></div></section></div></div> ",f.append(e),
c)$("div.fave_add_circle:last").on("click",Page.removeEvent).on("mouseover",Page.mouseoverEventOff).on("mouseout",Page.mouseoutEventOff);else $("div.fave_add_circle:last").on("click",Page.addEvent).on("mouseover",Page.mouseoverEventOn).on("mouseout",Page.mouseoutEventOn)}else{var g=isSupportPdf();for(d in a.items)if(c=Fave.findById(a.items[d].id)?!0:!1,e="<section class='search_result_item'>"+(g?"<a class='search_result_view_pdf' href='http://twosphere.ru/?open="+decodeURIComponent(a.items[d].download_url.match(/id\=([a-zA-Z0-9]{16,})/i)[1])+
"' onclick='PdfViewer.open(\""+encodeURIComponent(a.items[d].download_url.match(/id\=([a-zA-Z0-9]{16,})/i)[1])+"\"); return !1;'></a>":"")+"<div class='search_result_item_layout'><div class='fave_add_toggle fl_l'><div data-id="+encodeURIComponent(a.items[d].id)+" data-name="+encodeURIComponent(a.items[d].name+" ("+a.items[d].file_size+")")+" data-download_url="+encodeURIComponent(a.items[d].download_url)+" "+(0!=a.items[d].authors.length?"data-authors="+encodeURIComponent(a.items[d].authors.join(",")):
"")+" data-photo_big="+encodeURIComponent(a.items[d].photo_big)+" data-category="+encodeURIComponent(a.items[d].category)+" data-count_dl="+encodeURIComponent(a.items[d].count_dl)+" title='"+(c?"\u0423\u0434\u0430\u043b\u0438\u0442\u044c \u0438\u0437 \u0438\u0437\u0431\u0440\u0430\u043d\u043d\u043e\u0433\u043e":"\u0414\u043e\u0431\u0430\u0432\u0438\u0442\u044c \u0432 \u0438\u0437\u0431\u0440\u0430\u043d\u043d\u043e\u0435")+"' class='fave_add_circle fave_add"+(c?"ed":"_none")+"' id='data_fave_"+a.items[d].id+
"'></div></div><div class='search_result_item_content fl_l'><div class='search_result_item_name'><a target='_blank' class='flat_link_block flat_link' href='"+a.items[d].download_url+"'>"+Page.searchHighlight(a.items[d].name,Search.previous_value,a.lang_cl)+" ("+a.items[d].file_size+")</a></div><div class='search_result_item_sum'><span class='search_result_item_countdl'>"+a.items[d].count_dl+" (\u043a\u043e\u043b\u0438\u0447\u0435\u0441\u0442\u0432\u043e \u0437\u0430\u0433\u0440\u0443\u0437\u043e\u043a)</span></div><div class='search_result_item_buttons'><div class='search_result_item_buttons_btn'><div title='\u041f\u043e\u043a\u0430\u0437\u0430\u0442\u044c \u0440\u0435\u0437\u0443\u043b\u044c\u0442\u0430\u0442\u044b \u0442\u043e\u043b\u044c\u043a\u043e \u0434\u043b\u044f \u044d\u0442\u043e\u0439 \u043a\u0430\u0442\u0435\u0433\u043e\u0440\u0438\u0438' class='search_result_item_button_category category"+
a.items[d].category+"' onclick='Search.changeCategory("+a.items[d].category+")'></div></div><div class='search_result_item_buttons_btn'><a target='_blank' href='"+a.items[d].download_url+"' title='\u0421\u043a\u0430\u0447\u0430\u0442\u044c \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442' class='search_result_item_button button_dl'></a></div><div class='search_result_item_buttons_btn'><div title='\u041f\u043e\u043b\u0443\u0447\u0438\u0442\u044c \u0441\u0441\u044b\u043b\u043a\u0443 \u043d\u0430 \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442' class='search_result_item_button button_link' onclick='Page.openLinkPopup(\"copy_link\", \""+
encodeURIComponent(a.items[d].download_url)+'", "'+decodeURIComponent("\u0421\u0441\u044b\u043b\u043a\u0430 \u043d\u0430 \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442")+"\", 400)'></div></div><div class='search_result_item_buttons_btn'><div title='\u041f\u043e\u0441\u043c\u043e\u0442\u0440\u0435\u0442\u044c \u043f\u0440\u0435\u0432\u044c\u044e \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442\u0430' onclick='Page.openPreviewPopup(\"img_preview\", \""+encodeURIComponent(a.items[d].photo_big)+"\", \"\u0414\u043e\u043a\u0443\u043c\u0435\u043d\u0442\", 450)' class='search_result_item_button button_preview'></div></div><div class='search_result_item_buttons_btn' style='margin-right: 4px'><span class='data_author'>\u0410\u0432\u0442\u043e\u0440"+
(1<a.items[d].authors.length?"\u044b":"")+": </span></div>"+Page.insertAuthors(a.items[d].authors,a.lang_cl)+"<div class='search_result_item_buttons_btn'><div title='\u0414\u043e\u0431\u0430\u0432\u0438\u0442\u044c \u0430\u0432\u0442\u043e\u0440\u0430' class='search_result_item_button button_add' onclick='Page.openAddPopup(\"add_new_author\", \""+a.items[d].id+'", "'+encodeURIComponent(a.items[d].name)+"\")' style='display: inline-block;'></div></div></div></div></div></section>",$(".search_result_layout").append(e),
c)$("div.fave_add_circle:last").on("click",Page.removeEvent).on("mouseover",Page.mouseoverEventOff).on("mouseout",Page.mouseoutEventOff);else $("div.fave_add_circle:last").on("click",Page.addEvent).on("mouseover",Page.mouseoverEventOn).on("mouseout",Page.mouseoutEventOn)}},addContinueButton:function(a){a.items_count==Search.default_count&&Search.all_count!=a.all_items_count&&(a="<button class='search_result_add_new'>\u041f\u043e\u043a\u0430\u0437\u0430\u0442\u044c \u0435\u0449\u0435 "+(10>
a.all_items_count-Search.all_count?a.all_items_count-Search.all_count:Search.default_count)+" \u0438\u0437 "+(a.all_items_count-Search.all_count)+"</button>",$(".search_result_layout").append(a),Search.offset=Search.all_count,$(".search_result_add_new:last").on("click",function(){$(this).addClass("active_btn").text("\u0417\u0430\u0433\u0440\u0443\u0437\u043a\u0430...");Search.addNewResults(Search.offset)}))},insertAuthors:function(a,b,c){var e="";if(0!=a.length)for(var d in a)e+=
"<div class='search_result_item_buttons_btn' "+(c?"":"title='\u041f\u043e\u043a\u0430\u0437\u0430\u0442\u044c \u0432\u0441\u0435 \u043c\u0430\u0442\u0435\u0440\u0438\u0430\u043b\u044b \u044d\u0442\u043e\u0433\u043e \u0430\u0432\u0442\u043e\u0440\u0430' style='cursor: pointer;' onclick='Search.setSearch(\""+encodeURIComponent(a[d])+"\")'")+"><div class='data_author_text"+(c?"":" author_link")+"'>"+Page.searchHighlight(a[d],Search.previous_value,b,"rgba(138, 224, 81, 0.5)")+"</div></div>";return e},
openAddPopup:function(a,b,c){$("#"+a).oModal({top:50,marginBottom:50,closeButton:".popup_close"});document.getElementById("popup_id_edition").value=b;document.getElementById("popup_edition_name").innerHTML="<b>\u00ab"+decodeURIComponent(c)+"\u00bb</b>"},openLinkPopup:function(a,b,c,e){$("#"+a).css({width:e}).oModal({top:50,marginBottom:50,closeButton:".popup_close,.popup_cancel"});document.getElementById("popup_link_input").value=decodeURIComponent(b);document.getElementById("popup_title").innerHTML=
decodeURIComponent(c);$("#popup_link_input").select()},openAddEditionPopup:function(a,b){$("#"+a).css({width:b}).oModal({top:50,marginBottom:50,closeButton:".popup_close"});var c=document.getElementById("add_edition_text");$("#add_edition_text").off("keyup").on("keyup",function(a){13==a.which&&Methods.addEdition()});c.value="";c.focus()},openPreviewPopup:function(a,b,c,e){$("#"+a).css({width:e}).oModal({top:50,marginBottom:50,closeButton:".popup_close,.popup_cancel,#popup_preview_photo"});a=document.createElement("img");
a.src=decodeURIComponent(b);a.alt="\u041f\u0440\u0435\u0432\u044c\u044e \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442\u0430";a.width=400;b=document.getElementById("popup_preview_photo");b.innerHTML="";b.appendChild(a)},getPopular:function(){var a={};a.url=Settings.base_url+"/"+Settings.methods_dir+"/"+Settings.materials_base+"."+Settings.get_popular;Ajax.post(a,Page.insertPopular)},insertPopular:function(a){Page.loadingAnimate(!1);$(".search_result_layout_popular").empty().append("<div class='search_result_header'><div class='search_result_title'><span id='search_result_title_text'>\u041f\u043e\u043f\u0443\u043b\u044f\u0440\u043d\u043e\u0435:</span></div></div>");
$(".search_result_wrap_popular").css({display:"block"});var b,c,e,d=isSupportPdf();for(e in a.items)if(b=Fave.findById(a.items[e].id)?!0:!1,c="<section class='search_result_item'>"+(d?"<a class='search_result_view_pdf' href='http://twosphere.ru/?open="+decodeURIComponent(a.items[e].download_url.match(/id\=([a-zA-Z0-9]{16,})/i)[1])+"' onclick='PdfViewer.open(\""+encodeURIComponent(a.items[e].download_url.match(/id\=([a-zA-Z0-9]{16,})/i)[1])+"\"); return !1;'></a>":"")+"<div class='search_result_item_layout'><div class='fave_add_toggle fl_l'><div data-id="+
encodeURIComponent(a.items[e].id)+" data-name="+encodeURIComponent(a.items[e].name+" ("+a.items[e].file_size+")")+" data-photo_big="+encodeURIComponent(a.items[e].photo_big)+" data-download_url="+encodeURIComponent(a.items[e].download_url)+" "+(0!=a.items[e].authors.length?"data-authors="+encodeURIComponent(a.items[e].authors.join(",")):"")+" data-category="+encodeURIComponent(a.items[e].category)+" data-count_dl="+encodeURIComponent(a.items[e].count_dl)+" title='"+(b?"\u0423\u0434\u0430\u043b\u0438\u0442\u044c \u0438\u0437 \u0438\u0437\u0431\u0440\u0430\u043d\u043d\u043e\u0433\u043e":
"\u0414\u043e\u0431\u0430\u0432\u0438\u0442\u044c \u0432 \u0438\u0437\u0431\u0440\u0430\u043d\u043d\u043e\u0435")+"' class='fave_add_circle_p fave_add"+(b?"ed":"_none")+"' id='data_fave_"+a.items[e].id+"'></div></div><div class='search_result_item_content fl_l'><div class='search_result_item_name'><a target='_blank' class='flat_link_block flat_link' href='"+a.items[e].download_url+"'>"+a.items[e].name+" ("+a.items[e].file_size+")</a></div><div class='search_result_item_sum'><span class='search_result_item_countdl'>"+
a.items[e].count_dl+" (\u043a\u043e\u043b\u0438\u0447\u0435\u0441\u0442\u0432\u043e \u0437\u0430\u0433\u0440\u0443\u0437\u043e\u043a)</span></div><div class='search_result_item_buttons'><div class='search_result_item_buttons_btn'><div class='search_result_item_button_category category"+a.items[e].category+"' onclick='Search.changeCategory("+a.items[e].category+")'></div></div><div class='search_result_item_buttons_btn'><a target='_blank' href='"+a.items[e].download_url+"' title='\u0421\u043a\u0430\u0447\u0430\u0442\u044c \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442' class='search_result_item_button button_dl'></a></div><div class='search_result_item_buttons_btn'><div title='\u041f\u043e\u043b\u0443\u0447\u0438\u0442\u044c \u0441\u0441\u044b\u043b\u043a\u0443 \u043d\u0430 \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442' class='search_result_item_button button_link' onclick='Page.openLinkPopup(\"copy_link\", \""+
encodeURIComponent(a.items[e].download_url)+'", "'+decodeURIComponent("\u0421\u0441\u044b\u043b\u043a\u0430 \u043d\u0430 \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442")+"\", 400)'></div></div><div class='search_result_item_buttons_btn'><div title='\u041f\u043e\u0441\u043c\u043e\u0442\u0440\u0435\u0442\u044c \u043f\u0440\u0435\u0432\u044c\u044e \u0434\u043e\u043a\u0443\u043c\u0435\u043d\u0442\u0430' onclick='Page.openPreviewPopup(\"img_preview\", \""+encodeURIComponent(a.items[e].photo_big)+"\", \"\u0414\u043e\u043a\u0443\u043c\u0435\u043d\u0442\", 450)' class='search_result_item_button button_preview'></div></div><div class='search_result_item_buttons_btn' style='margin-right: 4px'><span class='data_author'>\u0410\u0432\u0442\u043e\u0440"+
(1<a.items[e].authors.length?"\u044b":"")+": </span></div>"+Page.insertAuthors(a.items[e].authors)+"<div class='search_result_item_buttons_btn'><div title='\u0414\u043e\u0431\u0430\u0432\u0438\u0442\u044c \u0430\u0432\u0442\u043e\u0440\u0430' class='search_result_item_button button_add' onclick='Page.openAddPopup(\"add_new_author\", \""+a.items[e].id+'", "'+encodeURIComponent(a.items[e].name)+"\")' style='display: inline-block;'></div></div></div></div></div></section>",$(".search_result_layout_popular").append(c),
b)$("div.fave_add_circle_p:last").on("click",Page.removeEvent).on("mouseover",Page.mouseoverEventOff).on("mouseout",Page.mouseoutEventOff);else $("div.fave_add_circle_p:last").on("click",Page.addEvent).on("mouseover",Page.mouseoverEventOn).on("mouseout",Page.mouseoutEventOn)},searchHighlight:function(a,b,c,e){e=e||"rgba(251, 91, 94, 0.28)";c&&(b=Search.invertLanguage(b));b=b.replace(/[^A-Za-z\u0410-\u042f\u0430-\u044f0-9 ]/gi," ").trim().replace(/\s+/g," ").split(" ");var d="";for(c in b)b.hasOwnProperty(c)&&
(3>b.length||1!=b[c].length)&&(d+=b[c]+"|");pattern="("+d.replace(/\|$/i,"")+")";try{return a.replace(new RegExp(pattern,"gi"),'<span class="rounded" style="background-color: '+e+';">$1</span>')}catch(g){return a}}};