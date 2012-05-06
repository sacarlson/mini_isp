<SCRIPT language="JavaScript">
<!--
function deactivate()
      {
           document.form1.auction_paymentsystem_activate_paypal[1].checked=true;
           document.form1.auction_paymentsystem_activate_moneybooker[1].checked=true;
           document.form1.auction_paymentsystem_activate_paypal[0].disabled=true;
           document.form1.auction_paymentsystem_activate_paypal[1].disabled=true;
           document.form1.auction_paymentsystem_activate_moneybooker[0].disabled=true;
           document.form1.auction_paymentsystem_activate_moneybooker[1].disabled=true;
      }
//-->
</script>

<h1>{L_AUCTION_CONFIG}</h1>

<p>{L_AUCTION_CONFIG_EXPLAIN}</p>

<form action="{S_AUCTION_CONFIG_ACTION}" method="post" name="form1">
<table width="90%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
    <tr>
      <th class="thHead" colspan="2">{L_AUCTION_GENERAL_SETTINGS}</th>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_CONFIG_DISABLE}<br /></span><span class="gensmall">{L_AUCTION_CONFIG_DISABLE_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_disable" value="1" {AUCTION_DISABLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_disable" value="0" {AUCTION_DISABLE_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_CONFIG_DISABLE_OFFER}<br /></span><span class="gensmall">{L_AUCTION_CONFIG_DISABLE_OFFER_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_offer_disable" value="1" {AUCTION_OFFER_DISABLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_offer_disable" value="0" {AUCTION_OFFER_DISABLE_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_CONFIG_CURRENCY}<br /></span><span class="gensmall">{L_AUCTION_CONFIG_CURRENCY_EXPLAIN}</span></td>
        <td class="row2">{CURRENCY_DD}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_ROOM_PAGINATION}<br /></span><span class="gensmall">{L_AUCTION_ROOM_PAGINATION_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" maxlength="5" size="5" name="auction_room_pagination" / value="{AUCTION_ROOM_PAGINATION}"></td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_PM_NOTIFY}<br /></span><span class="gensmall">{L_AUCTION_PM_NOTIFY_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_pm_notify" value="1" {AUCTION_PM_NOTIFY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_pm_notify" value="0" {AUCTION_PM_NOTIFY_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_EMAIL_NOTIFY}<br /></span><span class="gensmall">{L_AUCTION_EMAIL_NOTIFY_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_email_notify" value="1" {AUCTION_EMAIL_NOTIFY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_email_notify" value="0" {AUCTION_EMAIL_NOTIFY_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_END_NOTIFY_EMAIL}*<br /></span><span class="gensmall">{L_AUCTION_END_NOTIFY_EMAIL_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_end_notify_email" value="1" {AUCTION_END_NOTIFY_EMAIL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_end_notify_email" value="0" {AUCTION_END_NOTIFY_EMAIL_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_END_NOTIFY_PM}*<br /></span><span class="gensmall">{L_AUCTION_END_NOTIFY_PM_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_end_notify_pm" value="1" {AUCTION_END_NOTIFY_PM_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_end_notify_pm" value="0" {AUCTION_END_NOTIFY_PM_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_PSEUDO_CRON}<br /></span><span class="gensmall">{L_AUCTION_PSEUDO_CRON_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_pseudo_cron" value="1" {AUCTION_PSEUDO_CRON_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_pseudo_cron" value="0" {AUCTION_PSEUDO_CRON_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_PSEUDO_CRON_FREQEUNCE}<br /></span><span class="gensmall">{L_AUCTION_PSEUDO_CRON_FREQEUNCE_EXPLAIN}</span></td>
        <td class="row2">{PSEUDO_CRON_FREQUENCE}</td>
    </tr>

    <tr>
      <td class="row2" colspan="2">*{L_AUCTION_END_NOTIFY}</th>
    </tr>
    <tr>
      <th class="thHead" colspan="2">{L_AUCTION_OFFER_DETAILS}</th>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_OFFER_AMOUNT_MAX}<br /></span><span class="gensmall">{L_AUCTION_OFFER_AMOUNT_MAX_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" maxlength="25" size="25" name="auction_offer_amount_max" / value="{AUCTION_OFFER_AMOUNT_MAX}"></td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_OFFER_AMOUNT_MIN}<br /></span><span class="gensmall">{L_AUCTION_OFFER_AMOUNT_MIN_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" maxlength="25" size="25" name="auction_offer_amount_min" / value="{AUCTION_OFFER_AMOUNT_MIN}"></td>
    </tr>
    <tr>
        <td class="row1">{L_AUCTION_SELFBIDS_ALLOW}<br /></span></td>
        <td class="row2"><input type="radio" name="auction_allow_self_bids" value="1" {AUCTION_SELFBIDS_ALLOW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_allow_self_bids" value="0" {AUCTION_SELFBIDS_ALLOW_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_AUCTION_SHOW_TIMELINE}<br /></span></td>
        <td class="row2"><input type="radio" name="auction_show_timeline" value="1" {AUCTION_SHOW_TIMELINE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_show_timeline" value="0" {AUCTION_SHOW_TIMELINE_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_AUCTION_ALLOW_COMMENT}<br /></span></td>
        <td class="row2"><input type="radio" name="auction_allow_comment" value="1" {AUCTION_ALLOW_COMMENT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_allow_comment" value="0" {AUCTION_ALLOW_COMMENT_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_AUCTION_ALLOW_CHANGE_COMMENT}<br /></span></td>
        <td class="row2"><input type="radio" name="auction_allow_change_comment" value="1" {AUCTION_ALLOW_CHANGE_COMMENT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_allow_change_comment" value="0" {AUCTION_ALLOW_CHANGE_COMMENT_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_AUCTION_SHIPPING_ALLOW}<br /></span></td>
        <td class="row2"><input type="radio" name="auction_offer_allow_shipping" value="1" {AUCTION_SHIPPING_ALLOW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_offer_allow_shipping" value="0" {AUCTION_SHIPPING_ALLOW_NO} /> {L_NO}</td>
    </tr>
    <tr>
      <th class="thHead" colspan="2">{L_AUCTION_BLOCK_SETTING}</th>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_BLOCK_NEWS}<br /></span><span class="gensmall">{L_AUCTION_BLOCK_NEWS_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_block_display_news" value="1" {AUCTION_BLOCK_DISPLAY_NEWS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_block_display_news" value="0" {AUCTION_BLOCK_DISPLAY_NEWS_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_BLOCK_NEWS_FORUM_ID}<br /></span><span class="gensmall">{L_AUCTION_BLOCK_NEWS_FORUM_ID_EXPLAIN}</span></td>
        <td class="row2">{NEWS_FORUM}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_BLOCK_CLOSE_TO_END}<br /></span><span class="gensmall">{L_AUCTION_BLOCK_CLOSE_TO_END_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_block_display_close_to_end" value="1" {AUCTION_BLOCK_DISPLAY_CLOSE_TO_END_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_block_display_close_to_end" value="0" {AUCTION_BLOCK_DISPLAY_CLOSE_TO_END_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_CLOSE_TO_END_NUM}<br /></span><span class="gensmall">{L_AUCTION_CLOSE_TO_END_NUM_EXPLAIN}</span></td>
        <td class="row2"><input class="post" type="text" maxlength="3" size="3" name="auction_config_close_to_end_number" / value="{AUCTION_CLOSE_TO_END_NUM}"></td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_BLOCK_TICKER}<br /></span><span class="gensmall">{L_AUCTION_BLOCK_TICKER_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_block_display_ticker" value="1" {AUCTION_BLOCK_DISPLAY_TICKER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_block_display_ticker" value="0" {AUCTION_BLOCK_DISPLAY_TICKER_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_BLOCK_AUCTION_ROOMS}<br /></span><span class="gensmall">{L_AUCTION_BLOCK_AUCTION_ROOMS_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_block_display_auction_rooms" value="1" {AUCTION_BLOCK_DISPLAY_AUCTION_ROOMS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_block_display_auction_rooms" value="0" {AUCTION_BLOCK_DISPLAY_AUCTION_ROOMS_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_BLOCK_DROP_DOWN_AUCTION_ROOMS}<br /></span><span class="gensmall">{L_AUCTION_BLOCK_DROP_DOWN_AUCTION_ROOMS_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_block_display_drop_down_auction_rooms" value="1" {AUCTION_BLOCK_DISPLAY_DROP_DOWN_AUCTION_ROOMS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_block_display_drop_down_auction_rooms" value="0" {AUCTION_BLOCK_DISPLAY_DROP_DOWN_AUCTION_ROOMS_NO} /> {L_NO}</td>
    </tr>

    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_BLOCK_STATISTICS}<br /></span><span class="gensmall">{L_AUCTION_BLOCK_STATISTICS_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_block_display_statistics" value="1" {AUCTION_BLOCK_DISPLAY_STATISTICS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_block_display_statistics" value="0" {AUCTION_BLOCK_DISPLAY_STATISTICS_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_BLOCK_MYAUCTIONS}<br /></span><span class="gensmall">{L_AUCTION_BLOCK_MYAUCTIONS_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_block_display_myauctions" value="1" {AUCTION_BLOCK_DISPLAY_MYAUCTIONS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_block_display_myauctions" value="0" {AUCTION_BLOCK_DISPLAY_MYAUCTIONS_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_BLOCK_CALENDAR}<br /></span><span class="gensmall">{L_AUCTION_BLOCK_CALENDAR_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_block_display_calendar" value="1" {AUCTION_BLOCK_DISPLAY_CALENDAR_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_block_display_calendar" value="0" {AUCTION_BLOCK_DISPLAY_CALENDAR_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_BLOCK_SEARCH}<br /></span><span class="gensmall">{L_AUCTION_BLOCK_SEARCH_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_block_display_search" value="1" {AUCTION_BLOCK_DISPLAY_SEARCH_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_block_display_search" value="0" {AUCTION_BLOCK_DISPLAY_SEARCH_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_BLOCK_SPECIAL}<br /></span><span class="gensmall">{L_AUCTION_BLOCK_SPECIAL_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_block_display_specials" value="1" {AUCTION_BLOCK_DISPLAY_SPECIAL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_block_display_specials" value="0" {AUCTION_BLOCK_DISPLAY_SPECIAL_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_BLOCK_PRICE_INFO}<br /></span><span class="gensmall">{L_AUCTION_BLOCK_PRICE_INFO_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_block_display_priceinformation" value="1" {AUCTION_BLOCK_DISPLAY_PRICE_INFO_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_block_display_priceinformation" value="0" {AUCTION_BLOCK_DISPLAY_PRICE_INFO_NO} /> {L_NO}</td>
    </tr>
    <tr>
         <td class="row1">{L_AUCTION_BLOCK_SPECIALS_LIMIT}<br /><span class="gensmall">{L_AUCTION_BLOCK_SPECIALS_LIMIT_EXPLAIN}</span></td>
         <td class="row2"><input class="post" type="text" maxlength="3" size="3" name="auction_block_specials_limit" value="{AUCTION_BLOCK_SPECIALS_LIMIT}" /></td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_BLOCK_LAST_BIDS}<br /></span><span class="gensmall">{L_AUCTION_BLOCK_LAST_BIDS_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_block_display_last_bids" value="1" {AUCTION_BLOCK_DISPLAY_LAST_BIDS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_block_display_last_bids" value="0" {AUCTION_BLOCK_DISPLAY_LAST_BIDS_NO} /> {L_NO}</td>
    </tr>
    <tr>
         <td class="row1">{L_AUCTION_BLOCK_LAST_BIDS_LIMIT}<br /><span class="gensmall">{L_AUCTION_BLOCK_LAST_BIDS_LIMIT_EXPLAIN}</span></td>
         <td class="row2"><input class="post" type="text" maxlength="3" size="3" name="auction_config_last_bids_number" value="{AUCTION_BLOCK_LAST_BIDS_LIMIT}" /></td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_BLOCK_NEWEST_OFFER}<br /></span><span class="gensmall">{L_AUCTION_BLOCK_NEWEST_OFFER_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_block_display_newest_offers" value="1" {AUCTION_BLOCK_DISPLAY_LAST_BIDS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_block_display_newest_offers" value="0" {AUCTION_BLOCK_DISPLAY_LAST_BIDS_NO} /> {L_NO}</td>
    </tr>
    <tr>
         <td class="row1">{L_AUCTION_BLOCK_NEWEST_OFFER_NUMBER}<br /></td>
         <td class="row2"><input class="post" type="text" maxlength="3" size="3" name="auction_config_newest_offers_number" value="{AUCTION_BLOCK_NEWEST_OFFER_LIMIT}" /></td>
    </tr>
    <tr>
      <th class="thHead" colspan="2">{L_AUCTION_COSTS}</th>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_OFFER_COST_BASIC}<br /></span></td>
        <td class="row2"><input class="post" type="text" maxlength="5" size="5" name="auction_offer_cost_basic" / value="{AUCTION_OFFER_COST_BASIC}"></td>
    </tr>
    <tr>
        <td class="row1">{L_AUCTION_OFFER_ALLOW_BOLD}</td>
        <td class="row2"><input type="radio" name="auction_offer_allow_bold" value="1" {AUCTION_OFFER_ALLOW_BOLD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_offer_allow_bold" value="0" {AUCTION_OFFER_ALLOW_BOLD_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_OFFER_COST_BOLD}<br /></td>
        <td class="row2"><input class="post" type="text" maxlength="5" size="5" name="auction_offer_cost_bold" / value="{AUCTION_OFFER_COST_BOLD}"></td>
    </tr>
    <tr>
        <td class="row1">{L_AUCTION_OFFER_ALLOW_ON_TOP}</td>
        <td class="row2"><input type="radio" name="auction_offer_allow_on_top" value="1" {AUCTION_OFFER_ALLOW_ON_TOP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_offer_allow_on_top" value="0" {AUCTION_OFFER_ALLOW_ON_TOP_NO} /> {L_NO}</td>
    </tr>

    <tr>
        <td class="row1">{L_AUCTION_OFFER_COST_ON_TOP}<br /></span></td>
        <td class="row2"><input class="post" type="text" maxlength="5" size="5" name="auction_offer_cost_on_top" / value="{AUCTION_OFFER_COST_ON_TOP}"></td>
    </tr>
    <tr>
        <td class="row1">{L_AUCTION_OFFER_ALLOW_SPECIAL}</td>
        <td class="row2"><input type="radio" name="auction_offer_allow_special" value="1" {AUCTION_OFFER_ALLOW_SPECIAL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_offer_allow_special" value="0" {AUCTION_OFFER_ALLOW_SPECIAL_NO} /> {L_NO}</td>
    </tr>

    <tr>
        <td class="row1">{L_AUCTION_OFFER_COST_SPECIAL}<br /></span></td>
        <td class="row2"><input class="post" type="text" maxlength="5" size="5" name="auction_offer_cost_special" / value="{AUCTION_OFFER_COST_SPECIAL}"></td>
    </tr>
    <tr>
        <td class="row1">{L_AUCTION_OFFER_ALLOW_DIRECT_SELL}</td>
        <td class="row2"><input type="radio" name="auction_allow_direct_sell" value="1" {AUCTION_OFFER_ALLOW_DIRECT_SELL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_allow_direct_sell" value="0" {AUCTION_OFFER_ALLOW_DIRECT_SELL_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_AUCTION_OFFER_COST_DIRECT_SELL}<br /></span></td>
        <td class="row2"><input class="post" type="text" maxlength="5" size="5" name="auction_offer_cost_direct_sell" / value="{AUCTION_OFFER_COST_DIRECT_SELL}"></td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_OFFER_FINAL_PERCENT}<br /></span><br><span class="gensmall">{L_AUCTION_OFFER_FINAL_PERCENT_EXPLAIN}<br /></span></td>
        <td class="row2"><input class="post" type="text" maxlength="5" size="5" name="auction_offer_cost_final_percent" / value="{AUCTION_OFFER_COST_FINAL_PERCENT}"></td>
    </tr>

    <tr>
        <td class="row1">{L_AUCTION_COUPONS_ALLOW}<br /></span></td>
        <td class="row2"><input type="radio" name="auction_allow_coupons" value="1" {AUCTION_COUPONS_ALLOW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_allow_coupons" value="0" {AUCTION_COUPONS_ALLOW_NO} /> {L_NO}</td>
    </tr>

    <tr>
      <th class="thHead" colspan="2">{L_AUCTION_PAYMENTSYSTEM}</th>
    </tr>
    <tr>
        <td class="row1">{L_AUCTION_PAYMENTSYSTEM_ACTIVATE_PAYPAL}</td>
        <td class="row2"><input type="radio" name="auction_paymentsystem_activate_paypal" value="1" {AUCTION_PAYMENTSYSTEM_ACTIVATE_PAYPAL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_paymentsystem_activate_paypal" value="0" {AUCTION_PAYMENTSYSTEM_ACTIVATE_PAYPAL_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_PAYMENTSYSTEM_PAYPAL_EMAIL}<br /></span></td>
        <td class="row2"><input class="post" type="text" maxlength="100" size="25" name="auction_paymentsystem_paypal_email" / value="{AUCTION_PAYMENTSYSTEM_PAYPAL_EMAIL}"></td>
    </tr>
    <tr>
        <td class="row1">{L_AUCTION_PAYMENTSYSTEM_ACTIVATE_MONEYBOOKER}</td>
        <td class="row2"><input type="radio" name="auction_paymentsystem_activate_moneybooker" value="1" {AUCTION_PAYMENTSYSTEM_ACTIVATE_MONEYBOOKER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_paymentsystem_activate_moneybooker" value="0" {AUCTION_PAYMENTSYSTEM_ACTIVATE_MONEYBOOKER_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_PAYMENTSYSTEM_MONEYBOOKER_EMAIL}<br /></span></td>
        <td class="row2"><input class="post" type="text" maxlength="25" size="25" name="auction_paymentsystem_moneybooker_email" / value="{AUCTION_PAYMENTSYSTEM_MONEYBOOKER_EMAIL}"></td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{L_AUCTION_PAYMENTSYSTEM_ACTIVATE_DEBIT}</span><br>
             <span class="gensmall">{L_AUCTION_PAYMENTSYSTEM_ACTIVATE_DEBIT_EXPLAIN}</span></td>
        <td class="row2"><input type="radio" name="auction_paymentsystem_activate_debit" value="1" {AUCTION_PAYMENTSYSTEM_ACTIVATE_DEBIT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_paymentsystem_activate_debit" value="0" {AUCTION_PAYMENTSYSTEM_ACTIVATE_DEBIT_NO} /> {L_NO}</td>
    </tr>
    <!-- BEGIN user_points_active -->
    <tr>
         <td class="row2" colspan="2">{user_points_active.L_AUCTION_USER_POINTS_ACTIVE}</td>
    </tr>
    <tr>
         <td class="row1">{user_points_active.L_AUCTION_PAYMENTSYSTEM_USER_POINTS}</td>
         <td class="row2"><input type="radio" name="auction_paymentsystem_activate_user_points" value="1" {user_points_active.AUCTION_PAYMENTSYSTEM_ACTIVATE_USER_POINTS_YES} onclick="javascript:deactivate();"/> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_paymentsystem_activate_user_points" value="0" {user_points_active.AUCTION_PAYMENTSYSTEM_ACTIVATE_USER_POINTS_NO} /> {L_NO}</td>
    </tr>
    <!-- END user_points_active -->
    <!-- BEGIN user_points_not_active -->
    <tr>
         <td class="row2" colspan="2">{user_points_not_active.L_AUCTION_USER_POINTS_NOT_ACTIVE}</td>
    </tr>
    <!-- END user_points_not_active -->
    <tr>
        <td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
        </td>
    </tr>
</table></form>

<br clear="all" />