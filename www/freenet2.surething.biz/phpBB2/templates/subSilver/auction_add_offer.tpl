<script language="JavaScript" type="text/javascript">
<!--
// bbCode control by
// subBlue design
// www.subBlue.com

// Startup variables
var imageTag = false;
var theSelection = false;

// Check for Browser & Platform for PC & IE specific bits
// More details from: http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html
var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var clientVer = parseInt(navigator.appVersion); // Get browser version

var is_ie = ((clientPC.indexOf("msie") != -1) && (clientPC.indexOf("opera") == -1));
var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
                && (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
                && (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));
var is_moz = 0;

var is_win = ((clientPC.indexOf("win")!=-1) || (clientPC.indexOf("16bit") != -1));
var is_mac = (clientPC.indexOf("mac")!=-1);

// Helpline messages
b_help = "{L_BBCODE_B_HELP}";
i_help = "{L_BBCODE_I_HELP}";
u_help = "{L_BBCODE_U_HELP}";
q_help = "{L_BBCODE_Q_HELP}";
c_help = "{L_BBCODE_C_HELP}";
l_help = "{L_BBCODE_L_HELP}";
o_help = "{L_BBCODE_O_HELP}";
p_help = "{L_BBCODE_P_HELP}";
w_help = "{L_BBCODE_W_HELP}";
a_help = "{L_BBCODE_A_HELP}";
s_help = "{L_BBCODE_S_HELP}";
f_help = "{L_BBCODE_F_HELP}";

// Define the bbCode tags
bbcode = new Array();
bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[quote]','[/quote]','[code]','[/code]','[list]','[/list]','[list=]','[/list]','[img]','[/img]','[url]','[/url]');
imageTag = false;

// Shows the help messages in the helpline window
function helpline(help) {
   document.post.helpbox.value = eval(help + "_help");
}


// Replacement for arrayname.length property
function getarraysize(thearray) {
   for (i = 0; i < thearray.length; i++) {
      if ((thearray[i] == "undefined") || (thearray[i] == "") || (thearray[i] == null))
         return i;
      }
   return thearray.length;
}

// Replacement for arrayname.push(value) not implemented in IE until version 5.5
// Appends element to the array
function arraypush(thearray,value) {
   thearray[ getarraysize(thearray) ] = value;
}

// Replacement for arrayname.pop() not implemented in IE until version 5.5
// Removes and returns the last element of an array
function arraypop(thearray) {
   thearraysize = getarraysize(thearray);
   retval = thearray[thearraysize - 1];
   delete thearray[thearraysize - 1];
   return retval;
}


function checkForm() {

   formErrors = false;

   if (document.post.auction_offer_text.value.length < 2) {
      formErrors = "{L_EMPTY_MESSAGE}";
   }
   

   if (formErrors) {
      alert(formErrors);
      return false;
   } else {
      bbstyle(-1);
      //formObj.preview.disabled = true;
      //formObj.submit.disabled = true;
      return true;
   }
}

function emoticon(text) {
   var txtarea = document.post.auction_offer_text;
   text = ' ' + text + ' ';
   if (txtarea.createTextRange && txtarea.caretPos) {
      var caretPos = txtarea.caretPos;
      caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
      txtarea.focus();
   } else {
      txtarea.value  += text;
      txtarea.focus();
   }
}

function bbfontstyle(bbopen, bbclose) {
   var txtarea = document.post.auction_offer_text;

   if ((clientVer >= 4) && is_ie && is_win) {
      theSelection = document.selection.createRange().text;
      if (!theSelection) {
         txtarea.value += bbopen + bbclose;
         txtarea.focus();
         return;
      }
      document.selection.createRange().text = bbopen + theSelection + bbclose;
      txtarea.focus();
      return;
   }
   else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0))
   {
      mozWrap(txtarea, bbopen, bbclose);
      return;
   }
   else
   {
      txtarea.value += bbopen + bbclose;
      txtarea.focus();
   }
   storeCaret(txtarea);
}


function bbstyle(bbnumber) {
   var txtarea = document.post.auction_offer_text;

   donotinsert = false;
   theSelection = false;
   bblast = 0;

   if (bbnumber == -1) { // Close all open tags & default button names
      while (bbcode[0]) {
         butnumber = arraypop(bbcode) - 1;
         txtarea.value += bbtags[butnumber + 1];
         buttext = eval('document.post.addbbcode' + butnumber + '.value');
         eval('document.post.addbbcode' + butnumber + '.value ="' + buttext.substr(0,(buttext.length - 1)) + '"');
      }
      imageTag = false; // All tags are closed including image tags :D
      txtarea.focus();
      return;
   }

   if ((clientVer >= 4) && is_ie && is_win)
   {
      theSelection = document.selection.createRange().text; // Get text selection
      if (theSelection) {
         // Add tags around selection
         document.selection.createRange().text = bbtags[bbnumber] + theSelection + bbtags[bbnumber+1];
         txtarea.focus();
         theSelection = '';
         return;
      }
   }
   else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0))
   {
      mozWrap(txtarea, bbtags[bbnumber], bbtags[bbnumber+1]);
      return;
   }

   // Find last occurance of an open tag the same as the one just clicked
   for (i = 0; i < bbcode.length; i++) {
      if (bbcode[i] == bbnumber+1) {
         bblast = i;
         donotinsert = true;
      }
   }

   if (donotinsert) {      // Close all open tags up to the one just clicked & default button names
      while (bbcode[bblast]) {
            butnumber = arraypop(bbcode) - 1;
            txtarea.value += bbtags[butnumber + 1];
            buttext = eval('document.post.addbbcode' + butnumber + '.value');
            eval('document.post.addbbcode' + butnumber + '.value ="' + buttext.substr(0,(buttext.length - 1)) + '"');
            imageTag = false;
         }
         txtarea.focus();
         return;
   } else { // Open tags

      if (imageTag && (bbnumber != 14)) {      // Close image tag before adding another
         txtarea.value += bbtags[15];
         lastValue = arraypop(bbcode) - 1;   // Remove the close image tag from the list
         document.post.addbbcode14.value = "Img";   // Return button back to normal state
         imageTag = false;
      }

      // Open tag
      txtarea.value += bbtags[bbnumber];
      if ((bbnumber == 14) && (imageTag == false)) imageTag = 1; // Check to stop additional tags after an unclosed image tag
      arraypush(bbcode,bbnumber+1);
      eval('document.post.addbbcode'+bbnumber+'.value += "*"');
      txtarea.focus();
      return;
   }
   storeCaret(txtarea);
}

// From http://www.massless.org/mozedit/
function mozWrap(txtarea, open, close)
{
   var selLength = txtarea.textLength;
   var selStart = txtarea.selectionStart;
   var selEnd = txtarea.selectionEnd;
   if (selEnd == 1 || selEnd == 2)
      selEnd = selLength;

   var s1 = (txtarea.value).substring(0,selStart);
   var s2 = (txtarea.value).substring(selStart, selEnd)
   var s3 = (txtarea.value).substring(selEnd, selLength);
   txtarea.value = s1 + open + s2 + close + s3;
   return;
}

// Insert at Claret position. Code from
// http://www.faqts.com/knowledge_base/view.phtml/aid/1052/fid/130
function storeCaret(textEl) {
   if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
}

//-->
</script>

                <form enctype="multipart/form-data" method="post" name="post" action="{S_AUCTION_ADD_OFFER_ACTION}">
                <table width="100%" cellspacing="1" border="0" class="forumline">
                     <tr>
                         <th colspan="3" class="thHead" align="center">{AUCTION_NEW_OFFER}</th>
                     </tr>
                     <tr>
                         <td class="row1">
                             <span class="forumlink">{L_AUCTION_OFFER_OFFERER}</span>
                         </td>
                         <td colspan="2" class="row1" valign="center">
                             <span class="forumlink" align="center">{AUCTION_OFFER_OFFERER}</span>
                         </td>
                     </tr>
                     <tr>
                         <td class="row1">
                             <span class="forumlink">{L_AUCTION_ROOM_TITLE}</span>
                         </td>
                         <td colspan="2" class="row1">
                             <span class="forumlink" align="center" valign="center"><select name="auction_room_id">{AUCTION_ROOM_LIST_DD}</select></span>
                         </td>
                     </tr>
                     <tr>
                         <td class="row1">
                             <span class="forumlink">{L_AUCTION_OFFER_TITLE}</span><br>
                             <span class="genmed">{L_AUCTION_OFFER_TITLE_EXPLAIN}</span>
                         </td>
                         <td colspan="2" class="row1">
                             <input class="post" type="text" name="auction_offer_title" maxlength="30" size="35"/ value="{AUCTION_OFFER_TITLE}">
                         </td>
                     </tr>
                     <tr>
                         <td class="row1">
                             <span class="forumlink">{L_AUCTION_OFFER_TEXT}</span><br>
                         </td>
                         <td colspan="2" class="row1">
		              <table width="450" border="0" cellspacing="0" cellpadding="2">
		                   <tr align="center" valign="middle">
			                <td><span class="genmed">
			                     <input type="button" class="button" accesskey="b" name="addbbcode0" value=" B " style="font-weight:bold; width: 30px" onClick="bbstyle(0)" onMouseOver="helpline('b')" />
			                     </span>
                                        </td>
			                <td><span class="genmed">
			                     <input type="button" class="button" accesskey="i" name="addbbcode2" value=" i " style="font-style:italic; width: 30px" onClick="bbstyle(2)" onMouseOver="helpline('i')" />
			                     </span>
                                        </td>
			                <td><span class="genmed">
			                     <input type="button" class="button" accesskey="u" name="addbbcode4" value=" u " style="text-decoration: underline; width: 30px" onClick="bbstyle(4)" onMouseOver="helpline('u')" />
			                     </span>
                                        </td>
			                <td><span class="genmed">
			                      <input type="button" class="button" accesskey="q" name="addbbcode6" value="Quote" style="width: 50px" onClick="bbstyle(6)" onMouseOver="helpline('q')" />
			                      </span>
                                         </td>
			                 <td><span class="genmed">
			                      <input type="button" class="button" accesskey="c" name="addbbcode8" value="Code" style="width: 40px" onClick="bbstyle(8)" onMouseOver="helpline('c')" />
			                      </span>
                                         </td>
		                         <td><span class="genmed">
			                      <input type="button" class="button" accesskey="l" name="addbbcode10" value="List" style="width: 40px" onClick="bbstyle(10)" onMouseOver="helpline('l')" />
			                      </span>
                                         </td>
			                 <td><span class="genmed">
			                      <input type="button" class="button" accesskey="o" name="addbbcode12" value="List=" style="width: 40px" onClick="bbstyle(12)" onMouseOver="helpline('o')" />
			                      </span>
                                         </td>
			                 <td><span class="genmed">
			                      <input type="button" class="button" accesskey="p" name="addbbcode14" value="Img" style="width: 40px"  onClick="bbstyle(14)" onMouseOver="helpline('p')" />
			                      </span>
                                         </td>
			                 <td><span class="genmed">
			                      <input type="button" class="button" accesskey="w" name="addbbcode16" value="URL" style="text-decoration: underline; width: 40px" onClick="bbstyle(16)" onMouseOver="helpline('w')" />
			                      </span>
                                         </td>
                                   </tr>
		                   <tr>
			                 <td colspan="9">
                                             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                       <td><span class="genmed"> &nbsp;{L_FONT_COLOR}:
                                                            <select name="addbbcode18" onchange="bbfontstyle('[color=' + this.form.addbbcode18.options[this.form.addbbcode18.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;" onMouseOver="helpline('s')">
					                         <option style="color:black; background-color: {T_TD_COLOR1}" value="{T_FONTCOLOR1}" class="genmed">{L_COLOR_DEFAULT}</option>
					                         <option style="color:darkred; background-color: {T_TD_COLOR1}" value="darkred" class="genmed">{L_COLOR_DARK_RED}</option>
					                         <option style="color:red; background-color: {T_TD_COLOR1}" value="red" class="genmed">{L_COLOR_RED}</option>
					                         <option style="color:orange; background-color: {T_TD_COLOR1}" value="orange" class="genmed">{L_COLOR_ORANGE}</option>
					                         <option style="color:brown; background-color: {T_TD_COLOR1}" value="brown" class="genmed">{L_COLOR_BROWN}</option>
					                         <option style="color:yellow; background-color: {T_TD_COLOR1}" value="yellow" class="genmed">{L_COLOR_YELLOW}</option>
					                         <option style="color:green; background-color: {T_TD_COLOR1}" value="green" class="genmed">{L_COLOR_GREEN}</option>
					                         <option style="color:olive; background-color: {T_TD_COLOR1}" value="olive" class="genmed">{L_COLOR_OLIVE}</option>
					                         <option style="color:cyan; background-color: {T_TD_COLOR1}" value="cyan" class="genmed">{L_COLOR_CYAN}</option>
					                         <option style="color:blue; background-color: {T_TD_COLOR1}" value="blue" class="genmed">{L_COLOR_BLUE}</option>
					                         <option style="color:darkblue; background-color: {T_TD_COLOR1}" value="darkblue" class="genmed">{L_COLOR_DARK_BLUE}</option>
					                         <option style="color:indigo; background-color: {T_TD_COLOR1}" value="indigo" class="genmed">{L_COLOR_INDIGO}</option>
					                         <option style="color:violet; background-color: {T_TD_COLOR1}" value="violet" class="genmed">{L_COLOR_VIOLET}</option>
					                         <option style="color:white; background-color: {T_TD_COLOR1}" value="white" class="genmed">{L_COLOR_WHITE}</option>
					                         <option style="color:black; background-color: {T_TD_COLOR1}" value="black" class="genmed">{L_COLOR_BLACK}</option>
                                                            </select> &nbsp;{L_FONT_SIZE}:
                                                            <select name="addbbcode20" onChange="bbfontstyle('[size=' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + ']', '[/size]')" onMouseOver="helpline('f')">
					                         <option value="7" class="genmed">{L_FONT_TINY}</option>
					                         <option value="9" class="genmed">{L_FONT_SMALL}</option>
					                         <option value="12" selected class="genmed">{L_FONT_NORMAL}</option>
					                         <option value="18" class="genmed">{L_FONT_LARGE}</option>
					                         <option  value="24" class="genmed">{L_FONT_HUGE}</option>
                                                            </select>
                                                            </span>
                                                       </td>
                                                       <td nowrap="nowrap" align="right">
				                            <span class="gensmall"><a href="javascript:bbstyle(-1)" class="genmed" onMouseOver="helpline('a')">{L_BBCODE_CLOSE_TAGS}</a></span>
                                                       </td>
                                                  </tr>
                                             </table>
                                        </td>
                                   </tr>
                                   <tr>
                                        <td colspan="9">
                                             <span class="gensmall">
			                           <input type="text" name="helpbox" size="45" maxlength="100" style="width:450px; font-size:10px" class="helpline" value="{L_STYLES_TIP}" />
                                             </span>
                                        </td>
                                   </tr>
                              </table>
                              <textarea name="auction_offer_text" rows="15" cols="35" wrap="virtual" style="width:450px" tabindex="3" class="post" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{AUCTION_OFFER_TEXT}</textarea>
                         </td>
                     </tr>
                     <tr>
                        <td class="row1">&nbsp;</td>
                        <td colspan="2" class="row1">&nbsp;</td>
                     </tr>
                     <tr>
                         <td class="row1">
                             <span class="forumlink">{L_AUCTION_OFFER_TIME_START}</span>
                         </td>
                         <td class="row1">
                             <span class="genmed">{L_AUCTION_NOW}</span><input type="checkbox" name="time_start_now" value="1"/>
                         </td>
                         <td class="row1">
                             <span class="genmed">{L_AUCTION_OR_DATE}</span>
                             <input class="post" type="number" name="auction_offer_time_start_d" size="2" /><span class="genmed">(dd)</span>&nbsp;
                             <input class="post" type="number" name="auction_offer_time_start_m" size="2" /><span class="genmed">(mm)</span>&nbsp;
                             <input class="post" type="number" name="auction_offer_time_start_y" size="4" /><span class="genmed">(yyyy)</span>                         </td>
                     </tr>
                     <tr>
                         <td class="row1">
                             <span class="forumlink">{L_AUCTION_OFFER_TIME_STOP}</span>
                         </td>
                         <td class="row1">
                             <select name="time_to_end_dd">{AUCTION_TIME_TO_END_DD}</select>
                         </td>
                         <td class="row1">
                             <span class="genmed">{L_AUCTION_OR_DATE}</span>
                             <input class="post" type="number" name="auction_offer_time_stop_d" size="2" /><span class="genmed">(dd)</span>&nbsp;
                             <input class="post" type="number" name="auction_offer_time_stop_m" size="2" /><span class="genmed">(mm)</span>&nbsp;
                             <input class="post" type="number" name="auction_offer_time_stop_y" size="4" /><span class="genmed">(yyyy)</span>
                         </td>
                     </tr>
                     <tr>
                         <td class="row1">
                             <span class="forumlink">{L_AUCTION_OFFER_PRICE_START}</span>
                         </td>
                         <td colspan="2" class="row1">
                             <input class="post" type="number" name="auction_offer_price_start" size="10" value="{AUCTION_OFFER_PRICE_START}"/>
                         </td>
                     </tr>
                     <tr>
                         <td class="row1">
                             <span class="forumlink">{L_AUCTION_OFFER_BID_INCREASE}</span>
                         </td>
                         <td colspan="2" class="row1">
                             {DD_AUCTION_OFFER_BID_INCREASE}
                         </td>
                     </tr>
                     <!-- BEGIN direct_sell -->
                     <tr>
                        <td class="row1"><span class="forumlink">{direct_sell.L_AUCTION_OFFER_DIRECT_SELL}</span></td>
                        <td colspan="2" class="row1"><input class="post" type="number" name="auction_offer_direct_sell_price" size="10" value="{AUCTION_OFFER_DIRECT_SELL_PRICE}" /></td>
                     </tr>
                     <!-- END direct_sell -->

                     <!-- BEGIN offer_shipping -->
                     <tr>
                         <td class="row1">
                             <span class="forumlink">{offer_shipping.L_AUCTION_OFFER_SHIPPING_PRICE}</span>
                         </td>
                         <td colspan="2" class="row1">
                             <input class="post" type="number" name="auction_offer_shipping_price" size="10" value="{AUCTION_OFFER_SHIPPING_PRICE}"/>
                         </td>
                     </tr>
                     <!-- END offer_shipping -->
                     <tr>
                         <td class="row1">
                             <span class="forumlink">{L_AUCTION_OFFER_SELLERS_LOCATION}</span>
                         </td>
                         <td colspan="2" class="row1">
                             <input class="post" type="text" name="auction_offer_sellers_location" maxlength="100" size="35"/ value="{AUCTION_OFFER_SELLERS_LOCATION}">
                         </td>
                     </tr>
                     <tr>
                         <td class="row1">
                             <span class="forumlink">{L_AUCTION_OFFER_ACCEPTED_PAYMENTS}</span>
                         </td>
                         <td colspan="2" class="row1">
                             <input class="post" type="text" name="auction_offer_accepted_payments" maxlength="100" size="35"/ value="{AUCTION_OFFER_ACCEPTED_PAYMENTS}">
                         </td>
                     </tr>
                     <!-- BEGIN offer_picture -->
                     <tr>
                        <td class="row1">
                            <span class="forumlink">{offer_picture.L_AUCTION_OFFER_PICTURE}</span>
                        </td>
                        <td colspan="2" class="row1">
                            <input class="post" type="file" name="auction_offer_picture_file" />
                        </td>
                     </tr>
                     <!-- END offer_picture -->
                     <!-- BEGIN url_upload -->
                     <tr>
                        <td class="row1">
                            <span class="forumlink">{url_upload.L_AUCTION_OFFER_URL_PICTURE}</span>
                        </td>
                        <td colspan="2" class="row1">
                            <input class="post" type="text" name="auction_offer_url_file" value="{AUCTION_OFFER_PICTURE}" />
                        </td>
                     </tr>
                     <!-- END url_upload -->
                     <tr>
                        <td class="row1">&nbsp;</td>
                        <td colspan="2" class="row1">&nbsp;</td>
                     </tr>

                     <!-- BEGIN offer_bold -->
                     <tr>
                        <td class="row1"><span class="forumlink">{offer_bold.L_AUCTION_OFFER_BOLD}</span></td>
                        <td colspan="2" class="row1"><input type="checkbox" name="offer_bold" value="1" {AUCTION_OFFER_BOLD_CHECKED} /></td>
                     </tr>
                     <!-- END offer_bold -->
                     <!-- BEGIN offer_on_top -->
                     <tr>
                        <td class="row1"><span class="forumlink">{offer_on_top.L_AUCTION_OFFER_ON_TOP}</span></td>
                        <td colspan="2" class="row1"><input type="checkbox" name="offer_on_top" value="1" {AUCTION_OFFER_ON_TOP_CHECKED} /></td>
                     </tr>
                     <!-- END offer_on_top -->
                     <!-- BEGIN offer_special -->
                     <tr>
                        <td class="row1"><span class="forumlink">{offer_special.L_AUCTION_OFFER_SPECIAL}</span></td>
                        <td colspan="2" class="row1"><input type="checkbox" name="offer_special" value="1" {AUCTION_OFFER_SPECIAL_CHECKED}/> {L_YES}&nbsp;&nbsp;</td>
                     </tr>
                     <!-- END offer_special -->
                     <!-- BEGIN offer_coupon -->
                     <tr>
                         <td class="row1">
                             <span class="forumlink">{offer_coupon.L_AUCTION_OFFER_COUPON}</span></br>
                             <span class="genmed">{offer_coupon.L_AUCTION_OFFER_COUPON_EXPLAIN}</span>
                         </td>
                         <td colspan="2" class="row1">
                             <input class="post" type="text" name="auction_offer_coupon" maxlength="8" size="8"/>
                         </td>
                     </tr>
                     <!-- END offer_coupon -->
                     <tr>
                         <td class="row1" cellpadding="2" colspan="3" align="Center">
                             <br>
                             <input type="submit" class="mainoption" name="login" value="{L_AUCTION_NEW_OFFER}" />
                             <br><br>
                         </td>

                     </tr>
              </table>
              </form>

              <br>
              <table width="100%" cellspacing="0" border="0" align="center" cellpadding="2">
                <tr> 
                  <td align="right"><span class="gensmall">{S_TIMEZONE}</span></td>
                </tr>
              </table>

              <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
                <tr> 
                  <td class="catHead" colspan="2" height="28"><span class="cattitle"><a href="{U_VIEWONLINE}" class="cattitle">{L_WHO_IS_ONLINE}</a></span></td>
                </tr>
                <tr> 
                  <td class="row1" align="center" valign="middle" rowspan="2"><img src="templates/subSilver/images/whosonline.gif" alt="{L_WHO_IS_ONLINE}" /></td>
                  <td class="row1" align="left" width="100%"><span class="gensmall">{TOTAL_POSTS}<br />{TOTAL_USERS}<br />{NEWEST_USER}</span>
                  </td>
                </tr>
                <tr> 
                  <td class="row1" align="left"><span class="gensmall">{TOTAL_USERS_ONLINE} &nbsp; [ {L_WHOSONLINE_ADMIN} ] &nbsp; [ {L_WHOSONLINE_MOD} ]<br />{RECORD_USERS}<br />{LOGGED_IN_USER_LIST}</span></td>
                </tr>
              </table>

              <table width="100%" cellpadding="1" cellspacing="1" border="0">
              <tr>
                  <td align="left" valign="top"><span class="gensmall">{L_ONLINE_EXPLAIN}</span></td>
              </tr>
              </table>

              <!-- BEGIN switch_user_logged_out -->
              <form method="post" action="{S_LOGIN_ACTION}">
                <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
                  <tr> 
                    <td class="catHead" height="28"><a name="login"></a><span class="cattitle">{L_LOGIN_LOGOUT}</span></td>
                  </tr>
                  <tr> 
                    <td class="row1" align="center" valign="middle" height="28"><span class="gensmall">{L_USERNAME}: 
                      <input class="post" type="text" name="username" size="10" />
                      &nbsp;&nbsp;&nbsp;{L_PASSWORD}: 
                      <input class="post" type="password" name="password" size="10" maxlength="32" />
                      &nbsp;&nbsp; &nbsp;&nbsp;{L_AUTO_LOGIN} 
                      <input class="text" type="checkbox" name="autologin" />
                      &nbsp;&nbsp;&nbsp; 
                      <input type="submit" class="mainoption" name="login" value="{L_LOGIN}" />
                      </span> </td>
                  </tr>
                </table>
              </form>
              <!-- END switch_user_logged_out -->