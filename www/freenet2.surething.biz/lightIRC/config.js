/*
 * lightIRC configuration
 * www.lightIRC.com
 * 
 * You can add or change these parameters to customize lightIRC.
 * Please see the full parameters list at www.lightirc.com/faq.html#parameters
 * 
 */   

var params = {};

/* Change these parameters */
params.host                         = "irc.surething.biz";
params.port                         = 6667;
params.policyPort                   = 8002;

/* Language for the user interface. Currently available translations:
 * bd: Bengali
 * bg: Bulgarian
 * da: Danish
 * de: German
 * el: Greek
 * en: English
 * es: Spanish
 * et: Estonian
 * fr: French
 * it: Italian
 * ja: Japanese
 * nl: Dutch
 * br: Brazilian Portuguese
 * ro: Romanian
 * ru: Russian
 * sq: Albanian
 * sr_cyr: Serbian Cyrillic
 * sr_lat: Serbian Latin
 * sv: Swedish
 * th: Thai 
 * tr: Turkish
 * uk: Ukrainian
 */
params.language                     = "en";

/* Relative or absolute URL to a lightIRC CSS file.
 * Using styles works only when you upload lightIRC to your webserver.
 * Example: css/lightblue.css 
 */
params.styleURL                     = "";

/* Nick to be used. A % character will be replaced through a random number */
params.nick                         = "freenet_user_%";
/* Channel to be joined after connecting. Multiple channels can be added like this: #lightIRC,#test,#help */
params.autojoin                     = "#freenet";
/* Commands to be executed after connecting. E.g.: /mode %nick% +x */
params.perform                      = "";

/* Whether the server window (and button) should be shown */
params.showServerWindow             = true;

/* Show a popup to enter a nickname */
params.showNickSelection            = true;
/* Adds a password field to the nick selection box */
params.showIdentifySelection        = false;

/* Show button to register a nickname */
params.showRegisterNicknameButton   = false;
/* Show button to register a channel */
params.showRegisterChannelButton    = false;

/* Opens new queries in background when set to true */
params.showNewQueriesInBackground   = false;

/* Position of the navigation container (where channel and query buttons appear). Valid values: left, right, top, bottom */
params.navigationPosition           = "bottom";


/* See more parameters at www.lightirc.com/faq.html#parameters */





/* ------------------------------------------------------------
 * Don't change anything from here!
 */
for(var key in params) {
  params[key] = params[key].toString().replace(/%/g, "%25");
}

function sendCommand(command) {
  swfobject.getObjectById('lightIRC').sendCommand(command);
}

function onContextMenuSelect(nick) {
  alert("onContextMenuSelect: "+nick);
}
